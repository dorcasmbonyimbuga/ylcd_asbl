<?php
/**
 * Champ de recherche (GET ?q=). Conserve les filtres existants (catégorie...).
 * Variable optionnelle : $searchPlaceholder.
 */
$__q = search_query();
?>
<form method="get" action="<?= e(url(current_path())) ?>" class="search-form" role="search">
  <?php if (!config('app.pretty_urls', true)): ?><input type="hidden" name="url" value="<?= e(current_path()) ?>"><?php endif; ?>
  <?php foreach ($_GET as $__k => $__v):
      if (in_array($__k, ['q', 'page', 'url', 'lang'], true) || !is_string($__v)) continue; ?>
  <input type="hidden" name="<?= e($__k) ?>" value="<?= e($__v) ?>">
  <?php endforeach; ?>
  <div class="search-box">
    <i class="fa-solid fa-magnifying-glass"></i>
    <input type="search" name="q" value="<?= e($__q) ?>" class="form-control" placeholder="<?= e($searchPlaceholder ?? __('Rechercher...')) ?>" aria-label="<?= e(__('Rechercher')) ?>">
    <?php if ($__q !== ''): ?>
    <a href="<?= e(query_url(['q' => null, 'page' => null])) ?>" class="search-clear" title="<?= e(__('Effacer la recherche')) ?>"><i class="fa-solid fa-xmark"></i></a>
    <?php endif; ?>
  </div>
  <button type="submit" class="btn btn-search"><?= e(__('Rechercher')) ?></button>
</form>
