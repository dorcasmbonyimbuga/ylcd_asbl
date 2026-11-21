<?php
/**
 * Configuration générale.
 * Pour les valeurs propres à une machine (mots de passe SMTP, base de production...),
 * créez app/Config/config.local.php qui retourne un tableau avec seulement les clés à surcharger.
 */
$config = [
    'app' => [
        'name'        => 'YLCD-ASBL',
        'env'         => 'dev',               // 'dev' affiche les erreurs, 'prod' les masque
        'base_url'    => '',                  // vide = détection automatique (ex: /ylcd_asbl)
        'pretty_urls' => true,                // false si mod_rewrite n'est pas disponible
        'timezone'    => 'Africa/Lubumbashi', // Goma (UTC+2)
    ],

    'db' => [
        'host'    => '127.0.0.1',
        'port'    => 3306,
        'name'    => 'ylcd_asbl',
        'user'    => 'root',
        'pass'    => '',
        'charset' => 'utf8mb4',
    ],

    'mail' => [
        // Adresse de l'association qui reçoit les messages du formulaire de contact
        'to'        => 'ylcdrc21@gmail.com',
        'from'      => 'ylcdrc21@gmail.com',
        'from_name' => 'Site web YLCD-ASBL',
        // Sous WAMP, la fonction mail() de PHP n'envoie rien : configurez un SMTP.
        // Gmail : activez la validation en 2 étapes puis créez un « mot de passe d'application ».
        'smtp' => [
            'enabled' => false,
            'host'    => 'smtp.gmail.com',
            'port'    => 587,
            'secure'  => 'tls',               // 'tls' (port 587) ou 'ssl' (port 465)
            'user'    => '',
            'pass'    => '',
        ],
    ],

    'upload' => [
        'max_image_mb' => 5,
        'max_video_mb' => 60,
    ],

    // Chiffres affichés sur le site (non liés à une table)
    'site' => [
        'annee_creation' => 2021,
        'nombre_membres' => 15,
    ],
];

$local = __DIR__ . '/config.local.php';
if (is_file($local)) {
    $config = array_replace_recursive($config, require $local);
}

return $config;
