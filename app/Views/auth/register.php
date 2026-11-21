<div class="auth-head">
  <h1><?= e(__('Créer un compte')) ?></h1>
  <p><?= e(__("Rejoignez l'espace membre de YLCD-ASBL.")) ?></p>
</div>

<form action="<?= url('inscription') ?>" method="post" enctype="multipart/form-data" novalidate>
  <?= csrf_field() ?>

  <div class="photo-picker mb-3">
    <div class="photo-preview" data-initials-from="username"><span>?</span></div>
    <div>
      <label class="btn btn-sm btn-light border mb-1" for="photo"><i class="fa-solid fa-camera me-1"></i> <?= e(__('Ajouter une photo')) ?></label>
      <input type="file" name="photo" id="photo" accept="image/*" class="d-none photo-input">
      <div class="form-text mt-0"><?= e(__('Optionnel — sinon vos initiales seront affichées.')) ?></div>
      <?php if (error('photo')): ?><div class="text-danger small"><?= e(error('photo')) ?></div><?php endif; ?>
    </div>
  </div>

  <div class="mb-3">
    <label for="username" class="form-label"><?= e(__("Nom d'utilisateur")) ?> *</label>
    <div class="input-icon">
      <i class="fa-regular fa-user"></i>
      <input type="text" name="username" id="username" class="form-control<?= invalid('username') ?>" value="<?= e(old('username')) ?>" placeholder="<?= e(__('Ex : Jean Mukendi')) ?>" autocomplete="username" required>
      <?= error_feedback('username') ?>
    </div>
  </div>
  <div class="mb-3">
    <label for="email" class="form-label"><?= e(__('Email')) ?> *</label>
    <div class="input-icon">
      <i class="fa-regular fa-envelope"></i>
      <input type="email" name="email" id="email" class="form-control<?= invalid('email') ?>" value="<?= e(old('email')) ?>" autocomplete="email" required>
      <?= error_feedback('email') ?>
    </div>
  </div>
  <div class="row g-3 mb-4">
    <div class="col-sm-6">
      <label for="password" class="form-label"><?= e(__('Mot de passe')) ?> *</label>
      <div class="input-icon">
        <i class="fa-solid fa-lock"></i>
        <input type="password" name="password" id="password" class="form-control<?= invalid('password') ?>" autocomplete="new-password" minlength="8" required>
        <button type="button" class="toggle-password" data-target="password" aria-label="<?= e(__('Afficher le mot de passe')) ?>"><i class="fa-regular fa-eye"></i></button>
        <?= error_feedback('password') ?>
      </div>
    </div>
    <div class="col-sm-6">
      <label for="password_confirm" class="form-label"><?= e(__('Confirmer')) ?> *</label>
      <div class="input-icon">
        <i class="fa-solid fa-lock"></i>
        <input type="password" name="password_confirm" id="password_confirm" class="form-control<?= invalid('password_confirm') ?>" autocomplete="new-password" required>
        <?= error_feedback('password_confirm') ?>
      </div>
    </div>
    <div class="col-12"><div class="form-text mt-0"><?= e(__('Au moins 8 caractères.')) ?></div></div>
  </div>
  <button type="submit" class="btn btn-ylcd w-100"><i class="fa-solid fa-user-plus me-1"></i> <?= e(__('Créer mon compte')) ?></button>
</form>

<p class="auth-switch"><?= e(__('Déjà inscrit ?')) ?> <a href="<?= url('connexion') ?>"><?= e(__('Se connecter')) ?></a></p>
