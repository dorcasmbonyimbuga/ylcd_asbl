<?php
/** Sélecteur de langue (FR / EN) et bouton mode clair / sombre. Utilisé sur le site, l'admin et les pages de connexion. */
$__dark = theme() === 'dark';
?>
<div class="switchers">
  <div class="lang-switch" role="group" aria-label="<?= e(__('Langue')) ?>">
    <?php foreach (\App\Core\Lang::LOCALES as $__code => $__label): ?>
    <a href="<?= e(lang_url($__code)) ?>" class="<?= locale() === $__code ? 'active' : '' ?>" title="<?= e($__label) ?>" hreflang="<?= $__code ?>"
       <?= locale() === $__code ? 'aria-current="true"' : '' ?>><?= strtoupper($__code) ?></a>
    <?php endforeach; ?>
  </div>
  <button type="button" class="theme-toggle" data-theme-toggle
          data-label-dark="<?= e(__('Activer le mode sombre')) ?>" data-label-light="<?= e(__('Activer le mode clair')) ?>"
          title="<?= e($__dark ? __('Activer le mode clair') : __('Activer le mode sombre')) ?>" aria-pressed="<?= $__dark ? 'true' : 'false' ?>">
    <i class="fa-solid <?= $__dark ? 'fa-sun' : 'fa-moon' ?>"></i>
  </button>
</div>
