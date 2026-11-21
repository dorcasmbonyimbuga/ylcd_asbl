<?php
/** Bandeau d'appel à l'action. Variables (déjà traduites) : $ctaTitle, $ctaText, $ctaLabel (optionnel), $ctaHref (optionnel). */
?>
<section class="cta-band">
  <div class="container">
    <div class="row align-items-center gy-3">
      <div class="col-lg-9">
        <h3 class="mb-1"><?= e($ctaTitle) ?></h3>
        <p class="mb-0"><?= e($ctaText) ?></p>
      </div>
      <div class="col-lg-3 text-lg-end">
        <a href="<?= e($ctaHref ?? url('#contact')) ?>" class="btn btn-light-ylcd"><?= e($ctaLabel ?? __('Contactez-nous')) ?></a>
      </div>
    </div>
  </div>
</section>
