<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

/**
 * Utilisateurs du site (table users).
 * Le champ "confirmation du mot de passe" n'est pas une colonne : il sert uniquement
 * à vérifier la saisie dans les formulaires. Seul le hash du mot de passe est stocké.
 */
class User extends Model
{
    public const ROLE_ADMIN = 'admin';
    public const ROLE_MEMBRE = 'membre';
    public const ROLES = [self::ROLE_ADMIN => 'Administrateur', self::ROLE_MEMBRE => 'Membre'];

    protected static string $table = 'users';
    protected static string $orderBy = 'created_at DESC, id DESC';
    protected static array $searchable = ['username', 'email'];
    protected static array $fillable = [
        'username', 'email', 'photo', 'password', 'role', 'must_change_credentials', 'last_login_at',
    ];

    /** Connexion avec le nom d'utilisateur OU l'email. */
    public static function findByLogin(string $login): ?array
    {
        return static::first('username = ? OR email = ?', [$login, $login]);
    }

    public static function usernameTaken(string $username, ?int $exceptId = null): bool
    {
        return static::count('username = ? AND id <> ?', [$username, $exceptId ?? 0]) > 0;
    }

    public static function emailTaken(string $email, ?int $exceptId = null): bool
    {
        return static::count('email = ? AND id <> ?', [$email, $exceptId ?? 0]) > 0;
    }

    public static function adminCount(): int
    {
        return static::count('role = ?', [self::ROLE_ADMIN]);
    }

    public static function hash(string $password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }
}
