<?php
/** Liste des activités. Variables : $pager (items avec medias), $categorie (filtre actif). */
use App\Models\Activite;

$createUrl = 'admin/activites/creer';
$createLabel = __('Nouvelle activité');
$searchPlaceholder = __('Rechercher une activité...');
ob_start(); ?>
<div class="filter-pills">
  <a href="<?= e(query_url(['categorie' => null, 'page' => null])) ?>" class="<?= $categorie === '' ? 'active' : '' ?>"><?= e(__('Toutes')) ?></a>
  <?php foreach (Activite::CATEGORIES as $key => $label): ?>
  <a href="<?= e(query_url(['categorie' => $key, 'page' => null])) ?>" class="<?= $categorie === $key ? 'active' : '' ?>"><?= e(__($label)) ?></a>
  <?php endforeach; ?>
</div>
<?php $toolbarExtra = ob_get_clean();
require ROOT . '/app/Views/partials/admin_toolbar.php';
?>

<div class="panel p-0">
  <?php if (!$pager['items']): ?>
    <p class="empty-state"><i class="fa-solid fa-camera-retro"></i><?= e(search_query() !== '' ? __('Aucun résultat pour cette recherche.') : __('Aucune activité. Ajoutez la première !')) ?></p>
  <?php else: ?>
  <div class="table-responsive">
    <table class="table admin-table align-middle mb-0">
      <thead>
        <tr><th><?= e(__('Activité')) ?></th><th><?= e(__('Catégorie')) ?></th><th><?= e(__('Date')) ?></th><th class="text-center"><?= e(__('Médias')) ?></th><th><?= e(__('Traduction')) ?></th><th><?= e(__('Statut')) ?></th><th class="text-end"><?= e(__('Actions')) ?></th></tr>
      </thead>
      <tbody>
        <?php foreach ($pager['items'] as $a):
            $thumb = null;
            foreach ($a['medias'] as $m) { if ($m['type'] === 'image') { $thumb = $m; break; } } ?>
        <tr>
          <td>
            <div class="d-flex align-items-center gap-3">
              <?php if ($thumb): ?>
                <img src="<?= e(media_url($thumb['chemin'])) ?>" class="table-thumb" alt="">
              <?php else: ?>
                <span class="table-thumb placeholder-thumb"><i class="fa-solid <?= $a['medias'] ? 'fa-video' : 'fa-image' ?>"></i></span>
              <?php endif; ?>
              <div>
                <strong><?= e(tr($a, 'titre')) ?></strong>
                <?php if (tr($a, 'sous_titre')): ?><div class="small text-muted"><?= e(excerpt(tr($a, 'sous_titre'), 70)) ?></div><?php endif; ?>
              </div>
            </div>
          </td>
          <td><span class="cat-badge cat-<?= e($a['categorie']) ?>"><?= e(Activite::categorieLabel($a['categorie'])) ?></span></td>
          <td class="text-nowrap"><?= $a['date_activite'] ? e(date_activite($a['date_activite'])) : '<span class="text-muted">' . e(__('Régulière')) . '</span>' ?></td>
          <td class="text-center"><?= count($a['medias']) ?></td>
          <td><?php require ROOT . '/app/Views/partials/translation_status.php'; ?></td>
          <td><?= $a['est_publie'] ? '<span class="status status-on">' . e(__('Publiée')) . '</span>' : '<span class="status status-off">' . e(__('Brouillon')) . '</span>' ?></td>
          <td class="text-end text-nowrap">
            <a href="<?= url('admin/activites/' . $a['id'] . '/modifier') ?>" class="btn btn-icon" title="<?= e(__('Modifier')) ?>"><i class="fa-regular fa-pen-to-square"></i></a>
            <?php $deleteUrl = 'admin/activites/' . $a['id'] . '/supprimer'; $deleteLabel = __('Supprimer « :title » et tous ses médias ?', ['title' => tr($a, 'titre')]);
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
