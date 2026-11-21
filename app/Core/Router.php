<?php
declare(strict_types=1);

namespace App\Core;

/**
 * Routeur : associe une URL + une méthode HTTP à "Controleur@action".
 * Les segments {id} capturent un nombre entier transmis à l'action.
 *
 * Middlewares disponibles :
 *  - guest : réservé aux visiteurs non connectés (connexion, inscription)
 *  - auth  : utilisateur connecté (admin ou membre)
 *  - admin : utilisateur connecté avec le rôle admin
 */
final class Router
{
    /** Routes accessibles à un utilisateur qui doit encore modifier ses identifiants par défaut. */
    private const SETUP_ALLOWED = ['compte/configuration', 'deconnexion'];

    private array $routes = [];

    public function get(string $path, string $handler, array $middleware = []): self
    {
        return $this->add('GET', $path, $handler, $middleware);
    }

    public function post(string $path, string $handler, array $middleware = []): self
    {
        return $this->add('POST', $path, $handler, $middleware);
    }

    /** Regroupe des routes sous un préfixe et des middlewares communs. */
    public function group(string $prefix, array $middleware, callable $callback): void
    {
        $sub = new self();
        $callback($sub);
        foreach ($sub->routes as $r) {
            $r['path'] = trim($prefix . '/' . $r['path'], '/');
            $r['middleware'] = array_unique([...$middleware, ...$r['middleware']]);
            $this->routes[] = $r;
        }
    }

    private function add(string $method, string $path, string $handler, array $middleware): self
    {
        $this->routes[] = ['method' => $method, 'path' => trim($path, '/'), 'handler' => $handler, 'middleware' => $middleware];
        return $this;
    }

    public function dispatch(string $url, string $method): void
    {
        $url = trim(parse_url($url, PHP_URL_PATH) ?? '', '/');
        $method = strtoupper($method);
        $pathExists = false;

        foreach ($this->routes as $route) {
            $regex = '#^' . preg_replace('#\{(\w+)\}#', '(?P<$1>\d+)', $route['path']) . '$#';
            if (!preg_match($regex, $url, $m)) {
                continue;
            }
            $pathExists = true;
            if ($route['method'] !== $method) {
                continue;
            }

            $GLOBALS['__current_path'] = $url;

            if ($method === 'POST' && !verify_csrf()) {
                flash('error', __('Votre session a expiré. Veuillez réessayer.'));
                redirect($_SERVER['HTTP_REFERER'] ?? url());
            }

            $this->runMiddleware($route['middleware'], $route['path']);

            $params = array_map('intval', array_filter($m, 'is_string', ARRAY_FILTER_USE_KEY));
            [$class, $action] = explode('@', $route['handler']);
            $fqcn = 'App\\Controllers\\' . $class;
            (new $fqcn())->$action(...array_values($params));
            return;
        }

        http_response_code($pathExists ? 405 : 404);
        (new Controller())->render('errors/404', ['title' => __('Page introuvable')]);
    }

    private function runMiddleware(array $middleware, string $routePath): void
    {
        foreach ($middleware as $mw) {
            switch ($mw) {
                case 'guest':
                    if (Auth::check()) {
                        redirect(Auth::homePath());
                    }
                    break;

                case 'auth':
                case 'admin':
                    if (!Auth::check()) {
                        $_SESSION['_intended'] = $_SERVER['REQUEST_URI'] ?? null;
                        flash('error', __('Veuillez vous connecter pour accéder à cette page.'));
                        redirect('connexion');
                    }
                    // Première connexion de l'admin par défaut : changement d'identifiants obligatoire
                    if (Auth::mustChangeCredentials() && !in_array($routePath, self::SETUP_ALLOWED, true)) {
                        redirect('compte/configuration');
                    }
                    if ($mw === 'admin' && !Auth::isAdmin()) {
                        http_response_code(403);
                        (new Controller())->render('errors/403', ['title' => __('Accès refusé')]);
                        exit;
                    }
                    break;
            }
        }
    }
}
