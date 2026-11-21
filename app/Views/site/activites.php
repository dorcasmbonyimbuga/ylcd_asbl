<?php
/**
 * Page Activités. Variables : $pager (frise paginée), $regulieres, $categorie, $search.
 */
use App\Models\Activite;

$bannerTitle = __('Nos activités');
$bannerSubtitle = __("Retour en images sur nos actions sur le terrain, de :year à aujourd'hui", ['year' => (int) config('site.annee_creation')]);
$bannerCrumb = __('Activités');
$bannerImg = 'img/environnement-2023-2.jpg';
require ROOT . '/app/Views/partials/banner.php';

/** Affiche un média (image cliquable en lightbox ou vidéo). */
$renderMedia = function (array $m, string $group, string $title, string $class = '') {
    $src = e(media_url($m['chemin']));
    if ($m['type'] === 'video') {
        return '<video src="' . $src . '" controls preload="metadata" class="w-100 ' . $class . '" style="object-fit:cover;background:#000"></video>';
    }
    return '<a href="' . $src . '" data-lightbox="' . e($group) . '" data-title="' . e($title) . '">'
        . '<img src="' . $src . '" alt="' . e($title) . '" class="' . $class . '" loading="lazy" /></a>';
};
$filtered = $categorie !== '' || $search !== '';
?>
<section class="py-5">
  <div class="container">
    <div class="row align-items-end gy-3 mb-4">
      <div class="col-lg-6">
        <h1 class="title1"><?= e(__("DE LA FORMATION À L'ACTION")) ?></h1>
        <p class="mb-0"><?= e(__('Depuis :year, YLCD-ASBL multiplie les initiatives au service des jeunes et des communautés de Goma : formations, cérémonies de remise de certificats, actions humanitaires, encadrement des enfants et protection de l’environnement. Cliquez sur une photo pour l’agrandir.', ['year' => (int) config('site.annee_creation')])) ?></p>
      </div>
      <div class="col-lg-6">
        <?php $searchPlaceholder = __('Rechercher une activité...');
        require ROOT . '/app/Views/partials/search_form.php'; ?>
      </div>
    </div>

    <div class="activity-filters mb-4">
      <a href="<?= e(query_url(['categorie' => null, 'page' => null])) ?>" class="btn<?= $categorie === '' ? ' active' : '' ?>"><?= e(__('Toutes')) ?></a>
      <?php foreach (Activite::CATEGORIES as $key => $label): ?>
      <a href="<?= e(query_url(['categorie' => $key, 'page' => null])) ?>" class="btn<?= $categorie === $key ? ' active' : '' ?>"><?= e(__($label)) ?></a>
      <?php endforeach; ?>
    </div>

    <?php if ($filtered): ?>
    <p class="search-summary">
      <?= e(__(':count résultat(s)', ['count' => $pager['total'] + count($regulieres)])) ?>
      <?php if ($search !== ''): ?> — « <?= e($search) ?> »<?php endif; ?>
      <a href="<?= url('activites') ?>" class="ms-2"><?= e(__('Réinitialiser')) ?></a>
    </p>
    <?php endif; ?>

    <?php if (!$pager['items'] && !$regulieres): ?>
      <div class="empty-public"><i class="fa-regular fa-folder-open"></i><?= e(__('Aucune activité ne correspond à votre recherche.')) ?></div>
    <?php endif; ?>

    <div class="timeline">
      <?php foreach ($pager['items'] as $a):
          $group = 'act-' . $a['id'];
          $title = tr($a, 'titre');
          $main = $a['medias'][0] ?? null;
          $others = array_slice($a['medias'], 1); ?>
      <div class="timeline-item">
        <div class="ylcd-card">
          <div class="row g-0">
            <div class="col-md-5">
              <?= $main ? $renderMedia($main, $group, $title, 'activity-main-img') : '<img src="' . asset('img/logo-ylcd.jpg') . '" class="activity-main-img" alt="">' ?>
            </div>
            <div class="col-md-7 p-4">
              <span class="timeline-date"><i class="fa-regular fa-calendar me-1"></i> <?= e(date_activite($a['date_activite'])) ?></span>
              <span class="badge-cat ms-2"><?= e(Activite::categorieLabel($a['categorie'])) ?></span>
              <h4 class="mt-1"><?= e($title) ?></h4>
              <p><?= nl2br(e(tr($a, 'description'))) ?></p>
              <?php if ($others): ?>
              <div class="row g-2 activity-gallery mt-1">
                <?php foreach ($others as $m): ?>
                <div class="col-4"><?= $renderMedia($m, $group, $title) ?></div>
                <?php endforeach; ?>
              </div>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

    <?php require ROOT . '/app/Views/partials/pagination.php'; ?>
  </div>
</section>

<?php if ($regulieres): ?>
<section class="py-5 bg-light">
  <div class="container">
    <div class="text-center mb-5">
      <h1 class="title1"><?= e(__('NOS ACTIVITÉS RÉGULIÈRES')) ?></h1>
      <p class="mt-2"><?= e(__('Des programmes que nous organisons tout au long de l’année.')) ?></p>
    </div>
    <div class="row g-4">
      <?php foreach ($regulieres as $a):
          $group = 'act-' . $a['id'];
          $title = tr($a, 'titre');
          $main = $a['medias'][0] ?? null; ?>
      <div class="col-md-6 col-lg-4">
        <div class="ylcd-card">
          <?= $main ? $renderMedia($main, $group, $title, 'card-cover') : '' ?>
          <?php foreach (array_slice($a['medias'], 1) as $m):
              if ($m['type'] === 'image'): ?>
            <a href="<?= e(media_url($m['chemin'])) ?>" data-lightbox="<?= e($group) ?>" data-title="<?= e($title) ?>" class="d-none"></a>
          <?php endif; endforeach; ?>
          <div class="p-4">
            <h5><?= e($title) ?></h5>
            <p class="small"><?= nl2br(e(tr($a, 'description'))) ?></p>
            <?php if ($main && $main['type'] === 'image'): ?>
            <a href="#" onclick="this.closest('.ylcd-card').querySelector('[data-lightbox]').click(); return false;" class="voir-plus small fw-bold">
              <?= e(__('Voir les photos (:count)', ['count' => count($a['medias'])])) ?> <i class="fa-solid fa-arrow-right ms-1"></i>
            </a>
            <?php endif; ?>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?php
$ctaTitle = __('Vous souhaitez participer à nos prochaines activités ?');
$ctaText = __('Inscrivez-vous à une formation, devenez bénévole ou soutenez nos actions sur le terrain.');
require ROOT . '/app/Views/partials/cta.php';
