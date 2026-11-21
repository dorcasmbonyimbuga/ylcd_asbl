<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Upload;
use App\Core\Validator;

/** Base des contrôleurs de l'espace admin : layout, image unique, champs bilingues, liste paginée. */
abstract class AdminController extends Controller
{
    protected function view(string $view, array $data = []): void
    {
        $this->render('admin/' . $view, $data, 'admin');
    }

    /**
     * Liste paginée (10 par page) avec recherche ?q= sur les colonnes $searchable du modèle.
     * @param class-string<\App\Core\Model> $model
     */
    protected function listing(string $model, string $where = '1', array $params = [], ?string $orderBy = null): array
    {
        [$where, $params] = $model::searchClause(search_query(), $where, $params);
        return $model::paginate(current_page(), $model::PER_PAGE, $where, $params, $orderBy);
    }

    /**
     * Lit les champs bilingues du formulaire : ['titre'] => titre_fr, titre_en.
     * Les champs facultatifs vides sont enregistrés à NULL.
     */
    protected function bilingual(array $fields, array $required = []): array
    {
        $data = [];
        foreach ($fields as $field) {
            foreach (['fr', 'en'] as $code) {
                $value = $this->input($field . '_' . $code);
                $data[$field . '_' . $code] = $value !== '' || in_array($field, $required, true) ? $value : null;
            }
        }
        return $data;
    }

    /**
     * Gère le champ image d'un formulaire :
     *  - nouveau fichier valide => enregistré dans uploads/<dossier>/, l'ancien est supprimé
     *  - case "retirer l'image" cochée => image retirée
     *  - sinon => image actuelle conservée
     */
    protected function image(Validator $v, string $folder, ?string $current, string $field = 'image'): ?string
    {
        if ($v->fails()) {
            return $current;
        }
        if (Upload::has($_FILES[$field] ?? null)) {
            try {
                $path = Upload::store($_FILES[$field], $folder)['path'];
                Upload::delete($current);
                return $path;
            } catch (\RuntimeException $e) {
                $v->check(true, $field, $e->getMessage());
                return $current;
            }
        }
        if (!empty($_POST['remove_' . $field])) {
            Upload::delete($current);
            return null;
        }
        return $current;
    }

    protected function intInput(string $key, int $default = 0): int
    {
        $value = filter_var($_POST[$key] ?? null, FILTER_VALIDATE_INT);
        return $value !== false && $value !== null ? (int) $value : $default;
    }

    protected function notFound(string $redirect): never
    {
        flash('error', __('Élément introuvable.'));
        redirect($redirect);
    }
}
