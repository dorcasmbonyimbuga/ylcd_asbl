<?php
/** Liste des membres de l'équipe. Variable : $pager. */
$createUrl = 'admin/membres/creer';
$createLabel = __('Nouveau membre');
$searchPlaceholder = __('Rechercher un membre...');
require ROOT . '/app/Views/partials/admin_toolbar.php';
?>
<p class="small text-muted"><?= e(__("Membres affichés sur la page Équipe et sur l'accueil. Ce ne sont pas des comptes de connexion.")) ?></p>
<div class="row g-4">
  <?php if (!$pager['items']): ?>
    <div class="col-12"><div class="panel"><p class="empty-state"><i class="fa-solid fa-people-group"></i><?= e(search_query() !== '' ? __('Aucun résultat pour cette recherche.') : __('Aucun membre pour le moment.')) ?></p></div></div>
  <?php endif; ?>
  <?php foreach ($pager['items'] as $a): ?>
  <div class="col-sm-6 col-lg-4 col-xl-3">
    <div class="member-card">
      <?php if ($a['est_coordinateur']): ?><span class="member-flag"><i class="fa-solid fa-star me-1"></i><?= e(__('Coordination')) ?></span><?php endif; ?>
      <img src="<?= e(media_url($a['photo'])) ?>" alt="" class="member-photo">
      <h6 class="mb-0"><?= e($a['nom']) ?></h6>
      <div class="small text-ylcd fw-semibold"><?= e(tr($a, 'fonction')) ?></div>
      <div class="small text-muted mt-1"><?= e(__('Ordre : :n', ['n' => (int) $a['ordre']])) ?></div>
      <div class="content-card-actions justify-content-center">
        <a href="<?= url('admin/membres/' . $a['id'] . '/modifier') ?>" class="btn btn-sm btn-outline-ylcd"><i class="fa-regular fa-pen-to-square me-1"></i> <?= e(__('Modifier')) ?></a>
        <?php $deleteUrl = 'admin/membres/' . $a['id'] . '/supprimer'; $deleteLabel = __('Supprimer :name de l’équipe ?', ['name' => $a['nom']]);
        require ROOT . '/app/Views/partials/delete_button.php'; ?>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
</div>
<?php require ROOT . '/app/Views/partials/pagination.php'; ?>
