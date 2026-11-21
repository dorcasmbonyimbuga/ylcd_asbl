<?php
/** Layout du site public. Variables : $title, $content, $withLightbox (optionnel). */
?>
<!DOCTYPE html>
<html lang="<?= locale() ?>" data-theme="<?= theme() ?>">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="color-scheme" content="light dark" />
    <title>YLCD-ASBL | <?= e($title ?? __('Accueil')) ?></title>
    <link rel="shortcut icon" type="image/x-icon" href="<?= asset('img/logo-ylcd.jpg') ?>" />
    <link rel="stylesheet" href="<?= asset('css/bootstrap.min.css') ?>" />
    <link rel="stylesheet" href="<?= asset('css/owl.carousel.min.css') ?>" />
    <link rel="stylesheet" href="<?= asset('css/owl.theme.default.min.css') ?>" />
    <?php if (!empty($withLightbox)): ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css" />
    <?php endif; ?>
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@700&family=Lato:wght@400&display=swap" rel="stylesheet" />
  </head>

  <body>
    <?php require ROOT . '/app/Views/partials/site_header.php'; ?>

    <?php require ROOT . '/app/Views/partials/flash.php'; ?>

    <?= $content ?>

    <?php require ROOT . '/app/Views/partials/site_footer.php'; ?>

    <script src="<?= asset('js/jquery.min.js') ?>"></script>
    <script src="<?= asset('js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= asset('js/owl.carousel.min.js') ?>"></script>
    <?php if (!empty($withLightbox)): ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js"></script>
    <script>lightbox.option({ albumLabel: <?= json_encode(__('Image %1 sur %2')) ?> });</script>
    <?php endif; ?>
    <script src="<?= asset('js/theme.js') ?>"></script>
    <script src="<?= asset('js/app.js') ?>"></script>
  </body>
</html>
