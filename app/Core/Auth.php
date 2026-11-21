<?php
declare(strict_types=1);

namespace App\Core;

use App\Models\User;

/** Gestion de l'utilisateur connecté (session). */
final class Auth
{
    private static ?array $user = null;
    private static bool $loaded = false;

    /** Utilisateur connecté, relu en base à chaque requête (un changement de rôle s'applique immédiatement). */
    public static function user(): ?array
    {
        if (!self::$loaded) {
            self::$loaded = true;
            if (!empty($_SESSION['user_id'])) {
                self::$user = User::find((int) $_SESSION['user_id']);
                if (!self::$user) {
                    unset($_SESSION['user_id']);
                }
            }
        }
        return self::$user;
    }

    public static function check(): bool
    {
        return self::user() !== null;
    }

    public static function id(): ?int
    {
        return self::user()['id'] ?? null;
    }

    public static function isAdmin(): bool
    {
        return (self::user()['role'] ?? null) === User::ROLE_ADMIN;
    }

    public static function mustChangeCredentials(): bool
    {
        return (bool) (self::user()['must_change_credentials'] ?? false);
    }

    /** Page d'accueil de l'espace connecté selon le rôle. */
    public static function homePath(): string
    {
        if (self::mustChangeCredentials()) {
            return 'compte/configuration';
        }
        return self::isAdmin() ? 'admin' : 'profil';
    }

    public static function login(array $user): void
    {
        session_regenerate_id(true);
        $_SESSION['user_id'] = (int) $user['id'];
        self::$user = $user;
        self::$loaded = true;
        User::update((int) $user['id'], ['last_login_at' => date('Y-m-d H:i:s')]);
    }

    public static function logout(): void
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
        }
        session_destroy();
        self::$user = null;
    }

    /** Recharge l'utilisateur après une modification de son compte. */
    public static function refresh(): void
    {
        self::$loaded = false;
        self::user();
    }
}
