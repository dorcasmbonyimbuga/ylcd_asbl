<?php /** Détail d'un message. Variable : $message. */ ?>
<a href="<?= url('admin/messages') ?>" class="back-link"><i class="fa-solid fa-arrow-left me-1"></i> <?= e(__('Retour aux messages')) ?></a>

<div class="panel message-view">
  <div class="d-flex flex-wrap gap-3 align-items-start justify-content-between mb-4">
    <div class="d-flex align-items-center gap-3">
      <?= avatar(['username' => $message['nom'], 'photo' => null], 52) ?>
      <div>
        <h4 class="mb-0"><?= e($message['nom']) ?></h4>
        <a href="mailto:<?= e($message['email']) ?>"><?= e($message['email']) ?></a>
        <?php if ($message['telephone']): ?> · <a href="tel:<?= e($message['telephone']) ?>"><?= e($message['telephone']) ?></a><?php endif; ?>
      </div>
    </div>
    <div class="text-end small text-muted">
      <?= e(__('Reçu le :date', ['date' => date_long($message['created_at'])])) ?><br>
      <?= $message['email_envoye']
          ? '<span class="status status-on">' . e(__('Copie envoyée par email')) . '</span>'
          : '<span class="status status-warn">' . e(__('Email non envoyé (SMTP)')) . '</span>' ?>
    </div>
  </div>

  <?php if ($message['sujet']): ?><h5 class="mb-3"><?= e($message['sujet']) ?></h5><?php endif; ?>
  <div class="message-body"><?= nl2br(e($message['message'])) ?></div>

  <div class="d-flex gap-2 mt-4">
    <a href="mailto:<?= e($message['email']) ?>?subject=<?= rawurlencode('Re: ' . ($message['sujet'] ?: __('Votre message à YLCD-ASBL'))) ?>" class="btn btn-ylcd">
      <i class="fa-solid fa-reply me-1"></i> <?= e(__('Répondre par email')) ?>
    </a>
    <form action="<?= url('admin/messages/' . $message['id'] . '/supprimer') ?>" method="post" data-confirm="<?= e(__('Supprimer ce message ?')) ?>">
      <?= csrf_field() ?>
      <button class="btn btn-outline-danger"><i class="fa-regular fa-trash-can me-1"></i> <?= e(__('Supprimer')) ?></button>
    </form>
  </div>
</div>
