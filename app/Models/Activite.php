<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

/**
 * Activités de l'association (textes en FR et EN). Les images / vidéos sont dans la table
 * activite_medias (une activité peut en avoir plusieurs). Sans date = "activité régulière".
 */
class Activite extends Model
{
    /** Catégories : clé en base => libellé (en français, traduit par __()). */
    public const CATEGORIES = [
        'formation'     => 'Formations',
        'ceremonie'     => 'Remises de certificats',
        'social'        => 'Social & humanitaire',
        'environnement' => 'Environnement',
    ];

    protected static string $table = 'activites';
    protected static string $orderBy = 'date_activite IS NULL, date_activite DESC, id DESC';
    protected static array $fillable = [
        'titre_fr', 'titre_en', 'sous_titre_fr', 'sous_titre_en', 'description_fr', 'description_en',
        'categorie', 'date_activite', 'est_publie', 'created_by',
    ];
    protected static array $searchable = [
        'titre_fr', 'titre_en', 'sous_titre_fr', 'sous_titre_en', 'description_fr', 'description_en',
    ];

    /** Activités datées et publiées (accueil), avec leurs médias. */
    public static function datees(?int $limit = null): array
    {
        return static::withMedias(static::select('est_publie = 1 AND date_activite IS NOT NULL', [], 'date_activite DESC, id DESC', $limit));
    }

    /** Condition WHERE pour la page publique : publiées, catégorie et recherche optionnelles. */
    public static function publicClause(bool $datees, string $categorie = '', string $search = ''): array
    {
        $where = 'est_publie = 1 AND date_activite IS ' . ($datees ? 'NOT NULL' : 'NULL');
        $params = [];
        if (isset(self::CATEGORIES[$categorie])) {
            $where .= ' AND categorie = ?';
            $params[] = $categorie;
        }
        return static::searchClause($search, $where, $params);
    }

    /** Ajoute la clé "medias" à chaque activité (une seule requête). */
    public static function withMedias(array $activites): array
    {
        if (!$activites) {
            return [];
        }
        $grouped = [];
        foreach (ActiviteMedia::forActivites(array_column($activites, 'id')) as $m) {
            $grouped[$m['activite_id']][] = $m;
        }
        foreach ($activites as &$a) {
            $a['medias'] = $grouped[$a['id']] ?? [];
        }
        return $activites;
    }

    /** Libellé traduit d'une catégorie. */
    public static function categorieLabel(string $key): string
    {
        return __(self::CATEGORIES[$key] ?? ucfirst($key));
    }
}
