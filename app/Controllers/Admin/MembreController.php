<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Upload;
use App\Core\Validator;
use App\Models\Membre;

/** Membres de l'équipe affichés sur le site (page Équipe et accueil). */
class MembreController extends AdminController
{
    private const BASE = 'admin/membres';

    public function index(): void
    {
        $this->view('membres/index', ['title' => __('Membres de l’équipe'), 'pager' => $this->listing(Membre::class)]);
    }

    public function create(): void
    {
        $this->view('membres/form', [
            'title'  => __('Nouveau membre'),
            'membre' => ['id' => null, 'nom' => '', 'fonction_fr' => '', 'fonction_en' => '', 'bio_fr' => '', 'bio_en' => '',
                         'photo' => null, 'email' => '', 'linkedin' => '', 'est_coordinateur' => 0, 'ordre' => Membre::count() + 1],
        ]);
    }

    public function store(): void
    {
        $v = $this->validate();
        $photo = $this->image($v, 'membres', null, 'photo');
        if ($v->fails()) {
            $this->back(self::BASE . '/creer', $v->errors, $_POST);
        }
        $id = Membre::create($this->data() + ['photo' => $photo]);
        $this->uniqueCoordinator($id);
        flash('success', __('Membre ajouté.'));
        redirect(self::BASE);
    }

    public function edit(int $id): void
    {
        $membre = Membre::find($id) ?? $this->notFound(self::BASE);
        $this->view('membres/form', ['title' => __('Modifier le membre'), 'membre' => $membre]);
    }

    public function update(int $id): void
    {
        $membre = Membre::find($id) ?? $this->notFound(self::BASE);
        $v = $this->validate();
        $photo = $this->image($v, 'membres', $membre['photo'], 'photo');
        if ($v->fails()) {
            $this->back(self::BASE . "/$id/modifier", $v->errors, $_POST);
        }
        Membre::update($id, $this->data() + ['photo' => $photo]);
        $this->uniqueCoordinator($id);
        flash('success', __('Membre mis à jour.'));
        redirect(self::BASE);
    }

    public function destroy(int $id): void
    {
        $membre = Membre::find($id) ?? $this->notFound(self::BASE);
        Upload::delete($membre['photo']);
        Membre::delete($id);
        flash('success', __('Membre supprimé.'));
        redirect(self::BASE);
    }

    /** Un seul coordinateur à la fois. */
    private function uniqueCoordinator(int $id): void
    {
        if (!empty($_POST['est_coordinateur'])) {
            foreach (Membre::select('est_coordinateur = 1 AND id <> ?', [$id]) as $other) {
                Membre::update((int) $other['id'], ['est_coordinateur' => 0]);
            }
        }
    }

    private function validate(): Validator
    {
        return (new Validator($_POST))
            ->required('nom', 'Le nom')->max('nom', 120, 'Le nom')
            ->bilingual('fonction', 'La fonction', 120)
            ->email('email')->max('email', 150, "L'email")
            ->url('linkedin')->max('linkedin', 255, 'Le lien');
    }

    private function data(): array
    {
        return $this->bilingual(['fonction', 'bio']) + [
            'nom'              => $this->input('nom'),
            'email'            => $this->input('email') ?: null,
            'linkedin'         => $this->input('linkedin') ?: null,
            'est_coordinateur' => empty($_POST['est_coordinateur']) ? 0 : 1,
            'ordre'            => $this->intInput('ordre'),
        ];
    }
}
