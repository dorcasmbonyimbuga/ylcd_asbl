<?php /** Layout des pages de connexion / inscription : écran partagé image + formulaire. */ ?>
<!DOCTYPE html>
<html lang="<?= locale() ?>" data-theme="<?= theme() ?>">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="color-scheme" content="light dark" />
    <title>YLCD-ASBL | <?= e($title ?? __('Se connecter')) ?></title>
    <link rel="shortcut icon" type="image/x-icon" href="<?= asset('img/logo-ylcd.jpg') ?>" />
    <link rel="stylesheet" href="<?= asset('css/bootstrap.min.css') ?>" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="<?= asset('css/admin.css') ?>" />
  </head>
  <body class="auth-body">
    <?php require ROOT . '/app/Views/partials/flash.php'; ?>
    <div class="auth-wrapper">
      <aside class="auth-visual" style="background-image: linear-gradient(160deg, rgba(31,33,80,.92), rgba(145,73,146,.82)), url('<?= asset('img/brevets-anglais-2026-1.jpg') ?>')">
        <a href="<?= url() ?>" class="auth-brand">
          <img src="<?= asset('img/logo-ylcd.jpg') ?>" alt="Logo YLCD-ASBL">
          <span>YLCD-ASBL</span>
        </a>
        <div>
          <h2><?= __('Former, accompagner,<br>agir pour demain.') ?></h2>
          <p><?= e(__("Espace réservé aux membres et à l'administration de la Youth League for Community Development.")) ?></p>
        </div>
        <small>© <?= date('Y') ?> YLCD-ASBL — Goma, <?= e(__('RDC')) ?></small>
      </aside>
      <main class="auth-panel">
        <div class="auth-topbar"><?php require ROOT . '/app/Views/partials/switchers.php'; ?></div>
        <div class="auth-card">
          <?= $content ?>
        </div>
        <a href="<?= url() ?>" class="auth-back"><i class="fa-solid fa-arrow-left me-1"></i> <?= e(__('Retour au site')) ?></a>
      </main>
    </div>
    <script src="<?= asset('js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= asset('js/theme.js') ?>"></script>
    <script src="<?= asset('js/admin.js') ?>"></script>
  </body>
</html>
