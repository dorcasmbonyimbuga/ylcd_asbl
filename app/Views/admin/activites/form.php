<?php
/** Formulaire activité (création / modification). Variable : $activite (avec 'medias'). */
use App\Models\Activite;

$isEdit = !empty($activite['id']);
$action = $isEdit ? 'admin/activites/' . $activite['id'] : 'admin/activites';
?>
<a href="<?= url('admin/activites') ?>" class="back-link"><i class="fa-solid fa-arrow-left me-1"></i> <?= e(__('Retour aux activités')) ?></a>

<form action="<?= url($action) ?>" method="post" enctype="multipart/form-data" novalidate>
  <?= csrf_field() ?>
  <div class="row g-4">
    <div class="col-lg-8">
      <div class="panel">
        <h5 class="panel-title"><i class="fa-solid fa-language"></i> <?= e(__('Textes (français et anglais)')) ?></h5>
        <p class="small text-muted"><?= e(__('Remplissez les deux langues : le site affiche la version correspondant à la langue choisie par le visiteur.')) ?></p>
        <?= lang_tabs('act', ['titre', 'sous_titre', 'description']) ?>
        <div class="tab-content">
          <?php foreach (['fr', 'en'] as $code): ?>
          <div class="tab-pane fade<?= $code === 'fr' ? ' show active' : '' ?>" id="act-<?= $code ?>" role="tabpanel">
            <div class="mb-3"><?= form_field("titre_$code", __('Titre'), $activite["titre_$code"], ['required' => true, 'maxlength' => 180]) ?></div>
            <div class="mb-3"><?= form_field("sous_titre_$code", __('Sous-titre'), $activite["sous_titre_$code"], ['maxlength' => 255, 'placeholder' => __("Phrase courte affichée sur l'accueil")]) ?></div>
            <div><?= form_field("description_$code", __('Description'), $activite["description_$code"], ['rows' => 7]) ?></div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="panel mt-4">
        <h5 class="panel-title"><i class="fa-solid fa-photo-film"></i> <?= e(__('Images & vidéos')) ?></h5>
        <?php if ($activite['medias']): ?>
        <p class="small text-muted"><?= e(__('Choisissez le média principal (affiché en grand) et cochez ceux à supprimer.')) ?></p>
        <div class="media-grid mb-3">
          <?php foreach ($activite['medias'] as $i => $m): ?>
          <div class="media-item">
            <?php if ($m['type'] === 'video'): ?>
              <video src="<?= e(media_url($m['chemin'])) ?>" preload="metadata" muted></video>
              <span class="media-type"><i class="fa-solid fa-video"></i></span>
            <?php else: ?>
              <img src="<?= e(media_url($m['chemin'])) ?>" alt="">
            <?php endif; ?>
            <div class="media-actions">
              <label title="<?= e(__('Média principal')) ?>"><input type="radio" name="media_principal" value="<?= (int) $m['id'] ?>" <?= $i === 0 ? 'checked' : '' ?>> <?= e(__('Principal')) ?></label>
              <label class="text-danger" title="<?= e(__('Supprimer')) ?>"><input type="checkbox" name="delete_medias[]" value="<?= (int) $m['id'] ?>"> <?= e(__('Suppr.')) ?></label>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
        <label class="dropzone<?= error('medias') ? ' is-invalid' : '' ?>" for="medias">
          <i class="fa-solid fa-cloud-arrow-up"></i>
          <strong><?= e(__('Ajouter des images ou des vidéos')) ?></strong>
          <span><?= e(__('JPG, PNG, WEBP (:img Mo max) — MP4, WEBM (:vid Mo max). Sélection multiple possible.', ['img' => (int) config('upload.max_image_mb'), 'vid' => (int) config('upload.max_video_mb')])) ?></span>
          <input type="file" name="medias[]" id="medias" accept="image/*,video/mp4,video/webm" multiple class="d-none" data-preview="#medias-preview" data-new-label="<?= e(__('Nouveau :')) ?>">
        </label>
        <?php if (error('medias')): ?><div class="text-danger small mt-2"><?= e(error('medias')) ?></div><?php endif; ?>
        <div id="medias-preview" class="media-grid mt-3"></div>
      </div>
    </div>

    <div class="col-lg-4">
      <div class="panel sticky-panel">
        <h5 class="panel-title"><i class="fa-solid fa-sliders"></i> <?= e(__('Paramètres')) ?></h5>
        <div class="mb-3">
          <label class="form-label" for="categorie"><?= e(__('Catégorie')) ?> *</label>
          <select name="categorie" id="categorie" class="form-select<?= invalid('categorie') ?>">
            <?php foreach (Activite::CATEGORIES as $key => $label): ?>
            <option value="<?= e($key) ?>" <?= old('categorie', $activite['categorie']) === $key ? 'selected' : '' ?>><?= e(__($label)) ?></option>
            <?php endforeach; ?>
          </select>
          <?= error_feedback('categorie') ?>
        </div>
        <div class="mb-3">
          <?= form_field('date_activite', __("Date de l'activité"), $activite['date_activite'], ['type' => 'date', 'help' => __('Le 1er du mois = affiché « Mois Année ». Vide = activité régulière.')]) ?>
        </div>
        <div class="form-check form-switch mb-4">
          <input type="hidden" name="est_publie" value="0">
          <input class="form-check-input" type="checkbox" role="switch" name="est_publie" id="est_publie" value="1" <?= old('est_publie', $activite['est_publie']) ? 'checked' : '' ?>>
          <label class="form-check-label" for="est_publie"><?= e(__('Publiée sur le site')) ?></label>
        </div>
        <button class="btn btn-ylcd w-100"><i class="fa-solid fa-floppy-disk me-1"></i> <?= e($isEdit ? __('Enregistrer les modifications') : __('Créer l’activité')) ?></button>
      </div>
    </div>
  </div>
</form>
