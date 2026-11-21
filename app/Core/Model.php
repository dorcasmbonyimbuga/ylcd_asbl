<?php
declare(strict_types=1);

namespace App\Core;

use PDO;

/**
 * Modèle de base : opérations CRUD communes à toutes les tables.
 * Chaque modèle déclare sa table, ses colonnes modifiables ($fillable) et son tri par défaut.
 * Seules les colonnes de $fillable sont acceptées en écriture (protection contre l'injection de champs).
 */
abstract class Model
{
    protected static string $table;
    protected static array $fillable = [];
    protected static string $orderBy = 'id DESC';
    /** Colonnes utilisées par la recherche (?q=...). */
    protected static array $searchable = [];
    /** Nombre d'éléments par page par défaut. */
    public const PER_PAGE = 10;

    protected static function db(): PDO
    {
        return Database::pdo();
    }

    public static function all(?string $orderBy = null): array
    {
        return static::db()
            ->query('SELECT * FROM ' . static::$table . ' ORDER BY ' . ($orderBy ?? static::$orderBy))
            ->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $stmt = static::db()->prepare('SELECT * FROM ' . static::$table . ' WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    /** Requête libre (paramètres préparés) sur la table du modèle. */
    public static function select(string $where = '1', array $params = [], ?string $orderBy = null, ?int $limit = null, int $offset = 0): array
    {
        $sql = 'SELECT * FROM ' . static::$table . ' WHERE ' . $where . ' ORDER BY ' . ($orderBy ?? static::$orderBy);
        if ($limit !== null) {
            $sql .= ' LIMIT ' . (int) $limit . ' OFFSET ' . max(0, $offset);
        }
        $stmt = static::db()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function first(string $where, array $params = []): ?array
    {
        return static::select($where, $params, null, 1)[0] ?? null;
    }

    /**
     * Ajoute à une condition WHERE une recherche "contient" sur les colonnes $searchable.
     * Retourne [where, params] à passer à select(), count() ou paginate().
     */
    public static function searchClause(string $search, string $where = '1', array $params = []): array
    {
        $search = trim($search);
        if ($search === '' || !static::$searchable) {
            return [$where, $params];
        }
        $like = '%' . addcslashes($search, '%_\\') . '%';
        $or = implode(' OR ', array_map(fn ($col) => "$col LIKE ?", static::$searchable));
        return ["($where) AND ($or)", [...$params, ...array_fill(0, count(static::$searchable), $like)]];
    }

    /**
     * Résultats paginés. Retourne :
     * ['items', 'total', 'page', 'pages', 'perPage', 'from', 'to']
     */
    public static function paginate(int $page, int $perPage = 10, string $where = '1', array $params = [], ?string $orderBy = null): array
    {
        $total = static::count($where, $params);
        $pages = max(1, (int) ceil($total / $perPage));
        $page = min(max(1, $page), $pages);
        return [
            'items'   => static::select($where, $params, $orderBy, $perPage, ($page - 1) * $perPage),
            'total'   => $total,
            'page'    => $page,
            'pages'   => $pages,
            'perPage' => $perPage,
            'from'    => $total ? ($page - 1) * $perPage + 1 : 0,
            'to'      => min($page * $perPage, $total),
        ];
    }

    public static function count(string $where = '1', array $params = []): int
    {
        $stmt = static::db()->prepare('SELECT COUNT(*) FROM ' . static::$table . ' WHERE ' . $where);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    }

    public static function create(array $data): int
    {
        $data = static::onlyFillable($data);
        $cols = array_keys($data);
        $sql = sprintf(
            'INSERT INTO %s (%s) VALUES (%s)',
            static::$table,
            implode(', ', $cols),
            implode(', ', array_fill(0, count($cols), '?'))
        );
        static::db()->prepare($sql)->execute(array_values($data));
        return (int) static::db()->lastInsertId();
    }

    public static function update(int $id, array $data): bool
    {
        $data = static::onlyFillable($data);
        if (!$data) {
            return false;
        }
        $set = implode(', ', array_map(fn ($c) => "$c = ?", array_keys($data)));
        $stmt = static::db()->prepare('UPDATE ' . static::$table . " SET $set WHERE id = ?");
        return $stmt->execute([...array_values($data), $id]);
    }

    public static function delete(int $id): bool
    {
        return static::db()->prepare('DELETE FROM ' . static::$table . ' WHERE id = ?')->execute([$id]);
    }

    protected static function onlyFillable(array $data): array
    {
        return array_intersect_key($data, array_flip(static::$fillable));
    }
}
