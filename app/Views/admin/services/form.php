<?php
/** Formulaire service. Variable : $service. */
$isEdit = !empty($service['id']);
?>
<a href="<?= url('admin/services') ?>" class="back-link"><i class="fa-solid fa-arrow-left me-1"></i> <?= e(__('Retour aux services')) ?></a>

<form action="<?= url($isEdit ? 'admin/services/' . $service['id'] : 'admin/services') ?>" method="post" enctype="multipart/form-data" novalidate>
  <?= csrf_field() ?>
  <div class="row g-4">
    <div class="col-lg-8">
      <div class="panel">
        <h5 class="panel-title"><i class="fa-solid fa-language"></i> <?= e(__('Textes (français et anglais)')) ?></h5>
        <?= lang_tabs('srv', ['titre', 'pole', 'slogan', 'resume', 'description', 'points']) ?>
        <div class="tab-content">
          <?php foreach (['fr', 'en'] as $code): ?>
          <div class="tab-pane fade<?= $code === 'fr' ? ' show active' : '' ?>" id="srv-<?= $code ?>" role="tabpanel">
            <div class="row g-3">
              <div class="col-12"><?= form_field("titre_$code", __('Titre'), $service["titre_$code"], ['required' => true, 'maxlength' => 150]) ?></div>
              <div class="col-md-6"><?= form_field("pole_$code", __('Pôle (badge)'), $service["pole_$code"], ['maxlength' => 120, 'placeholder' => $code === 'fr' ? 'PÔLE 01 — ...' : 'HUB 01 — ...']) ?></div>
              <div class="col-md-6"><?= form_field("slogan_$code", __('Slogan'), $service["slogan_$code"], ['maxlength' => 255]) ?></div>
              <div class="col-12"><?= form_field("resume_$code", __('Résumé (accueil)'), $service["resume_$code"], ['rows' => 3, 'maxlength' => 500]) ?></div>
              <div class="col-12"><?= form_field("description_$code", __('Description (page Services)'), $service["description_$code"], ['rows' => 4]) ?></div>
              <div class="col-12"><?= form_field("points_$code", __('Points clés'), $service["points_$code"], ['rows' => 5, 'help' => __('Une ligne par point. « Mot : texte » met le mot en gras.')]) ?></div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
    <div class="col-lg-4">
      <div class="panel sticky-panel">
        <h5 class="panel-title"><i class="fa-solid fa-sliders"></i> <?= e(__('Paramètres')) ?></h5>
        <div class="mb-3">
          <label class="form-label" for="slug"><?= e(__('Identifiant (ancre)')) ?></label>
          <div class="input-group">
            <span class="input-group-text">#</span>
            <input type="text" name="slug" id="slug" class="form-control<?= invalid('slug') ?>" value="<?= e(old('slug', $service['slug'])) ?>" placeholder="auto">
            <?= error_feedback('slug') ?>
          </div>
          <div class="form-text"><?= e(__('Laissez vide pour le générer depuis le titre.')) ?></div>
        </div>
        <div class="mb-3"><?= form_field('ordre', __("Ordre d'affichage"), $service['ordre'], ['type' => 'number']) ?></div>
        <div class="mb-4">
          <?php $fieldName = 'image'; $currentImage = $service['image']; $fieldLabel = __('Image');
          require ROOT . '/app/Views/partials/image_field.php'; ?>
        </div>
        <button class="btn btn-ylcd w-100"><i class="fa-solid fa-floppy-disk me-1"></i> <?= e($isEdit ? __('Enregistrer') : __('Créer le service')) ?></button>
      </div>
    </div>
  </div>
</form>
