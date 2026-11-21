<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Upload;
use App\Core\Validator;
use App\Models\Service;

class ServiceController extends AdminController
{
    private const BASE = 'admin/services';
    private const TEXT_FIELDS = ['titre', 'pole', 'slogan', 'resume', 'description', 'points'];

    public function index(): void
    {
        $this->view('services/index', ['title' => __('Services'), 'pager' => $this->listing(Service::class)]);
    }

    public function create(): void
    {
        $empty = [];
        foreach (self::TEXT_FIELDS as $f) {
            $empty[$f . '_fr'] = $empty[$f . '_en'] = '';
        }
        $this->view('services/form', [
            'title'   => __('Nouveau service'),
            'service' => $empty + ['id' => null, 'slug' => '', 'image' => null, 'ordre' => Service::count() + 1],
        ]);
    }

    public function store(): void
    {
        $slug = $this->slug();
        $v = $this->validate($slug);
        $image = $this->image($v, 'services', null);
        if ($v->fails()) {
            $this->back(self::BASE . '/creer', $v->errors, $_POST);
        }
        Service::create($this->data($slug) + ['image' => $image]);
        flash('success', __('Service ajouté.'));
        redirect(self::BASE);
    }

    public function edit(int $id): void
    {
        $service = Service::find($id) ?? $this->notFound(self::BASE);
        $this->view('services/form', ['title' => __('Modifier le service'), 'service' => $service]);
    }

    public function update(int $id): void
    {
        $service = Service::find($id) ?? $this->notFound(self::BASE);
        $slug = $this->slug();
        $v = $this->validate($slug, $id);
        $image = $this->image($v, 'services', $service['image']);
        if ($v->fails()) {
            $this->back(self::BASE . "/$id/modifier", $v->errors, $_POST);
        }
        Service::update($id, $this->data($slug) + ['image' => $image]);
        flash('success', __('Service mis à jour.'));
        redirect(self::BASE);
    }

    public function destroy(int $id): void
    {
        $service = Service::find($id) ?? $this->notFound(self::BASE);
        Upload::delete($service['image']);
        Service::delete($id); // les projets liés gardent leur contenu (service_id => NULL)
        flash('success', __('Service supprimé.'));
        redirect(self::BASE);
    }

    /** Identifiant d'ancre (ex: "langues"), généré depuis le titre français s'il est vide. */
    private function slug(): string
    {
        return slugify($this->input('slug') ?: $this->input('titre_fr'));
    }

    private function validate(string $slug, ?int $id = null): Validator
    {
        return (new Validator($_POST))
            ->bilingual('titre', 'Le titre', 150, true)
            ->bilingual('pole', 'Le pôle', 120)
            ->bilingual('slogan', 'Le slogan', 255)
            ->bilingual('resume', 'Le résumé', 500)
            ->check($slug === '', 'slug', 'Identifiant invalide.')
            ->check($slug !== '' && Service::slugTaken($slug, $id), 'slug', 'Cet identifiant est déjà utilisé par un autre service.');
    }

    private function data(string $slug): array
    {
        return $this->bilingual(self::TEXT_FIELDS, ['titre']) + [
            'slug'  => $slug,
            'ordre' => $this->intInput('ordre'),
        ];
    }
}
