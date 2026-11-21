<?php
/** Page Équipe. Variables : $coordinateur (ou null), $membres. */
$bannerTitle = __('Notre équipe');
$bannerSubtitle = __('Des femmes et des hommes engagés pour le développement communautaire');
$bannerCrumb = __('Equipe');
$bannerImg = 'img/certificats-2.jpg';
require ROOT . '/app/Views/partials/banner.php';
?>
<?php if ($coordinateur): ?>
<!------------------ MOT DE LA COORDINATION -------------------->
<section class="py-5" id="equipe">
  <div class="container">
    <div class="ylcd-card p-4 p-lg-5">
      <div class="row align-items-center gy-4">
        <div class="col-lg-4 text-center">
          <img src="<?= e(media_url($coordinateur['photo'])) ?>" alt="<?= e($coordinateur['nom']) ?>" class="coordinator-photo shadow" />
          <h5 class="mt-3 mb-0"><?= e($coordinateur['nom']) ?></h5>
          <small class="fw-bold text-ylcd-purple"><?= e(tr($coordinateur, 'fonction')) ?></small>
        </div>
        <div class="col-lg-8">
          <h1 class="title1"><?= e(__('MOT DE LA COORDINATION')) ?></h1>
          <blockquote class="blockquote mt-3">
            <p class="fst-italic">
              <?= tr($coordinateur, 'bio')
                  ? nl2br(e(tr($coordinateur, 'bio')))
                  : e(__('« Le développement durable de notre région repose avant tout sur le potentiel et la détermination de notre jeunesse. Chez YLCD-ASBL, chaque formation et chaque action menée est une graine plantée pour l’avenir. »')) ?>
            </p>
          </blockquote>
          <p class="mb-0"><?= e(__('Notre équipe de :count membres réunit des enseignants, des formateurs, des professionnels et des jeunes leaders communautaires. Ensemble, ils veillent à la qualité, à l’impact et à la transparence de chaque programme mené sur le terrain.', ['count' => (int) config('site.nombre_membres')])) ?></p>
        </div>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>

<!------------------ MEMBRES -------------------->
<section class="py-5 bg-light">
  <div class="container">
    <div class="text-center mb-5">
      <h1 class="title1"><?= e(__('LES MEMBRES')) ?></h1>
      <p class="mt-2"><?= e(__('Les visages derrière nos formations et nos actions à Goma.')) ?></p>
    </div>
    <div class="row g-4 justify-content-center">
      <?php foreach ($membres as $m): ?>
      <div class="col-sm-6 col-md-4 col-xl-2">
        <div class="team-card h-100">
          <div class="team-img-box">
            <img src="<?= e(media_url($m['photo'])) ?>" alt="<?= e($m['nom']) ?>" loading="lazy" />
            <?php if ($m['linkedin'] || $m['email']): ?>
            <div class="team-social-bar">
              <?php if ($m['linkedin']): ?><a href="<?= e($m['linkedin']) ?>" target="_blank" rel="noopener" aria-label="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a><?php endif; ?>
              <?php if ($m['email']): ?><a href="mailto:<?= e($m['email']) ?>" aria-label="Email"><i class="fa-solid fa-envelope"></i></a><?php endif; ?>
            </div>
            <?php endif; ?>
          </div>
          <div class="p-3">
            <h6 class="mb-1"><?= e($m['nom']) ?></h6>
            <span class="d-block small fw-bold text-ylcd-purple"><?= e(tr($m, 'fonction')) ?></span>
            <?php if (tr($m, 'bio')): ?><p class="small text-muted mt-2 mb-0"><?= e(excerpt(tr($m, 'bio'), 100)) ?></p><?php endif; ?>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!------------------ EN ACTION -------------------->
<section class="py-5">
  <div class="container">
    <div class="text-center mb-5"><h1 class="title1"><?= e(__("L'ÉQUIPE EN ACTION")) ?></h1></div>
    <div class="row g-4">
      <?php foreach (['presentation-anglais.jpg' => "Animation d'une présentation publique",
                      'environnement-2023-1.jpg' => 'Sur le terrain pour le reboisement',
                      'informatique-2026-2.jpg' => 'Accompagnement pendant les formations'] as $img => $label): ?>
      <div class="col-md-4">
        <div class="media-card">
          <img src="<?= asset('img/' . $img) ?>" alt="<?= e(__($label)) ?>" />
          <div class="media-overlay"><h6 class="mb-0"><?= e(__($label)) ?></h6></div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php
$ctaTitle = __('Rejoignez notre équipe de bénévoles');
$ctaText = __('Vous avez des compétences à partager et l’envie d’agir pour votre communauté ? Écrivez-nous.');
$ctaLabel = __('Devenir bénévole');
require ROOT . '/app/Views/partials/cta.php';
