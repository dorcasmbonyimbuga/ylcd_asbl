<?php
/** Page Services. Variable : $services. */
$bannerTitle = __("Nos services & domaines d'action");
$bannerSubtitle = __("Des solutions concrètes pour l'autonomisation et la résilience des communautés");
$bannerCrumb = __('Services');
$bannerImg = 'img/informatique-2026-1.jpg';
require ROOT . '/app/Views/partials/banner.php';

$badgeColors = ['', 'navy', '', 'green'];
?>
<section class="py-5" id="service">
  <div class="container">
    <h1 class="title1"><?= e(__('CE QUE NOUS FAISONS')) ?></h1>
    <p><?= e(__('Chez YLCD-ASBL, nous mettons notre expertise au service des communautés pour répondre à leurs besoins réels et actuels. Nos interventions combinent formation, accompagnement personnalisé, actions de terrain et sensibilisation, afin d’offrir des solutions durables et adaptées aux réalités locales.')) ?></p>
  </div>
</section>

<?php foreach ($services as $i => $s):
    $reverse = $i % 2 === 1; ?>
<section id="<?= e($s['slug']) ?>" class="py-5<?= $reverse ? ' bg-light' : '' ?>">
  <div class="container">
    <div class="row align-items-center gy-4<?= $reverse ? ' flex-lg-row-reverse' : '' ?>">
      <div class="col-lg-5">
        <img src="<?= e(media_url($s['image'])) ?>" alt="<?= e(tr($s, 'titre')) ?>" class="img-cover shadow" style="height: 380px" />
      </div>
      <div class="col-lg-7 <?= $reverse ? 'pe-lg-5' : 'ps-lg-5' ?>">
        <?php if (tr($s, 'pole')): ?>
        <span class="badge-ylcd <?= $badgeColors[$i % 4] ?> mb-2"><?= e(tr($s, 'pole')) ?></span>
        <?php endif; ?>
        <h2 class="mb-3"><?= e(tr($s, 'titre')) ?></h2>
        <p>
          <?php if (tr($s, 'slogan')): ?><strong><?= e(tr($s, 'slogan')) ?></strong> <?php endif; ?>
          <?= e(tr($s, 'description')) ?>
        </p>
        <?php if ($points = lines(tr($s, 'points'))): ?>
        <ul class="list-unstyled mb-4">
          <?php foreach ($points as $point): ?>
          <li class="mb-2"><i class="fa-solid fa-circle-check me-2 text-ylcd-green"></i><?php
            // "Anglais : texte" / "English: text" => début en gras
            if (preg_match('/^([^:]{1,60}?)\s*:\s+(.+)$/u', $point, $m)): ?><strong><?= e($m[1]) ?> :</strong> <?= e($m[2]) ?><?php else: ?><?= e($point) ?><?php endif; ?></li>
          <?php endforeach; ?>
        </ul>
        <?php endif; ?>
        <a href="<?= $s['slug'] === 'consultance' ? url('#contact') : url('activites') ?>" class="btn btn-brand">
          <?= e($s['slug'] === 'consultance' ? __('Demander un accompagnement') : __('Voir nos activités en images')) ?>
        </a>
      </div>
    </div>
  </div>
</section>
<?php endforeach; ?>

<?php
$ctaTitle = __('Vous souhaitez suivre une formation ou devenir partenaire ?');
$ctaText = __('Notre équipe reste à votre disposition pour toute question sur nos formations ou nos services.');
$ctaLabel = __("S'inscrire / Nous écrire");
require ROOT . '/app/Views/partials/cta.php';
