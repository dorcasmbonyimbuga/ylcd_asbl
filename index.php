<?php
/**
 * YLCD-ASBL — Contrôleur frontal.
 * Toutes les requêtes (site public, authentification, espace admin) passent par ce fichier.
 */
declare(strict_types=1);

define('ROOT', __DIR__);

require ROOT . '/app/Core/bootstrap.php';

$router = require ROOT . '/app/Config/routes.php';
$router->dispatch($_GET['url'] ?? '', $_SERVER['REQUEST_METHOD'] ?? 'GET');
