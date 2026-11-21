<?php
/**
 * Champ image unique avec aperçu (une seule image, commune aux deux langues).
 * Variables : $fieldName (ex: "image"), $currentImage (chemin ou null), $fieldLabel (traduit), $round (bool, aperçu rond).
 */
$round = $round ?? false;
?>
<label class="form-label"><?= e($fieldLabel ?? __('Image')) ?></label>
<div class="image-field<?= $round ? ' round' : '' ?>">
  <div class="image-field-preview" id="preview-<?= e($fieldName) ?>">
    <?php if ($currentImage): ?>
      <img src="<?= e(media_url($currentImage)) ?>" alt="">
    <?php else: ?>
      <i class="fa-regular fa-image"></i>
    <?php endif; ?>
  </div>
  <label class="btn btn-sm btn-light border w-100 mt-2" for="<?= e($fieldName) ?>">
    <i class="fa-solid fa-upload me-1"></i> <?= e($currentImage ? __('Remplacer') : __('Choisir une image')) ?>
  </label>
  <input type="file" name="<?= e($fieldName) ?>" id="<?= e($fieldName) ?>" accept="image/*" class="d-none" data-single-preview="#preview-<?= e($fieldName) ?>">
  <?php if ($currentImage): ?>
  <div class="form-check mt-2">
    <input class="form-check-input" type="checkbox" name="remove_<?= e($fieldName) ?>" id="remove_<?= e($fieldName) ?>" value="1">
    <label class="form-check-label small text-danger" for="remove_<?= e($fieldName) ?>"><?= e(__("Retirer l'image")) ?></label>
  </div>
  <?php endif; ?>
  <?php if (error($fieldName)): ?><div class="text-danger small mt-1"><?= e(error($fieldName)) ?></div><?php endif; ?>
  <div class="form-text"><?= e(__('JPG, PNG, WEBP — :max Mo max.', ['max' => (int) config('upload.max_image_mb')])) ?></div>
</div>
