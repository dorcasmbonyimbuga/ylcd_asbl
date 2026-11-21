<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Upload;
use App\Core\Validator;
use App\Models\Projet;
use App\Models\Service;

class ProjetController extends AdminController
{
    private const BASE = 'admin/projets';
    private const TEXT_FIELDS = ['titre', 'categorie', 'description', 'objectif'];

    public function index(): void
    {
        $pager = $this->listing(Projet::class);
        $pager['items'] = Projet::attachServices($pager['items']);
        $this->view('projets/index', ['title' => __('Projets'), 'pager' => $pager]);
    }

    public function create(): void
    {
        $empty = array_fill_keys(['titre_fr', 'titre_en', 'categorie_fr', 'categorie_en', 'description_fr', 'description_en', 'objectif_fr', 'objectif_en'], '');
        $this->view('projets/form', [
            'title'    => __('Nouveau projet'),
            'projet'   => $empty + ['id' => null, 'image' => null, 'service_id' => null, 'ordre' => Projet::count() + 1],
            'services' => Service::all(),
        ]);
    }

    public function store(): void
    {
        $v = $this->validate();
        $image = $this->image($v, 'projets', null);
        if ($v->fails()) {
            $this->back(self::BASE . '/creer', $v->errors, $_POST);
        }
        Projet::create($this->data() + ['image' => $image]);
        flash('success', __('Projet ajouté.'));
        redirect(self::BASE);
    }

    public function edit(int $id): void
    {
        $projet = Projet::find($id) ?? $this->notFound(self::BASE);
        $this->view('projets/form', ['title' => __('Modifier le projet'), 'projet' => $projet, 'services' => Service::all()]);
    }

    public function update(int $id): void
    {
        $projet = Projet::find($id) ?? $this->notFound(self::BASE);
        $v = $this->validate();
        $image = $this->image($v, 'projets', $projet['image']);
        if ($v->fails()) {
            $this->back(self::BASE . "/$id/modifier", $v->errors, $_POST);
        }
        Projet::update($id, $this->data() + ['image' => $image]);
        flash('success', __('Projet mis à jour.'));
        redirect(self::BASE);
    }

    public function destroy(int $id): void
    {
        $projet = Projet::find($id) ?? $this->notFound(self::BASE);
        Upload::delete($projet['image']);
        Projet::delete($id);
        flash('success', __('Projet supprimé.'));
        redirect(self::BASE);
    }

    private function validate(): Validator
    {
        $serviceId = $this->intInput('service_id');
        return (new Validator($_POST))
            ->bilingual('titre', 'Le titre', 180, true)
            ->bilingual('categorie', "L'étiquette", 80)
            ->bilingual('description', 'La description', 5000, true)
            ->check($serviceId !== 0 && !Service::find($serviceId), 'service_id', 'Service invalide.');
    }

    private function data(): array
    {
        return $this->bilingual(self::TEXT_FIELDS, ['titre']) + [
            'service_id' => $this->intInput('service_id') ?: null,
            'ordre'      => $this->intInput('ordre'),
        ];
    }
}
