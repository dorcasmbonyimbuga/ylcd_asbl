<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Activite;
use App\Models\Membre;
use App\Models\Service;

/** Pages publiques du site. */
class PageController extends Controller
{
    public function about(): void
    {
        $this->render('site/about', [
            'title'       => __('A propos'),
            'services'    => Service::all(),
            'nbActivites' => Activite::count('est_publie = 1'),
        ]);
    }

    public function services(): void
    {
        $this->render('site/services', [
            'title'    => __('Services'),
            'services' => Service::all(),
        ]);
    }

    /** Activités : recherche (?q=), catégorie (?categorie=) et pagination (10 par page) de la frise. */
    public function activites(): void
    {
        $categorie = (string) ($_GET['categorie'] ?? '');
        $search = search_query();

        [$where, $params] = Activite::publicClause(true, $categorie, $search);
        $pager = Activite::paginate(current_page(), Activite::PER_PAGE, $where, $params, 'date_activite DESC, id DESC');
        $pager['items'] = Activite::withMedias($pager['items']);

        // Les activités régulières (sans date) sont affichées sous la frise, sur la première page
        $regulieres = [];
        if ($pager['page'] === 1) {
            [$where, $params] = Activite::publicClause(false, $categorie, $search);
            $regulieres = Activite::withMedias(Activite::select($where, $params, 'id ASC'));
        }

        $this->render('site/activites', [
            'title'        => __('Activités'),
            'pager'        => $pager,
            'regulieres'   => $regulieres,
            'categorie'    => isset(Activite::CATEGORIES[$categorie]) ? $categorie : '',
            'search'       => $search,
            'withLightbox' => true,
        ]);
    }

    public function equipe(): void
    {
        $this->render('site/equipe', [
            'title'        => __('Equipe'),
            'coordinateur' => Membre::coordinateur(),
            'membres'      => Membre::equipe(),
        ]);
    }
}
