<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

/** Messages envoyés via le formulaire de contact (une copie part par email à l'association). */
class Contact extends Model
{
    protected static string $table = 'contacts';
    protected static string $orderBy = 'created_at DESC, id DESC';
    protected static array $searchable = ['nom', 'email', 'telephone', 'sujet', 'message'];
    protected static array $fillable = [
        'nom', 'email', 'telephone', 'sujet', 'message', 'est_lu', 'email_envoye', 'ip',
    ];

    public static function nonLus(): int
    {
        return static::count('est_lu = 0');
    }
}
