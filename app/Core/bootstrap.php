<?php
/**
 * Initialisation de l'application : configuration, autoload, session, gestion des erreurs.
 */
declare(strict_types=1);

$GLOBALS['__config'] = require ROOT . '/app/Config/config.php';

require ROOT . '/app/Core/helpers.php';

// Autoload PSR-4 simple : App\Core\Router => app/Core/Router.php
spl_autoload_register(function (string $class): void {
    if (strncmp($class, 'App\\', 4) !== 0) {
        return;
    }
    $file = ROOT . '/app/' . str_replace('\\', '/', substr($class, 4)) . '.php';
    if (is_file($file)) {
        require $file;
    }
});

date_default_timezone_set(config('app.timezone', 'UTC'));
mb_internal_encoding('UTF-8');

$isDev = config('app.env') === 'dev';
ini_set('display_errors', $isDev ? '1' : '0');
error_reporting(E_ALL);

set_exception_handler(function (Throwable $e) use ($isDev): void {
    log_error($e->getMessage() . ' @ ' . $e->getFile() . ':' . $e->getLine());
    http_response_code(500);
    if ($isDev) {
        echo '<pre style="padding:20px;background:#fff3f3;color:#900;white-space:pre-wrap">';
        echo e(get_class($e) . ': ' . $e->getMessage() . "\n\n" . $e->getTraceAsString());
        echo '</pre>';
    } else {
        echo 'Une erreur est survenue. Veuillez réessayer plus tard.';
    }
});

// Session sécurisée
session_name('ylcd_session');
session_set_cookie_params([
    'lifetime' => 0,
    'path'     => base_path() . '/',
    'httponly' => true,
    'samesite' => 'Lax',
    'secure'   => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
]);
session_start();

// Langue de l'interface (gère aussi le changement via ?lang=fr|en)
App\Core\Lang::init();

// Les données "flash" (messages, anciennes valeurs, erreurs) ne vivent qu'une requête
$_SESSION['_flash_now'] = $_SESSION['_flash_next'] ?? [];
$_SESSION['_flash_next'] = [];
