<?php
/** Bouton de suppression avec confirmation. Variables : $deleteUrl, $deleteLabel (texte de confirmation, déjà traduit). */
?>
<form action="<?= url($deleteUrl) ?>" method="post" class="d-inline" data-confirm="<?= e($deleteLabel ?? __('Supprimer cet élément ? Cette action est définitive.')) ?>">
  <?= csrf_field() ?>
  <button class="btn btn-icon btn-icon-danger" title="<?= e(__('Supprimer')) ?>"><i class="fa-regular fa-trash-can"></i></button>
</form>
