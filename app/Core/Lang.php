<?php
declare(strict_types=1);

namespace App\Core;

/**
 * Langue de l'interface (français par défaut, anglais).
 * - Changement : n'importe quelle URL + ?lang=en (ou ?lang=fr), mémorisé en session et en cookie.
 * - Les textes de l'interface sont écrits en français dans le code et traduits via app/Lang/en.php.
 * - Les contenus de la base existent en deux colonnes (<champ>_fr / <champ>_en) : voir tr().
 */
final class Lang
{
    public const LOCALES = ['fr' => 'Français', 'en' => 'English'];
    public const DEFAULT = 'fr';

    private static string $locale = self::DEFAULT;
    private static array $dictionary = [];

    public static function init(): void
    {
        $requested = $_GET['lang'] ?? null;
        if (is_string($requested) && isset(self::LOCALES[$requested])) {
            $_SESSION['locale'] = $requested;
            setcookie('lang', $requested, [
                'expires'  => time() + 365 * 24 * 3600,
                'path'     => base_path() . '/',
                'samesite' => 'Lax',
            ]);
            // On recharge la même page sans le paramètre ?lang=
            $parts = parse_url($_SERVER['REQUEST_URI'] ?? '/');
            parse_str($parts['query'] ?? '', $query);
            unset($query['lang']);
            header('Location: ' . ($parts['path'] ?? '/') . ($query ? '?' . http_build_query($query) : ''));
            exit;
        }

        $locale = $_SESSION['locale'] ?? $_COOKIE['lang'] ?? self::DEFAULT;
        self::$locale = isset(self::LOCALES[$locale]) ? $locale : self::DEFAULT;

        $file = ROOT . '/app/Lang/' . self::$locale . '.php';
        self::$dictionary = self::$locale !== self::DEFAULT && is_file($file) ? require $file : [];
    }

    public static function locale(): string
    {
        return self::$locale;
    }

    /** Traduit un texte français de l'interface et remplace les :parametres. */
    public static function get(string $text, array $params = []): string
    {
        $translated = self::$dictionary[$text] ?? $text;
        if (!$params) {
            return $translated;
        }
        // strtr remplace la correspondance la plus longue (":total" avant ":to")
        // et ne remplace jamais à l'intérieur d'une valeur déjà insérée.
        $pairs = [];
        foreach ($params as $key => $value) {
            $pairs[':' . $key] = (string) $value;
        }
        return strtr($translated, $pairs);
    }
}
