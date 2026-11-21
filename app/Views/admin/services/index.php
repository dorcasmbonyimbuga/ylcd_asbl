<?php
/** Liste des services. Variable : $pager. */
$createUrl = 'admin/services/creer';
$createLabel = __('Nouveau service');
$searchPlaceholder = __('Rechercher un service...');
require ROOT . '/app/Views/partials/admin_toolbar.php';
?>
<div class="panel p-0">
  <?php if (!$pager['items']): ?>
    <p class="empty-state"><i class="fa-solid fa-hand-holding-heart"></i><?= e(search_query() !== '' ? __('Aucun résultat pour cette recherche.') : __('Aucun service pour le moment.')) ?></p>
  <?php else: ?>
  <div class="table-responsive">
    <table class="table admin-table align-middle mb-0">
      <thead><tr><th>#</th><th><?= e(__('Service')) ?></th><th><?= e(__('Pôle')) ?></th><th><?= e(__('Ancre')) ?></th><th><?= e(__('Traduction')) ?></th><th class="text-end"><?= e(__('Actions')) ?></th></tr></thead>
      <tbody>
        <?php foreach ($pager['items'] as $a): ?>
        <tr>
          <td class="text-muted"><?= (int) $a['ordre'] ?></td>
          <td>
            <div class="d-flex align-items-center gap-3">
              <img src="<?= e(media_url($a['image'])) ?>" class="table-thumb" alt="">
              <div><strong><?= e(tr($a, 'titre')) ?></strong><div class="small text-muted"><?= e(tr($a, 'slogan')) ?></div></div>
            </div>
          </td>
          <td class="small"><?= e(tr($a, 'pole')) ?></td>
          <td><code>#<?= e($a['slug']) ?></code></td>
          <td><?php require ROOT . '/app/Views/partials/translation_status.php'; ?></td>
          <td class="text-end text-nowrap">
            <a href="<?= url('services#' . $a['slug']) ?>" target="_blank" class="btn btn-icon" title="<?= e(__('Voir sur le site')) ?>"><i class="fa-regular fa-eye"></i></a>
            <a href="<?= url('admin/services/' . $a['id'] . '/modifier') ?>" class="btn btn-icon" title="<?= e(__('Modifier')) ?>"><i class="fa-regular fa-pen-to-square"></i></a>
            <?php $deleteUrl = 'admin/services/' . $a['id'] . '/supprimer'; $deleteLabel = __('Supprimer le service « :title » ?', ['title' => tr($a, 'titre')]);
            require ROOT . '/app/Views/partials/delete_button.php'; ?>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php endif; ?>
</div>
<?php require ROOT . '/app/Views/partials/pagination.php'; ?>
