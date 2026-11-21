<?php /** Pied de page commun au site public. */ ?>
<footer>
  <div class="footer-top">
    <div class="container">
      <div class="row gy-4">
        <div class="col-lg-3 mx-auto">
          <a href="<?= url('a-propos') ?>" class="navbar-brand logo"><?= e(__('A PROPOS DE NOUS')) ?><span>.</span></a>
          <p><?= e(__('YLCD-ASBL (Youth League for Community Development) est une association sans but lucratif dédiée à l’autonomisation des communautés. À travers la formation, l’accompagnement et des actions concrètes, nous travaillons à bâtir un avenir plus solidaire, équitable et durable pour tous.')) ?></p>
        </div>
        <div class="col-lg-3 mx-auto">
          <a href="<?= url('a-propos#mission') ?>" class="navbar-brand logo"><?= e(__('NOTRE MISSION')) ?><span>.</span></a>
          <p><?= e(__('Notre mission est simple mais puissante : former, accompagner et agir pour demain. Nous voulons donner aux individus et aux institutions les outils, les connaissances et le soutien nécessaires pour relever les défis actuels et construire un futur meilleur.')) ?></p>
        </div>
        <div class="col-lg-3 mx-auto">
          <a href="<?= url('services') ?>" class="navbar-brand logo"><?= e(__('NOS SERVICES')) ?><span>.</span></a>
          <ul class="list-unstyled footer-links mt-2">
            <li><a href="<?= url('services#langues') ?>"><i class="fa-solid fa-angle-right me-2"></i><?= e(__('Formation en langues et métiers')) ?></a></li>
            <li><a href="<?= url('services#consultance') ?>"><i class="fa-solid fa-angle-right me-2"></i><?= e(__('Accompagnement, audit & consultance')) ?></a></li>
            <li><a href="<?= url('services#sante') ?>"><i class="fa-solid fa-angle-right me-2"></i><?= e(__('Santé communautaire & humanitaire')) ?></a></li>
            <li><a href="<?= url('services#environnement') ?>"><i class="fa-solid fa-angle-right me-2"></i><?= e(__('Environnement & climat')) ?></a></li>
            <li><a href="<?= url('activites') ?>"><i class="fa-solid fa-angle-right me-2"></i><?= e(__('Nos activités')) ?></a></li>
          </ul>
        </div>
        <div class="col-lg-3 mx-auto">
          <a href="<?= url('#contact') ?>" class="navbar-brand logo"><?= e(__('NOS CONTACTS')) ?><span>.</span></a>
          <p><i class="fa-solid fa-phone-volume"></i>
            <span>+243 970 747 731 | +243 975 355 503 <br> +243 999 118 125 | +243 994 769 416</span></p>
          <p><i class="fa-solid fa-envelope"></i> <span>ylcdrc21@gmail.com</span></p>
          <p><i class="fa-solid fa-location-dot"></i> <span>Youth League for Community Development, <?= e(__('RDC')) ?>, NK, Goma</span></p>
          <div class="social-links">
            <a href="#" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
            <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
            <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
            <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin"></i></a>
            <a href="#" aria-label="TikTok"><i class="fab fa-tiktok"></i></a>
            <a href="#" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="footer-bottom">
    <div class="container">
      <p class="mb-0 text-center">
        Copyrights © <span id="year"></span> <?= e(__('Tous droits réservés')) ?> - YLCD - <?= e(__('Réalisé par')) ?> Dorcas Mbonyimbuga
      </p>
    </div>
  </div>
</footer>
