<?php
/**
 * Layout de l'espace connecté. Le menu latéral s'adapte au rôle :
 * un membre ne voit que son profil, un admin voit toute la gestion du site.
 */
$__user = auth();
$__isAdmin = $__user['role'] === 'admin';
$__unread = $__isAdmin ? \App\Models\Contact::nonLus() : 0;

$__menu = $__isAdmin ? [
    'Général' => [
        ['admin', 'fa-gauge-high', 'Tableau de bord', false],
    ],
    'Contenu du site' => [
        ['admin/activites', 'fa-camera-retro', 'Activités', true],
        ['admin/projets', 'fa-diagram-project', 'Projets', true],
        ['admin/services', 'fa-hand-holding-heart', 'Services', true],
        ['admin/membres', 'fa-people-group', "Membres de l'équipe", true],
    ],
    'Administration' => [
        ['admin/messages', 'fa-envelope', 'Messages', true],
        ['admin/utilisateurs', 'fa-user-shield', 'Utilisateurs', true],
    ],
    'Mon compte' => [
        ['profil', 'fa-id-badge', 'Mon profil', false],
    ],
] : [
    'Mon compte' => [
        ['profil', 'fa-id-badge', 'Mon profil', false],
    ],
];
?>
<!DOCTYPE html>
<html lang="<?= locale() ?>" data-theme="<?= theme() ?>">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="color-scheme" content="light dark" />
    <title><?= e($title ?? __('Espace')) ?> | YLCD-ASBL</title>
    <link rel="shortcut icon" type="image/x-icon" href="<?= asset('img/logo-ylcd.jpg') ?>" />
    <link rel="stylesheet" href="<?= asset('css/bootstrap.min.css') ?>" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="<?= asset('css/admin.css') ?>" />
  </head>
  <body class="admin-body">
    <?php require ROOT . '/app/Views/partials/flash.php'; ?>

    <!-- Barre latérale -->
    <aside class="sidebar" id="sidebar">
      <a href="<?= url($__isAdmin ? 'admin' : 'profil') ?>" class="sidebar-brand">
        <img src="<?= asset('img/logo-ylcd.jpg') ?>" alt="Logo">
        <div><strong>YLCD-ASBL</strong><small><?= e($__isAdmin ? __('Administration') : __('Espace membre')) ?></small></div>
      </a>
      <nav class="sidebar-nav">
        <?php foreach ($__menu as $section => $items): ?>
          <div class="sidebar-section"><?= e(__($section)) ?></div>
          <?php foreach ($items as [$path, $icon, $label, $prefix]): ?>
          <a href="<?= url($path) ?>" class="sidebar-link <?= is_active($path, $prefix) ? 'active' : '' ?>">
            <i class="fa-solid <?= $icon ?>"></i><span><?= e(__($label)) ?></span>
            <?php if ($path === 'admin/messages' && $__unread): ?><span class="badge rounded-pill ms-auto"><?= $__unread ?></span><?php endif; ?>
          </a>
          <?php endforeach; ?>
        <?php endforeach; ?>
      </nav>
      <div class="sidebar-footer">
        <a href="<?= url() ?>" class="sidebar-link"><i class="fa-solid fa-globe"></i><span><?= e(__('Voir le site')) ?></span></a>
      </div>
    </aside>
    <div class="sidebar-backdrop" data-sidebar-close></div>

    <!-- Contenu -->
    <div class="admin-main">
      <header class="admin-topbar">
        <button class="btn btn-icon d-lg-none" data-sidebar-toggle aria-label="<?= e(__('Ouvrir le menu')) ?>"><i class="fa-solid fa-bars"></i></button>
        <h1 class="admin-title"><?= e($title ?? '') ?></h1>
        <div class="ms-auto d-flex align-items-center gap-2">
          <?php require ROOT . '/app/Views/partials/switchers.php'; ?>
          <a href="<?= url() ?>" class="btn btn-sm btn-light border d-none d-md-inline-flex align-items-center" target="_blank">
            <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> <?= e(__('Site')) ?>
          </a>
          <div class="dropdown">
            <button class="btn user-chip dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
              <?= avatar($__user, 34) ?>
              <span class="d-none d-md-inline text-start">
                <strong><?= e($__user['username']) ?></strong>
                <small><?= e(__(\App\Models\User::ROLES[$__user['role']])) ?></small>
              </span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow border-0">
              <li><a class="dropdown-item" href="<?= url('profil') ?>"><i class="fa-solid fa-id-badge me-2"></i><?= e(__('Mon profil')) ?></a></li>
              <li><hr class="dropdown-divider"></li>
              <li>
                <form action="<?= url('deconnexion') ?>" method="post">
                  <?= csrf_field() ?>
                  <button class="dropdown-item text-danger"><i class="fa-solid fa-right-from-bracket me-2"></i><?= e(__('Se déconnecter')) ?></button>
                </form>
              </li>
            </ul>
          </div>
        </div>
      </header>

      <main class="admin-content">
        <?= $content ?>
      </main>
    </div>

    <script src="<?= asset('js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= asset('js/theme.js') ?>"></script>
    <script src="<?= asset('js/admin.js') ?>"></script>
  </body>
</html>
