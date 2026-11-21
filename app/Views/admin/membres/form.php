<?php
/** Formulaire membre de l'équipe. Variable : $membre. */
$isEdit = !empty($membre['id']);
?>
<a href="<?= url('admin/membres') ?>" class="back-link"><i class="fa-solid fa-arrow-left me-1"></i> <?= e(__("Retour à l'équipe")) ?></a>

<form action="<?= url($isEdit ? 'admin/membres/' . $membre['id'] : 'admin/membres') ?>" method="post" enctype="multipart/form-data" novalidate>
  <?= csrf_field() ?>
  <div class="row g-4">
    <div class="col-lg-4 order-lg-2">
      <div class="panel">
        <?php $fieldName = 'photo'; $currentImage = $membre['photo']; $fieldLabel = __('Photo'); $round = true;
        require ROOT . '/app/Views/partials/image_field.php'; ?>
      </div>
    </div>
    <div class="col-lg-8 order-lg-1">
      <div class="panel">
        <h5 class="panel-title"><i class="fa-regular fa-id-card"></i> <?= e(__('Informations')) ?></h5>
        <div class="row g-3 mb-4">
          <div class="col-md-6"><?= form_field('nom', __('Nom complet'), $membre['nom'], ['required' => true, 'maxlength' => 120, 'help' => __('Le nom est identique dans les deux langues.')]) ?></div>
          <div class="col-md-6"><?= form_field('ordre', __("Ordre d'affichage"), $membre['ordre'], ['type' => 'number']) ?></div>
          <div class="col-md-6"><?= form_field('email', __('Email'), $membre['email'], ['type' => 'email']) ?></div>
          <div class="col-md-6"><?= form_field('linkedin', __('Lien LinkedIn'), $membre['linkedin'], ['type' => 'url', 'placeholder' => 'https://...']) ?></div>
          <div class="col-12">
            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" role="switch" name="est_coordinateur" id="est_coordinateur" value="1" <?= old('est_coordinateur', $membre['est_coordinateur']) ? 'checked' : '' ?>>
              <label class="form-check-label" for="est_coordinateur"><?= e(__('Coordinateur (mis en avant)')) ?></label>
            </div>
          </div>
        </div>

        <h5 class="panel-title"><i class="fa-solid fa-language"></i> <?= e(__('Textes (français et anglais)')) ?></h5>
        <?= lang_tabs('mbr', ['fonction', 'bio']) ?>
        <div class="tab-content">
          <?php foreach (['fr', 'en'] as $code): ?>
          <div class="tab-pane fade<?= $code === 'fr' ? ' show active' : '' ?>" id="mbr-<?= $code ?>" role="tabpanel">
            <div class="mb-3"><?= form_field("fonction_$code", __('Fonction'), $membre["fonction_$code"], ['maxlength' => 120, 'placeholder' => $code === 'fr' ? 'Coordinateur, Formateur...' : 'Coordinator, Trainer...']) ?></div>
            <div><?= form_field("bio_$code", __('Biographie / mot'), $membre["bio_$code"], ['rows' => 4, 'help' => __('Pour le coordinateur, ce texte remplace le « Mot de la coordination ».')]) ?></div>
          </div>
          <?php endforeach; ?>
        </div>

        <div class="text-end mt-4">
          <button class="btn btn-ylcd"><i class="fa-solid fa-floppy-disk me-1"></i> <?= e($isEdit ? __('Enregistrer') : __('Ajouter le membre')) ?></button>
        </div>
      </div>
    </div>
  </div>
</form>
