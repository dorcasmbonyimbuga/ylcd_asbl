<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Upload;
use App\Core\Validator;
use App\Models\Activite;
use App\Models\ActiviteMedia;

/** Gestion des activités (textes FR / EN) et de leurs médias (plusieurs images / vidéos). */
class ActiviteController extends AdminController
{
    private const BASE = 'admin/activites';
    private const TEXT_FIELDS = ['titre', 'sous_titre', 'description'];

    public function index(): void
    {
        $categorie = (string) ($_GET['categorie'] ?? '');
        [$where, $params] = isset(Activite::CATEGORIES[$categorie]) ? ['categorie = ?', [$categorie]] : ['1', []];
        $pager = $this->listing(Activite::class, $where, $params);
        $pager['items'] = Activite::withMedias($pager['items']);

        $this->view('activites/index', [
            'title'     => __('Activités'),
            'pager'     => $pager,
            'categorie' => $categorie,
        ]);
    }

    public function create(): void
    {
        $empty = array_fill_keys(['titre_fr', 'titre_en', 'sous_titre_fr', 'sous_titre_en', 'description_fr', 'description_en', 'date_activite'], '');
        $this->view('activites/form', [
            'title'    => __('Nouvelle activité'),
            'activite' => $empty + ['id' => null, 'categorie' => 'formation', 'est_publie' => 1, 'medias' => []],
        ]);
    }

    public function store(): void
    {
        $v = $this->validate();
        $uploaded = $this->uploadMedias($v);
        if ($v->fails()) {
            $this->back(self::BASE . '/creer', $v->errors, $_POST);
        }

        $id = Activite::create($this->data() + ['created_by' => Auth::id()]);
        $this->attachMedias($id, $uploaded);

        flash('success', __('Activité ajoutée.'));
        redirect(self::BASE);
    }

    public function edit(int $id): void
    {
        $activite = Activite::find($id) ?? $this->notFound(self::BASE);
        $activite['medias'] = ActiviteMedia::forActivite($id);
        $this->view('activites/form', ['title' => __('Modifier l’activité'), 'activite' => $activite]);
    }

    public function update(int $id): void
    {
        Activite::find($id) ?? $this->notFound(self::BASE);

        $v = $this->validate();
        $uploaded = $this->uploadMedias($v);
        if ($v->fails()) {
            $this->back(self::BASE . "/$id/modifier", $v->errors, $_POST);
        }

        Activite::update($id, $this->data());

        // Médias cochés pour suppression
        foreach ((array) ($_POST['delete_medias'] ?? []) as $mediaId) {
            $media = ActiviteMedia::find((int) $mediaId);
            if ($media && (int) $media['activite_id'] === $id) {
                Upload::delete($media['chemin']);
                ActiviteMedia::delete((int) $media['id']);
            }
        }
        $this->attachMedias($id, $uploaded);

        // Média principal choisi : il passe en première position
        $main = (int) ($_POST['media_principal'] ?? 0);
        $mainMedia = $main ? ActiviteMedia::find($main) : null;
        $medias = ActiviteMedia::forActivite($id);
        if ($mainMedia && (int) $mainMedia['activite_id'] === $id && $medias && (int) $medias[0]['id'] !== $main) {
            ActiviteMedia::update($main, ['ordre' => (int) $medias[0]['ordre'] - 1]);
        }

        flash('success', __('Activité mise à jour.'));
        redirect(self::BASE);
    }

    public function destroy(int $id): void
    {
        Activite::find($id) ?? $this->notFound(self::BASE);
        foreach (ActiviteMedia::forActivite($id) as $media) {
            Upload::delete($media['chemin']);
        }
        Activite::delete($id); // les lignes activite_medias sont supprimées en cascade
        flash('success', __('Activité supprimée.'));
        redirect(self::BASE);
    }

    /* ---------- Outils ---------- */

    private function validate(): Validator
    {
        return (new Validator($_POST))
            ->bilingual('titre', 'Le titre', 180, true)
            ->bilingual('sous_titre', 'Le sous-titre', 255)
            ->in('categorie', array_keys(Activite::CATEGORIES), 'La catégorie')
            ->date('date_activite');
    }

    private function data(): array
    {
        return $this->bilingual(self::TEXT_FIELDS, ['titre']) + [
            'categorie'     => $this->input('categorie'),
            'date_activite' => $this->input('date_activite') ?: null,
            'est_publie'    => empty($_POST['est_publie']) ? 0 : 1,
        ];
    }

    /** Enregistre les fichiers envoyés ; en cas d'erreur, rien n'est conservé. */
    private function uploadMedias(Validator $v): array
    {
        if ($v->fails()) {
            return [];
        }
        $stored = [];
        try {
            foreach (Upload::many($_FILES['medias'] ?? null) as $file) {
                $stored[] = Upload::store($file, 'activites', true);
            }
        } catch (\RuntimeException $e) {
            foreach ($stored as $s) {
                Upload::delete($s['path']);
            }
            $v->check(true, 'medias', $e->getMessage());
            return [];
        }
        return $stored;
    }

    private function attachMedias(int $activiteId, array $uploaded): void
    {
        $ordre = ActiviteMedia::nextOrder($activiteId);
        foreach ($uploaded as $u) {
            ActiviteMedia::create(['activite_id' => $activiteId, 'chemin' => $u['path'], 'type' => $u['type'], 'ordre' => $ordre++]);
        }
    }
}
