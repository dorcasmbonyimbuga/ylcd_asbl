<?php
/**
 * Table des routes : URL => Contrôleur@action.
 * Pour ajouter une fonctionnalité : une route ici, une action dans un contrôleur, une vue.
 */
use App\Core\Router;

$router = new Router();

/* ---------------- Site public ---------------- */
$router->get('', 'HomeController@index');
$router->get('a-propos', 'PageController@about');
$router->get('services', 'PageController@services');
$router->get('activites', 'PageController@activites');
$router->get('equipe', 'PageController@equipe');
$router->post('contact', 'ContactController@send');

/* ---------------- Authentification ---------------- */
$router->get('connexion', 'AuthController@loginForm', ['guest']);
$router->post('connexion', 'AuthController@login', ['guest']);
$router->get('inscription', 'AuthController@registerForm', ['guest']);
$router->post('inscription', 'AuthController@register', ['guest']);
$router->post('deconnexion', 'AuthController@logout', ['auth']);

/* ---------------- Espace connecté (tous les rôles) ---------------- */
$router->group('', ['auth'], function (Router $r) {
    $r->get('compte/configuration', 'ProfileController@setupForm');
    $r->post('compte/configuration', 'ProfileController@setup');
    $r->get('profil', 'ProfileController@show');
    $r->post('profil', 'ProfileController@update');
    $r->post('profil/mot-de-passe', 'ProfileController@password');
    $r->post('profil/photo/supprimer', 'ProfileController@deletePhoto');
});

/* ---------------- Administration (rôle admin) ---------------- */
$router->group('admin', ['admin'], function (Router $r) {
    $r->get('', 'Admin\DashboardController@index');

    // CRUD identiques pour activités, projets, services, membres
    foreach ([
        'activites' => 'Admin\ActiviteController',
        'projets'   => 'Admin\ProjetController',
        'services'  => 'Admin\ServiceController',
        'membres'   => 'Admin\MembreController',
    ] as $path => $ctrl) {
        $r->get($path, "$ctrl@index");
        $r->get("$path/creer", "$ctrl@create");
        $r->post($path, "$ctrl@store");
        $r->get("$path/{id}/modifier", "$ctrl@edit");
        $r->post("$path/{id}", "$ctrl@update");
        $r->post("$path/{id}/supprimer", "$ctrl@destroy");
    }

    $r->get('utilisateurs', 'Admin\UserController@index');
    $r->post('utilisateurs/{id}/role', 'Admin\UserController@role');
    $r->post('utilisateurs/{id}/supprimer', 'Admin\UserController@destroy');

    $r->get('messages', 'Admin\MessageController@index');
    $r->get('messages/{id}', 'Admin\MessageController@show');
    $r->post('messages/{id}/supprimer', 'Admin\MessageController@destroy');
});

return $router;
