<?php
/**
 * Pagination (Précédent / numéros / Suivant). Variable : $pager (résultat de Model::paginate()).
 * Conserve les autres paramètres de l'URL (recherche, catégorie...).
 */
if (empty($pager) || $pager['pages'] <= 1) {
    if (!empty($pager['total'])) {
        echo '<p class="pager-info">' . e(__('Affichage de :from à :to sur :total', ['from' => $pager['from'], 'to' => $pager['to'], 'total' => $pager['total']])) . '</p>';
    }
    return;
}
$page = $pager['page'];
$pages = $pager['pages'];

// Numéros affichés : première, dernière, et deux autour de la page courante
$numbers = [];
for ($i = 1; $i <= $pages; $i++) {
    if ($i === 1 || $i === $pages || abs($i - $page) <= 1) {
        $numbers[] = $i;
    } elseif (end($numbers) !== '…') {
        $numbers[] = '…';
    }
}
?>
<nav class="pager" aria-label="<?= e(__('Pagination')) ?>">
  <p class="pager-info"><?= e(__('Affichage de :from à :to sur :total', ['from' => $pager['from'], 'to' => $pager['to'], 'total' => $pager['total']])) ?></p>
  <ul class="pagination mb-0">
    <li class="page-item<?= $page <= 1 ? ' disabled' : '' ?>">
      <a class="page-link" href="<?= $page > 1 ? e(query_url(['page' => $page - 1])) : '#' ?>" rel="prev">
        <i class="fa-solid fa-chevron-left me-1"></i><?= e(__('Précédent')) ?>
      </a>
    </li>
    <?php foreach ($numbers as $n): ?>
      <?php if ($n === '…'): ?>
      <li class="page-item disabled"><span class="page-link">…</span></li>
      <?php else: ?>
      <li class="page-item<?= $n === $page ? ' active' : '' ?>">
        <a class="page-link" href="<?= e(query_url(['page' => $n])) ?>"<?= $n === $page ? ' aria-current="page"' : '' ?>><?= $n ?></a>
      </li>
      <?php endif; ?>
    <?php endforeach; ?>
    <li class="page-item<?= $page >= $pages ? ' disabled' : '' ?>">
      <a class="page-link" href="<?= $page < $pages ? e(query_url(['page' => $page + 1])) : '#' ?>" rel="next">
        <?= e(__('Suivant')) ?><i class="fa-solid fa-chevron-right ms-1"></i>
      </a>
    </li>
  </ul>
</nav>
