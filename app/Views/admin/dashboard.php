<?php
/** Tableau de bord admin. Variables : $stats, $messages, $users, $activites. */
$cards = [
    ['admin/activites', 'fa-camera-retro', 'Activités', $stats['activites'], 'navy'],
    ['admin/projets', 'fa-diagram-project', 'Projets', $stats['projets'], 'purple'],
    ['admin/services', 'fa-hand-holding-heart', 'Services', $stats['services'], 'green'],
    ['admin/membres', 'fa-people-group', "Membres de l'équipe", $stats['membres'], 'navy'],
    ['admin/messages', 'fa-envelope', 'Messages non lus', $stats['nonLus'] . ' / ' . $stats['messages'], 'purple'],
    ['admin/utilisateurs', 'fa-user-shield', 'Comptes utilisateurs', $stats['users'], 'green'],
];
?>
<div class="welcome-banner mb-4">
  <div>
    <h2><?= e(__('Bonjour, :name', ['name' => auth()['username']])) ?> 👋</h2>
    <p class="mb-0"><?= e(__('Gérez ici le contenu du site : activités, projets, services, équipe et messages reçus.')) ?></p>
  </div>
  <a href="<?= url('admin/activites/creer') ?>" class="btn btn-light-ylcd"><i class="fa-solid fa-plus me-1"></i> <?= e(__('Nouvelle activité')) ?></a>
</div>

<div class="row g-3 mb-4">
  <?php foreach ($cards as [$path, $icon, $label, $value, $color]): ?>
  <div class="col-6 col-md-4 col-xl-2">
    <a href="<?= url($path) ?>" class="stat-card stat-<?= $color ?>">
      <span class="stat-icon"><i class="fa-solid <?= $icon ?>"></i></span>
      <span class="stat-value"><?= e($value) ?></span>
      <span class="stat-label"><?= e(__($label)) ?></span>
    </a>
  </div>
  <?php endforeach; ?>
</div>

<div class="row g-4">
  <div class="col-xl-7">
    <div class="panel h-100">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="panel-title mb-0"><i class="fa-solid fa-inbox"></i> <?= e(__('Derniers messages')) ?></h5>
        <a href="<?= url('admin/messages') ?>" class="small"><?= e(__('Tout voir')) ?></a>
      </div>
      <?php if (!$messages): ?>
        <p class="empty-state"><i class="fa-regular fa-envelope-open"></i><?= e(__('Aucun message reçu pour le moment.')) ?></p>
      <?php else: ?>
      <div class="list-group list-group-flush">
        <?php foreach ($messages as $m): ?>
        <a href="<?= url('admin/messages/' . $m['id']) ?>" class="list-group-item list-group-item-action px-0 <?= $m['est_lu'] ? '' : 'fw-bold' ?>">
          <div class="d-flex justify-content-between">
            <span><?php if (!$m['est_lu']): ?><span class="dot-unread"></span><?php endif; ?><?= e($m['nom']) ?></span>
            <small class="text-muted fw-normal"><?= e(date_long($m['created_at'])) ?></small>
          </div>
          <div class="small text-muted fw-normal"><?= e($m['sujet'] ? $m['sujet'] . ' — ' : '') ?><?= e(excerpt($m['message'], 80)) ?></div>
        </a>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>
  </div>
  <div class="col-xl-5">
    <div class="panel mb-4">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="panel-title mb-0"><i class="fa-solid fa-clock-rotate-left"></i> <?= e(__('Activités récentes')) ?></h5>
        <a href="<?= url('admin/activites') ?>" class="small"><?= e(__('Gérer')) ?></a>
      </div>
      <ul class="simple-list">
        <?php foreach ($activites as $a): ?>
        <li>
          <a href="<?= url('admin/activites/' . $a['id'] . '/modifier') ?>"><?= e(tr($a, 'titre')) ?></a>
          <small><?= e($a['date_activite'] ? date_activite($a['date_activite']) : __('Régulière')) ?></small>
        </li>
        <?php endforeach; ?>
      </ul>
    </div>
    <div class="panel">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="panel-title mb-0"><i class="fa-solid fa-user-plus"></i> <?= e(__('Derniers inscrits')) ?></h5>
        <a href="<?= url('admin/utilisateurs') ?>" class="small"><?= e(__('Gérer')) ?></a>
      </div>
      <ul class="simple-list">
        <?php foreach ($users as $u): ?>
        <li class="d-flex align-items-center gap-2">
          <?= avatar($u, 32) ?>
          <span class="flex-grow-1"><?= e($u['username']) ?><br><small><?= e($u['email']) ?></small></span>
          <span class="role-badge role-<?= e($u['role']) ?>"><?= e(__(\App\Models\User::ROLES[$u['role']])) ?></span>
        </li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>
</div>
