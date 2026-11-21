<?php
/** Formulaire projet. Variables : $projet, $services. */
$isEdit = !empty($projet['id']);
?>
<a href="<?= url('admin/projets') ?>" class="back-link"><i class="fa-solid fa-arrow-left me-1"></i> <?= e(__('Retour aux projets')) ?></a>

<form action="<?= url($isEdit ? 'admin/projets/' . $projet['id'] : 'admin/projets') ?>" method="post" enctype="multipart/form-data" novalidate>
  <?= csrf_field() ?>
  <div class="row g-4">
    <div class="col-lg-8">
      <div class="panel">
        <h5 class="panel-title"><i class="fa-solid fa-language"></i> <?= e(__('Textes (français et anglais)')) ?></h5>
        <?= lang_tabs('prj', ['titre', 'categorie', 'description', 'objectif']) ?>
        <div class="tab-content">
          <?php foreach (['fr', 'en'] as $code): ?>
          <div class="tab-pane fade<?= $code === 'fr' ? ' show active' : '' ?>" id="prj-<?= $code ?>" role="tabpanel">
            <div class="row g-3">
              <div class="col-md-8"><?= form_field("titre_$code", __('Titre'), $projet["titre_$code"], ['required' => true, 'maxlength' => 180]) ?></div>
              <div class="col-md-4"><?= form_field("categorie_$code", __('Étiquette'), $projet["categorie_$code"], ['maxlength' => 80, 'placeholder' => __('Formation, Santé...')]) ?></div>
              <div class="col-12"><?= form_field("description_$code", __('Description'), $projet["description_$code"], ['rows' => 5, 'required' => true]) ?></div>
              <div class="col-12"><?= form_field("objectif_$code", __('Objectif'), $projet["objectif_$code"], ['rows' => 3]) ?></div>
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
          <label class="form-label" for="service_id"><?= e(__('Service lié')) ?></label>
          <select name="service_id" id="service_id" class="form-select<?= invalid('service_id') ?>">
            <option value=""><?= e(__('— Aucun —')) ?></option>
            <?php foreach ($services as $s): ?>
            <option value="<?= (int) $s['id'] ?>" <?= (int) old('service_id', $projet['service_id']) === (int) $s['id'] ? 'selected' : '' ?>><?= e(tr($s, 'titre')) ?></option>
            <?php endforeach; ?>
          </select>
          <?= error_feedback('service_id') ?>
        </div>
        <div class="mb-3"><?= form_field('ordre', __("Ordre d'affichage"), $projet['ordre'], ['type' => 'number']) ?></div>
        <div class="mb-4">
          <?php $fieldName = 'image'; $currentImage = $projet['image']; $fieldLabel = __('Image');
          require ROOT . '/app/Views/partials/image_field.php'; ?>
        </div>
        <button class="btn btn-ylcd w-100"><i class="fa-solid fa-floppy-disk me-1"></i> <?= e($isEdit ? __('Enregistrer') : __('Créer le projet')) ?></button>
      </div>
    </div>
  </div>
</form>
