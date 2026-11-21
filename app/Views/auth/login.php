<div class="auth-head">
  <h1><?= e(__('Se connecter')) ?></h1>
  <p><?= e(__('Heureux de vous revoir ! Connectez-vous à votre espace.')) ?></p>
</div>

<form action="<?= url('connexion') ?>" method="post" novalidate>
  <?= csrf_field() ?>
  <div class="mb-3">
    <label for="login" class="form-label"><?= e(__("Nom d'utilisateur ou email")) ?></label>
    <div class="input-icon">
      <i class="fa-regular fa-user"></i>
      <input type="text" name="login" id="login" class="form-control<?= invalid('login') ?>" value="<?= e(old('login')) ?>" autocomplete="username" autofocus required>
      <?= error_feedback('login') ?>
    </div>
  </div>
  <div class="mb-4">
    <label for="password" class="form-label"><?= e(__('Mot de passe')) ?></label>
    <div class="input-icon">
      <i class="fa-solid fa-lock"></i>
      <input type="password" name="password" id="password" class="form-control<?= invalid('password') ?>" autocomplete="current-password" required>
      <button type="button" class="toggle-password" data-target="password" aria-label="<?= e(__('Afficher le mot de passe')) ?>"><i class="fa-regular fa-eye"></i></button>
      <?= error_feedback('password') ?>
    </div>
  </div>
  <button type="submit" class="btn btn-ylcd w-100"><i class="fa-solid fa-right-to-bracket me-1"></i> <?= e(__('Se connecter')) ?></button>
</form>

<p class="auth-switch"><?= e(__('Pas encore de compte ?')) ?> <a href="<?= url('inscription') ?>"><?= e(__('Créer un compte')) ?></a></p>
