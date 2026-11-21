<?php
/** Messages flash affichés en "toast" (succès, erreur, avertissement). Les messages sont déjà traduits. */
$__flashes = array_filter([
    'success' => flash('success'),
    'danger'  => flash('error'),
    'warning' => flash('warning'),
]);
if ($__flashes): ?>
<div class="flash-stack">
  <?php foreach ($__flashes as $type => $msg): ?>
  <div class="alert alert-<?= $type ?> alert-dismissible fade show shadow-sm" role="alert">
    <i class="fa-solid <?= $type === 'success' ? 'fa-circle-check' : ($type === 'danger' ? 'fa-circle-exclamation' : 'fa-triangle-exclamation') ?> me-2"></i>
    <?= e($msg) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="<?= e(__('Fermer')) ?>"></button>
  </div>
  <?php endforeach; ?>
</div>
<?php endif; ?>
