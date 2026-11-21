<?php
declare(strict_types=1);

namespace App\Core;

/** Contrôleur de base : rendu des vues et retour vers un formulaire en cas d'erreur. */
class Controller
{
    /**
     * Affiche une vue dans un layout.
     * $view   : chemin relatif à app/Views sans ".php" (ex: "site/home")
     * $layout : "site", "admin", "auth" ou null (vue seule)
     */
    public function render(string $view, array $data = [], ?string $layout = 'site'): void
    {
        $content = $this->capture($view, $data);
        if ($layout === null) {
            echo $content;
            return;
        }
        extract($data, EXTR_SKIP);
        require ROOT . '/app/Views/layouts/' . $layout . '.php';
    }

    protected function capture(string $view, array $data = []): string
    {
        $file = ROOT . '/app/Views/' . $view . '.php';
        if (!is_file($file)) {
            throw new \RuntimeException("Vue introuvable : $view");
        }
        extract($data, EXTR_SKIP);
        ob_start();
        require $file;
        return (string) ob_get_clean();
    }

    /** Renvoie vers un formulaire avec les erreurs et les valeurs saisies (sauf mots de passe). */
    protected function back(string $path, array $errors, array $input = [], ?string $message = null): never
    {
        foreach (['password', 'password_confirm', 'current_password'] as $secret) {
            unset($input[$secret]);
        }
        flash('errors', $errors);
        flash('old', $input);
        flash('error', $message ?? __('Le formulaire contient des erreurs. Veuillez les corriger.'));
        redirect($path);
    }

    /** Valeur texte nettoyée d'un champ POST. */
    protected function input(string $key, string $default = ''): string
    {
        $v = $_POST[$key] ?? $default;
        if (!is_string($v)) {
            return $default;
        }
        // Texte mal encodé (ex : copié depuis un ancien logiciel) : conversion en UTF-8
        if (!mb_check_encoding($v, 'UTF-8')) {
            $v = mb_convert_encoding($v, 'UTF-8', 'Windows-1252');
        }
        return trim($v);
    }
}
