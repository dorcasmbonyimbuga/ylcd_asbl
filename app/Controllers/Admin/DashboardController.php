<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Models\Activite;
use App\Models\Contact;
use App\Models\Membre;
use App\Models\Projet;
use App\Models\Service;
use App\Models\User;

class DashboardController extends AdminController
{
    public function index(): void
    {
        $this->view('dashboard', [
            'title' => __('Tableau de bord'),
            'stats' => [
                'activites' => Activite::count(),
                'projets'   => Projet::count(),
                'services'  => Service::count(),
                'membres'   => Membre::count(),
                'users'     => User::count(),
                'messages'  => Contact::count(),
                'nonLus'    => Contact::nonLus(),
            ],
            'messages'  => Contact::select('1', [], null, 5),
            'users'     => User::select('1', [], null, 5),
            'activites' => Activite::select('1', [], null, 5),
        ]);
    }
}
