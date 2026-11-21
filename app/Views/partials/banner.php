<?php
/** Bannière des pages internes. Variables (déjà traduites) : $bannerTitle, $bannerSubtitle, $bannerCrumb, $bannerImg (chemin dans assets/). */
?>
<div class="page-banner" style="background-image: linear-gradient(rgba(31, 33, 80, 0.88), rgba(145, 73, 146, 0.78)), url('<?= e(asset($bannerImg)) ?>')">
  <div class="container">
    <h1><?= e($bannerTitle) ?></h1>
    <p class="lead mb-0"><?= e($bannerSubtitle) ?></p>
    <div class="breadcrumb-custom">
      <a href="<?= url() ?>"><?= e(__('Accueil')) ?></a><span>/</span><span><?= e($bannerCrumb) ?></span>
    </div>
  </div>
</div>
