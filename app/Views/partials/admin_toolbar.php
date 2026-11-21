<?php
/**
 * Barre d'outils des listes admin : recherche + bouton d'ajout.
 * Variables : $createUrl (optionnel), $createLabel (traduit), $searchPlaceholder (traduit), $toolbarExtra (HTML optionnel).
 */
?>
<div class="toolbar">
  <div class="toolbar-left">
    <?php require ROOT . '/app/Views/partials/search_form.php'; ?>
    <?= $toolbarExtra ?? '' ?>
  </div>
  <?php if (!empty($createUrl)): ?>
  <a href="<?= url($createUrl) ?>" class="btn btn-ylcd"><i class="fa-solid fa-plus me-1"></i> <?= e($createLabel) ?></a>
  <?php endif; ?>
</div>
<?php if (search_query() !== ''): ?>
<p class="search-summary">
  <?= e(__(':count résultat(s)', ['count' => $pager['total']])) ?> — « <?= e(search_query()) ?> »
  <a href="<?= e(query_url(['q' => null, 'page' => null])) ?>" class="ms-2"><?= e(__('Effacer la recherche')) ?></a>
</p>
<?php endif; ?>
