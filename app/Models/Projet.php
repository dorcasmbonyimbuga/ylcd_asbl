<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

/** Projets de l'association (textes en FR et EN), éventuellement rattachés à un service. */
class Projet extends Model
{
    protected static string $table = 'projets';
    protected static string $orderBy = 'ordre ASC, id ASC';
    protected static array $fillable = [
        'titre_fr', 'titre_en', 'categorie_fr', 'categorie_en', 'description_fr', 'description_en',
        'objectif_fr', 'objectif_en', 'image', 'service_id', 'ordre',
    ];
    protected static array $searchable = [
        'titre_fr', 'titre_en', 'categorie_fr', 'categorie_en', 'description_fr', 'description_en',
    ];

    /** Tous les projets avec leur service lié. */
    public static function withService(): array
    {
        return static::attachServices(static::all());
    }

    /** Ajoute la clé "service" (ligne du service lié ou null) à chaque projet. */
    public static function attachServices(array $projets): array
    {
        $services = [];
        foreach (Service::all() as $s) {
            $services[$s['id']] = $s;
        }
        foreach ($projets as &$p) {
            $p['service'] = $services[$p['service_id']] ?? null;
        }
        return $projets;
    }
}
