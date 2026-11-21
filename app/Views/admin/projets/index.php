<?php
/** Liste des projets. Variable : $pager (items avec 'service'). */
$createUrl = 'admin/projets/creer';
$createLabel = __('Nouveau projet');
$searchPlaceholder = __('Rechercher un projet...');
require ROOT . '/app/Views/partials/admin_toolbar.php';
?>
<div class="row g-4">
  <?php if (!$pager['items']): ?>
    <div class="col-12"><div class="panel"><p class="empty-state"><i class="fa-solid fa-diagram-project"></i><?= e(search_query() !== '' ? __('Aucun résultat pour cette recherche.') : __('Aucun projet pour le moment.')) ?></p></div></div>
  <?php endif; ?>
  <?php foreach ($pager['items'] as $a): ?>
  <div class="col-md-6 col-xl-3">
    <div class="content-card">
      <div class="content-card-img">
        <img src="<?= e(media_url($a['image'])) ?>" alt="">
        <?php if (tr($a, 'categorie')): ?><span class="content-card-tag"><?= e(tr($a, 'categorie')) ?></span><?php endif; ?>
        <span class="content-card-order">#<?= (int) $a['ordre'] ?></span>
      </div>
      <div class="content-card-body">
        <h6><?= e(tr($a, 'titre')) ?></h6>
        <p class="small text-muted mb-2"><?= e(excerpt(tr($a, 'description'), 100)) ?></p>
        <?php if ($a['service']): ?><div class="small mb-2"><i class="fa-solid fa-link me-1 text-ylcd"></i><?= e(tr($a['service'], 'titre')) ?></div><?php endif; ?>
        <?php require ROOT . '/app/Views/partials/translation_status.php'; ?>
      </div>
      <div class="content-card-actions">
        <a href="<?= url('admin/projets/' . $a['id'] . '/modifier') ?>" class="btn btn-sm btn-outline-ylcd"><i class="fa-regular fa-pen-to-square me-1"></i> <?= e(__('Modifier')) ?></a>
        <?php $deleteUrl = 'admin/projets/' . $a['id'] . '/supprimer'; $deleteLabel = __('Supprimer le projet « :title » ?', ['title' => tr($a, 'titre')]);
        require ROOT . '/app/Views/partials/delete_button.php'; ?>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
</div>
<?php require ROOT . '/app/Views/partials/pagination.php'; ?>
