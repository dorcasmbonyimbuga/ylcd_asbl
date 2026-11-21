<?php
/**
 * Fonctions utilitaires disponibles partout (contrôleurs et vues).
 */
declare(strict_types=1);

use App\Core\Auth;

/** Lit une valeur de configuration avec la notation "section.cle". */
function config(string $key, mixed $default = null): mixed
{
    $value = $GLOBALS['__config'];
    foreach (explode('.', $key) as $part) {
        if (!is_array($value) || !array_key_exists($part, $value)) {
            return $default;
        }
        $value = $value[$part];
    }
    return $value;
}

/** Échappe une valeur pour l'affichage HTML (protection XSS). */
function e(mixed $value): string
{
    return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES, 'UTF-8');
}

/** Chemin de base de l'application (ex: "/ylcd_asbl"), sans slash final. */
function base_path(): string
{
    static $base = null;
    if ($base === null) {
        $configured = (string) config('app.base_url', '');
        $base = $configured !== ''
            ? rtrim($configured, '/')
            : rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/');
    }
    return $base;
}

/** URL d'une route de l'application : url('activites'), url('admin/activites/3/modifier'). */
function url(string $path = ''): string
{
    $path = ltrim($path, '/');
    $fragment = '';
    if (($pos = strpos($path, '#')) !== false) {
        $fragment = substr($path, $pos);
        $path = substr($path, 0, $pos);
    }
    if ($path === '') {
        return base_path() . '/' . $fragment;
    }
    if (config('app.pretty_urls', true)) {
        return base_path() . '/' . $path . $fragment;
    }
    $query = '';
    if (($q = strpos($path, '?')) !== false) {
        $query = '&' . substr($path, $q + 1);
        $path = substr($path, 0, $q);
    }
    return base_path() . '/index.php?url=' . $path . $query . $fragment;
}

/** URL d'un fichier statique du dossier assets/. */
function asset(string $path): string
{
    return base_path() . '/assets/' . ltrim($path, '/');
}

/** URL d'un fichier stocké en base (ex: "uploads/activites/abc.jpg"). */
function media_url(?string $path): string
{
    if (!$path) {
        return asset('img/favicon_ylcd_64x64.png');
    }
    if (preg_match('#^https?://#', $path)) {
        return $path;
    }
    return base_path() . '/' . ltrim($path, '/');
}

function redirect(string $path): never
{
    header('Location: ' . (str_starts_with($path, 'http') || str_starts_with($path, '/') ? $path : url($path)));
    exit;
}

/* ---------------- Sécurité CSRF ---------------- */

