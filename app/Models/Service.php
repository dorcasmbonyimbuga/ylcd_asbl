<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

/** Domaines d'action / pôles de l'association (textes en FR et EN). */
class Service extends Model
{
    protected static string $table = 'services';
    protected static string $orderBy = 'ordre ASC, id ASC';
    protected static array $fillable = [
        'titre_fr', 'titre_en', 'slug', 'pole_fr', 'pole_en', 'slogan_fr', 'slogan_en',
        'resume_fr', 'resume_en', 'description_fr', 'description_en', 'points_fr', 'points_en', 'image', 'ordre',
    ];
    protected static array $searchable = [
        'titre_fr', 'titre_en', 'pole_fr', 'pole_en', 'slogan_fr', 'slogan_en', 'resume_fr', 'resume_en', 'slug',
    ];

    public static function slugTaken(string $slug, ?int $exceptId = null): bool
    {
        return static::count('slug = ? AND id <> ?', [$slug, $exceptId ?? 0]) > 0;
    }
}
