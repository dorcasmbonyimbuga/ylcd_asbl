<?php /** Profil de l'utilisateur connecté. Variable : $user. */ ?>
<div class="row g-4">
  <!-- Carte d'identité -->
  <div class="col-lg-4">
    <div class="panel text-center profile-card">
      <div class="profile-cover"></div>
      <div class="profile-avatar"><?= avatar($user, 110) ?></div>
      <h4 class="mb-0 mt-2"><?= e($user['username']) ?></h4>
      <p class="text-muted mb-2"><?= e($user['email']) ?></p>
      <span class="role-badge role-<?= e($user['role']) ?>"><?= e(__(\App\Models\User::ROLES[$user['role']])) ?></span>
      <ul class="profile-meta">
        <li><i class="fa-regular fa-calendar"></i> <?= e(__('Membre depuis le :date', ['date' => date_long($user['created_at'], false)])) ?></li>
        <?php if ($user['last_login_at']): ?>
        <li><i class="fa-regular fa-clock"></i> <?= e(__('Dernière connexion : :date', ['date' => date_long($user['last_login_at'])])) ?></li>
        <?php endif; ?>
      </ul>
      <?php if ($user['photo']): ?>
      <form action="<?= url('profil/photo/supprimer') ?>" method="post" data-confirm="<?= e(__('Supprimer votre photo ? Vos initiales seront affichées à la place.')) ?>">
        <?= csrf_field() ?>
        <button class="btn btn-sm btn-outline-danger"><i class="fa-regular fa-trash-can me-1"></i> <?= e(__('Retirer la photo')) ?></button>
      </form>
      <?php endif; ?>
    </div>
    <?php if ($user['role'] !== 'admin'): ?>
    <div class="panel mt-4 small text-muted">
      <i class="fa-solid fa-circle-info me-1 text-ylcd"></i>
      <?= __('Votre compte est de type <strong>membre</strong>. D’autres fonctionnalités pourront vous être attribuées par l’administration de YLCD-ASBL.') ?>
    </div>
    <?php endif; ?>
  </div>

  <div class="col-lg-8">
    <!-- Informations -->
    <div class="panel mb-4">
      <h5 class="panel-title"><i class="fa-regular fa-user"></i> <?= e(__('Informations du compte')) ?></h5>
      <form action="<?= url('profil') ?>" method="post" enctype="multipart/form-data" novalidate>
        <?= csrf_field() ?>
        <div class="row g-3">
          <div class="col-md-6"><?= form_field('username', __("Nom d'utilisateur"), $user['username'], ['required' => true]) ?></div>
          <div class="col-md-6"><?= form_field('email', __('Email'), $user['email'], ['type' => 'email', 'required' => true]) ?></div>
          <div class="col-12">
            <label class="form-label" for="photo"><?= e($user['photo'] ? __('Changer la photo') : __('Ajouter une photo')) ?> <span class="text-muted">(<?= e(__('optionnel')) ?>)</span></label>
            <input type="file" name="photo" id="photo" accept="image/*" class="form-control<?= invalid('photo') ?>">
            <?= error_feedback('photo') ?>
          </div>
        </div>
        <div class="text-end mt-3">
          <button class="btn btn-ylcd"><i class="fa-solid fa-floppy-disk me-1"></i> <?= e(__('Enregistrer')) ?></button>
        </div>
      </form>
    </div>

    <!-- Mot de passe -->
    <div class="panel" id="securite">
      <h5 class="panel-title"><i class="fa-solid fa-key"></i> <?= e(__('Changer le mot de passe')) ?></h5>
      <form action="<?= url('profil/mot-de-passe') ?>" method="post" novalidate>
        <?= csrf_field() ?>
        <div class="row g-3">
          <div class="col-md-4"><?= form_field('current_password', __('Mot de passe actuel'), '', ['type' => 'password', 'required' => true]) ?></div>
          <div class="col-md-4"><?= form_field('password', __('Nouveau mot de passe'), '', ['type' => 'password', 'required' => true]) ?></div>
          <div class="col-md-4"><?= form_field('password_confirm', __('Confirmation'), '', ['type' => 'password', 'required' => true]) ?></div>
        </div>
        <div class="text-end mt-3">
          <button class="btn btn-outline-ylcd"><i class="fa-solid fa-lock me-1"></i> <?= e(__('Mettre à jour le mot de passe')) ?></button>
        </div>
      </form>
    </div>
  </div>
</div>
