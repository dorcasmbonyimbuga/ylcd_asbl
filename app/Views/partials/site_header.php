<?php
/** En-tête commun à toutes les pages publiques : coordonnées + menu principal + langue / thème. */
$__user = auth();
$__menu = [
    ''          => 'Accueil',
    'a-propos'  => 'A propos',
    'services'  => 'Services',
    '#projets'  => 'Projets',
    'activites' => 'Activités',
    'equipe'    => 'Equipe',
];
?>
<nav class="top-nav" id="home">
  <div class="container">
    <div class="row justify-content-between">
      <div class="col-auto d-none d-md-block">
        <p><i class="fa-solid fa-location-dot"></i> <span>Goma, Nord-Kivu, <?= e(__('RDC')) ?></span></p>
      </div>
      <div class="col-auto ms-auto">
        <p><i class="fa-solid fa-envelope"></i> <span>ylcdrc21@gmail.com</span></p>
        <p><i class="fa-solid fa-phone-volume"></i> <span>+243 970 747 731 | +243 975 355 503</span></p>
      </div>
    </div>
  </div>
</nav>

<nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top">
  <div class="container">
    <a href="<?= url() ?>"><img src="<?= asset('img/logo-ylcd.jpg') ?>" alt="logo YLCD" class="logo" /></a>
    <button class="navbar-toggler btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="<?= e(__('Ouvrir le menu')) ?>">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 30 30" width="30" height="30" focusable="false">
        <title>Menu</title>
        <path stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-miterlimit="10" d="M4 7h22M4 15h22M4 23h22"></path>
      </svg>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto align-items-lg-center">
        <?php foreach ($__menu as $path => $label):
            $active = str_starts_with($path, '#') ? '' : is_active($path); ?>
        <li class="navbar-item">
          <a href="<?= url($path) ?>" class="nav-link <?= $active ?>"<?= $active ? ' aria-current="page"' : '' ?>><?= e(__($label)) ?></a>
        </li>
        <?php endforeach; ?>
        <li class="navbar-item">
          <a href="<?= url('#contact') ?>" class="btn btn-brand ms-lg-3"><?= e(__('Contact')) ?></a>
        </li>

        <?php if ($__user): ?>
        <li class="navbar-item dropdown ms-lg-2 mt-2 mt-lg-0">
          <a href="#" class="nav-link dropdown-toggle d-flex align-items-center gap-2" data-bs-toggle="dropdown" aria-expanded="false">
            <?= avatar($__user, 34) ?>
            <span class="d-lg-none d-xl-inline"><?= e($__user['username']) ?></span>
          </a>
          <ul class="dropdown-menu dropdown-menu-end shadow border-0">
            <li><span class="dropdown-item-text small text-muted"><?= e(__(\App\Models\User::ROLES[$__user['role']] ?? '')) ?></span></li>
            <?php if ($__user['role'] === 'admin'): ?>
            <li><a class="dropdown-item" href="<?= url('admin') ?>"><i class="fa-solid fa-gauge me-2"></i><?= e(__('Administration')) ?></a></li>
            <?php endif; ?>
            <li><a class="dropdown-item" href="<?= url('profil') ?>"><i class="fa-solid fa-user me-2"></i><?= e(__('Mon profil')) ?></a></li>
            <li><hr class="dropdown-divider"></li>
            <li>
              <form action="<?= url('deconnexion') ?>" method="post">
                <?= csrf_field() ?>
                <button class="dropdown-item text-danger"><i class="fa-solid fa-right-from-bracket me-2"></i><?= e(__('Se déconnecter')) ?></button>
              </form>
            </li>
          </ul>
        </li>
        <?php else: ?>
        <li class="navbar-item">
          <a href="<?= url('connexion') ?>" class="btn btn-outline-brand ms-lg-2 mt-2 mt-lg-0">
            <i class="fa-solid fa-right-to-bracket me-1"></i> <?= e(__('Se connecter')) ?>
          </a>
        </li>
        <?php endif; ?>
        <li class="navbar-item ms-lg-2 mt-2 mt-lg-0">
          <?php require ROOT . '/app/Views/partials/switchers.php'; ?>
        </li>
      </ul>
    </div>
  </div>
</nav>
