<?php
/** Boîte de réception (formulaire de contact). Variable : $pager. */
$searchPlaceholder = __('Nom, email, sujet, message...');
require ROOT . '/app/Views/partials/admin_toolbar.php';
?>
<p class="small text-muted"><?= __('Messages envoyés depuis le formulaire « Contactez-nous ». Une copie est envoyée à <strong>:email</strong>.', ['email' => e(config('mail.to'))]) ?></p>

<div class="panel p-0">
  <?php if (!$pager['items']): ?>
    <p class="empty-state"><i class="fa-regular fa-envelope-open"></i><?= e(search_query() !== '' ? __('Aucun résultat pour cette recherche.') : __('Aucun message reçu pour le moment.')) ?></p>
  <?php else: ?>
  <div class="table-responsive">
    <table class="table admin-table align-middle mb-0">
      <thead><tr><th></th><th><?= e(__('Expéditeur')) ?></th><th><?= e(__('Message')) ?></th><th><?= e(__('Reçu le')) ?></th><th><?= e(__('Email')) ?></th><th class="text-end"><?= e(__('Actions')) ?></th></tr></thead>
      <tbody>
        <?php foreach ($pager['items'] as $m): ?>
        <tr class="<?= $m['est_lu'] ? '' : 'row-unread' ?>">
          <td style="width:20px"><?php if (!$m['est_lu']): ?><span class="dot-unread" title="<?= e(__('Non lu')) ?>"></span><?php endif; ?></td>
          <td><strong><?= e($m['nom']) ?></strong><div class="small text-muted"><?= e($m['email']) ?></div></td>
          <td>
            <a href="<?= url('admin/messages/' . $m['id']) ?>" class="text-reset">
              <?php if ($m['sujet']): ?><strong><?= e($m['sujet']) ?></strong> — <?php endif; ?>
              <span class="text-muted"><?= e(excerpt($m['message'], 70)) ?></span>
            </a>
          </td>
          <td class="small text-nowrap"><?= e(date_long($m['created_at'])) ?></td>
          <td><?= $m['email_envoye']
              ? '<span class="status status-on" title="' . e(__('Copie envoyée par email')) . '">' . e(__('Envoyé')) . '</span>'
              : '<span class="status status-warn" title="' . e(__('Email non envoyé : vérifiez la configuration SMTP')) . '">' . e(__('Non envoyé')) . '</span>' ?></td>
          <td class="text-end text-nowrap">
            <a href="<?= url('admin/messages/' . $m['id']) ?>" class="btn btn-icon" title="<?= e(__('Lire')) ?>"><i class="fa-regular fa-eye"></i></a>
            <?php $deleteUrl = 'admin/messages/' . $m['id'] . '/supprimer'; $deleteLabel = __('Supprimer ce message ?');
            require ROOT . '/app/Views/partials/delete_button.php'; ?>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php endif; ?>
</div>
<?php require ROOT . '/app/Views/partials/pagination.php'; ?>