function csrf_token(): string
{
    if (empty($_SESSION['_csrf'])) {
        $_SESSION['_csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['_csrf'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="_csrf" value="' . e(csrf_token()) . '">';
}

function verify_csrf(): bool
{
    $token = $_POST['_csrf'] ?? '';
    return is_string($token) && !empty($_SESSION['_csrf']) && hash_equals($_SESSION['_csrf'], $token);
}

/* ---------------- Messages flash & formulaires ---------------- */

/** flash('success', 'Enregistré') pour écrire, flash('success') pour lire. */
function flash(string $key, mixed $value = null): mixed
{
    if (func_num_args() === 2) {
        $_SESSION['_flash_next'][$key] = $value;
        return null;
    }
    return $_SESSION['_flash_now'][$key] ?? null;
}

/** Ancienne valeur d'un champ après une erreur de validation. */
function old(string $key, mixed $default = ''): mixed
{
    $old = flash('old') ?? [];
    return $old[$key] ?? $default;
}

/** Message d'erreur d'un champ, ou null. */
function error(string $key): ?string
{
    $errors = flash('errors') ?? [];
    return $errors[$key] ?? null;
}

/** Classe Bootstrap "is-invalid" + message sous le champ. */
function invalid(string $key): string
{
    return error($key) ? ' is-invalid' : '';
}

function error_feedback(string $key): string
{
    $msg = error($key);
    return $msg ? '<div class="invalid-feedback">' . e($msg) . '</div>' : '';
}

/* ---------------- Navigation ---------------- */

/** Chemin de la route courante (ex: "activites", "admin/projets"). */
function current_path(): string
{
    return $GLOBALS['__current_path'] ?? '';
}

/** Retourne "active activ" si la page courante correspond (utilisé dans les menus). */
function is_active(string $path, bool $prefix = false): string
{
    $current = current_path();
    $match = $prefix ? ($current === $path || str_starts_with($current, $path . '/')) : $current === $path;
    return $match ? 'active activ' : '';
}

/* ---------------- Utilisateurs ---------------- */

function auth(): ?array
{
    return Auth::user();
}

/** Initiales d'un nom : "Dorcas Mbonyimbuga" => "DM", "admin" => "AD". */
function initials(string $name): string
{
    $parts = preg_split('/[\s._-]+/u', trim($name), -1, PREG_SPLIT_NO_EMPTY) ?: ['?'];
    if (count($parts) >= 2) {
        $text = mb_substr($parts[0], 0, 1) . mb_substr($parts[count($parts) - 1], 0, 1);
    } else {
        $text = mb_substr($parts[0], 0, 2);
    }
    return mb_strtoupper($text);
}

/** Avatar : photo si disponible, sinon pastille avec les initiales. */
function avatar(array $user, int $size = 40, string $class = ''): string
{
    $style = "width:{$size}px;height:{$size}px;";
    if (!empty($user['photo'])) {
        return '<img src="' . e(media_url($user['photo'])) . '" alt="' . e($user['username']) . '" class="avatar-img ' . e($class) . '" style="' . $style . '">';
    }
    $font = max(11, (int) round($size * 0.4));
    return '<span class="avatar-initials ' . e($class) . '" style="' . $style . 'font-size:' . $font . 'px">' . e(initials($user['username'])) . '</span>';
}

/* ---------------- Langue (FR / EN) ---------------- */

/** Traduit un texte de l'interface : __('Se connecter'), __('Bonjour, :name', ['name' => $n]). */
function __(string $text, array $params = []): string
{
    return \App\Core\Lang::get($text, $params);
}

/** Langue courante : "fr" ou "en". */
function locale(): string
{
    return \App\Core\Lang::locale();
}

/**
 * Valeur d'un champ bilingue d'une ligne de la base dans la langue courante :
 * tr($activite, 'titre') lit titre_fr ou titre_en. Si la traduction est vide,
 * on affiche l'autre langue plutôt qu'un texte vide.
 */
function tr(?array $row, string $field): string
{
    if (!$row) {
        return '';
    }
    $value = trim((string) ($row[$field . '_' . locale()] ?? ''));
    if ($value === '') {
        foreach (array_keys(\App\Core\Lang::LOCALES) as $other) {
            $value = trim((string) ($row[$field . '_' . $other] ?? ''));
            if ($value !== '') {
                break;
            }
        }
    }
    return $value;
}

/** URL de la page courante dans une autre langue. */
function lang_url(string $code): string
{
    $parts = parse_url($_SERVER['REQUEST_URI'] ?? '/');
    parse_str($parts['query'] ?? '', $query);
    $query['lang'] = $code;
    return ($parts['path'] ?? '/') . '?' . http_build_query($query);
}

/* ---------------- Formulaires ---------------- */

/**
 * Champ de formulaire (input ou textarea) avec ancienne valeur et message d'erreur.
 * Options : type, required, rows (=> textarea), placeholder, help, maxlength.
 */
function form_field(string $name, string $label, mixed $value, array $opt = []): string
{
    $id = 'f_' . $name;
    $value = old($name, $value ?? '');
    $required = !empty($opt['required']);
    $attrs = ' name="' . e($name) . '" id="' . e($id) . '" class="form-control' . invalid($name) . '"'
        . (!empty($opt['placeholder']) ? ' placeholder="' . e($opt['placeholder']) . '"' : '')
        . (!empty($opt['maxlength']) ? ' maxlength="' . (int) $opt['maxlength'] . '"' : '')
        . ($required ? ' required' : '');

    $html = '<label class="form-label" for="' . e($id) . '">' . e($label) . ($required ? ' *' : '') . '</label>';
    if (!empty($opt['rows'])) {
        $html .= '<textarea rows="' . (int) $opt['rows'] . '"' . $attrs . '>' . e($value) . '</textarea>';
    } else {
        $html .= '<input type="' . e($opt['type'] ?? 'text') . '" value="' . e($value) . '"' . $attrs . '>';
    }
    $html .= error_feedback($name);
    if (!empty($opt['help'])) {
        $html .= '<div class="form-text">' . e($opt['help']) . '</div>';
    }
    return $html;
}

/**
 * Onglets "Français / English" d'un formulaire bilingue. Un point rouge signale
 * un onglet contenant une erreur. $fields : noms des champs sans suffixe (titre, description...).
 */
function lang_tabs(string $id, array $fields): string
{
    $html = '<ul class="nav lang-tabs mb-3" role="tablist">';
    foreach (\App\Core\Lang::LOCALES as $code => $label) {
        $hasError = false;
        foreach ($fields as $f) {
            $hasError = $hasError || error($f . '_' . $code) !== null;
        }
        $active = $code === 'fr';
        $html .= '<li class="nav-item" role="presentation"><button type="button" class="nav-link' . ($active ? ' active' : '') . '"'
            . ' data-bs-toggle="tab" data-bs-target="#' . e($id) . '-' . $code . '" role="tab">'
            . '<span class="lang-code">' . strtoupper($code) . '</span> ' . e($label)
            . ($hasError ? ' <span class="tab-error" title="' . e(__('Erreur')) . '"></span>' : '')
            . '</button></li>';
    }
    return $html . '</ul>';
}

/* ---------------- Thème clair / sombre ---------------- */

/** Thème choisi par l'utilisateur (cookie posé par assets/js/theme.js), clair par défaut. */
function theme(): string
{
    return ($_COOKIE['theme'] ?? 'light') === 'dark' ? 'dark' : 'light';
}

/* ---------------- Pagination & recherche ---------------- */

/** Page demandée (?page=N), au minimum 1. */
function current_page(): int
{
    return max(1, (int) ($_GET['page'] ?? 1));
}

/** Texte recherché (?q=...). */
function search_query(): string
{
    $q = $_GET['q'] ?? '';
    return is_string($q) ? trim(mb_substr($q, 0, 100)) : '';
}

/** URL de la page courante en modifiant certains paramètres (?page, ?q, ?categorie...). */
function query_url(array $changes): string
{
    $query = $_GET;
    unset($query['url']);
    foreach ($changes as $key => $value) {
        if ($value === null || $value === '') {
            unset($query[$key]);
        } else {
            $query[$key] = $value;
        }
    }
    if (($query['page'] ?? null) == 1) {
        unset($query['page']);
    }
    return url(current_path() . ($query ? '?' . http_build_query($query) : ''));
}

/* ---------------- Dates & textes ---------------- */

const MOIS = [
    'fr' => ['', 'janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre'],
    'en' => ['', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
];

/**
 * Date d'activité dans la langue courante. Si le jour est le 1er, on affiche seulement
 * le mois : "Juillet 2026" / "July 2026" ; sinon "4 juillet 2024" / "4 July 2024".
 */
function date_activite(?string $date): string
{
    if (!$date) {
        return '';
    }
    $t = strtotime($date);
    $mois = (MOIS[locale()] ?? MOIS['fr'])[(int) date('n', $t)];
    $jour = (int) date('j', $t);
    return $jour === 1
        ? mb_convert_case(mb_substr($mois, 0, 1), MB_CASE_UPPER) . mb_substr($mois, 1) . ' ' . date('Y', $t)
        : $jour . ' ' . $mois . ' ' . date('Y', $t);
}

/** Date longue localisée : "21 novembre 2026 à 11:39" / "21 November 2026 at 11:39". */
function date_long(?string $datetime, bool $withTime = true): string
{
    if (!$datetime) {
        return '';
    }
    $t = strtotime($datetime);
    $txt = date('j', $t) . ' ' . (MOIS[locale()] ?? MOIS['fr'])[(int) date('n', $t)] . ' ' . date('Y', $t);
    return $withTime ? $txt . ' ' . __('à') . ' ' . date('H:i', $t) : $txt;
}

function excerpt(?string $text, int $length = 120): string
{
    $text = trim(preg_replace('/\s+/u', ' ', (string) $text));
    return mb_strlen($text) > $length ? mb_substr($text, 0, $length - 1) . '…' : $text;
}

/** "Santé & Humanitaire" => "sante-humanitaire" */
function slugify(string $text): string
{
    if (class_exists('Normalizer')) {
        $text = preg_replace('/\p{Mn}/u', '', \Normalizer::normalize($text, \Normalizer::FORM_D) ?: $text);
    } else {
        $text = strtr($text, ['à' => 'a', 'â' => 'a', 'ä' => 'a', 'ç' => 'c', 'é' => 'e', 'è' => 'e', 'ê' => 'e', 'ë' => 'e',
            'î' => 'i', 'ï' => 'i', 'ô' => 'o', 'ö' => 'o', 'ù' => 'u', 'û' => 'u', 'ü' => 'u', 'É' => 'e', 'È' => 'e', 'À' => 'a']);
    }
    $text = preg_replace('/[^a-z0-9]+/', '-', mb_strtolower((string) $text));
    return mb_substr(trim($text, '-'), 0, 80);
}

/** Convertit un texte multi-lignes en tableau (une ligne = un élément). */
function lines(?string $text): array
{
    return array_values(array_filter(array_map('trim', preg_split('/\R/u', (string) $text))));
}

function log_error(string $message): void
{
    $dir = ROOT . '/storage/logs';
    if (is_dir($dir) && is_writable($dir)) {
        @file_put_contents($dir . '/app.log', '[' . date('Y-m-d H:i:s') . '] ' . $message . PHP_EOL, FILE_APPEND);
    }
}
