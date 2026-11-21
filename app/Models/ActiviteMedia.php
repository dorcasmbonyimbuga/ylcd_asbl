<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

/** Images et vidéos d'une activité (seul le chemin du fichier est stocké). */
class ActiviteMedia extends Model
{
    protected static string $table = 'activite_medias';
    protected static string $orderBy = 'ordre ASC, id ASC';
    protected static array $fillable = ['activite_id', 'chemin', 'type', 'ordre'];

    public static function forActivite(int $activiteId): array
    {
        return static::select('activite_id = ?', [$activiteId]);
    }

    public static function forActivites(array $ids): array
    {
        $ids = array_map('intval', $ids);
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        return static::select("activite_id IN ($placeholders)", $ids);
    }

    public static function nextOrder(int $activiteId): int
    {
        $stmt = static::db()->prepare('SELECT COALESCE(MAX(ordre), 0) + 1 FROM activite_medias WHERE activite_id = ?');
        $stmt->execute([$activiteId]);
        return (int) $stmt->fetchColumn();
    }
}
