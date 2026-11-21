<?php /** Première connexion de l'admin par défaut : changement obligatoire des identifiants. Variable : $user. */ ?>
<div class="auth-head">
  <span class="badge-step"><i class="fa-solid fa-shield-halved me-1"></i> <?= e(__('Étape obligatoire')) ?></span>
  <h1><?= e(__('Sécurisez votre compte')) ?></h1>
  <p><?= __('Vous utilisez les identifiants par défaut (<code>admin / admin</code>). Choisissez vos propres identifiants pour continuer.') ?></p>
</div>

<form action="<?= url('compte/configuration') ?>" method="post" enctype="multipart/form-data" novalidate>
  <?= csrf_field() ?>

  <div class="photo-picker mb-3">
    <div class="photo-preview" data-initials-from="username">
      <?php if ($user['photo']): ?><img src="<?= e(media_url($user['photo'])) ?>" alt=""><?php else: ?><span><?= e(initials($user['username'])) ?></span><?php endif; ?>
    </div>
    <div>
      <label class="btn btn-sm btn-light border mb-1" for="photo"><i class="fa-solid fa-camera me-1"></i> <?= e(__('Choisir une photo')) ?></label>
      <input type="file" name="photo" id="photo" accept="image/*" class="d-none photo-input">
      <div class="form-text mt-0"><?= e(__('Optionnel.')) ?></div>
      <?php if (error('photo')): ?><div class="text-danger small"><?= e(error('photo')) ?></div><?php endif; ?>
    </div>
  </div>

  <div class="mb-3">
    <label for="username" class="form-label"><?= e(__("Nouveau nom d'utilisateur")) ?> *</label>
    <input type="text" name="username" id="username" class="form-control<?= invalid('username') ?>" value="<?= e(old('username', '')) ?>" required>
    <?= error_feedback('username') ?>
  </div>
  <div class="mb-3">
    <label for="email" class="form-label"><?= e(__('Nouvel email')) ?> *</label>
    <input type="email" name="email" id="email" class="form-control<?= invalid('email') ?>" value="<?= e(old('email', '')) ?>" placeholder="ex : ylcdrc21@gmail.com" required>
    <?= error_feedback('email') ?>
  </div>
  <div class="row g-3 mb-4">
    <div class="col-sm-6">
      <label for="password" class="form-label"><?= e(__('Nouveau mot de passe')) ?> *</label>
      <input type="password" name="password" id="password" class="form-control<?= invalid('password') ?>" autocomplete="new-password" required>
      <?= error_feedback('password') ?>
    </div>
    <div class="col-sm-6">
      <label for="password_confirm" class="form-label"><?= e(__('Confirmer')) ?> *</label>
      <input type="password" name="password_confirm" id="password_confirm" class="form-control<?= invalid('password_confirm') ?>" autocomplete="new-password" required>
      <?= error_feedback('password_confirm') ?>
    </div>
    <div class="col-12"><div class="form-text mt-0"><?= e(__('Au moins 8 caractères, différent de « admin ».')) ?></div></div>
  </div>
  <button type="submit" class="btn btn-ylcd w-100"><i class="fa-solid fa-check me-1"></i> <?= e(__('Enregistrer et continuer')) ?></button>
</form>

<form action="<?= url('deconnexion') ?>" method="post" class="text-center mt-3">
  <?= csrf_field() ?>
  <button class="btn btn-link btn-sm text-muted"><?= e(__('Se déconnecter')) ?></button>
</form>
