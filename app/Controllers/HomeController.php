<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Activite;
use App\Models\Membre;
use App\Models\Projet;
use App\Models\Service;

class HomeController extends Controller
{
    public function index(): void
    {
        $this->render('site/home', [
            'title'         => __('Accueil'),
            'services'      => Service::all(),
            'activites'     => Activite::datees(6),
            'nbActivites'   => Activite::count('est_publie = 1'),
            'projets'       => Projet::withService(),
            'membres'       => Membre::all(),
        ]);
    }
}
