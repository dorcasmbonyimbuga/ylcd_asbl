<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

/**
 * Membres de l'équipe affichés sur le site (différents des comptes utilisateurs).
 * Le nom est un nom propre (une seule colonne) ; fonction et biographie existent en FR et EN.
 */
class Membre extends Model
{
    protected static string $table = 'membres';
    protected static string $orderBy = 'est_coordinateur DESC, ordre ASC, id ASC';
    protected static array $fillable = [
        'nom', 'fonction_fr', 'fonction_en', 'bio_fr', 'bio_en', 'photo', 'email', 'linkedin', 'est_coordinateur', 'ordre',
    ];
    protected static array $searchable = ['nom', 'fonction_fr', 'fonction_en', 'email'];

    public static function coordinateur(): ?array
    {
        return static::first('est_coordinateur = 1');
    }

    /** Membres hors coordinateur. */
    public static function equipe(): array
    {
        return static::select('est_coordinateur = 0');
    }
}
