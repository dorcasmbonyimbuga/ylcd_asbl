<?php
/** Gestion des comptes utilisateurs. Variable : $pager. */
use App\Models\User;

$me = auth();
$searchPlaceholder = __('Nom d’utilisateur ou email...');
require ROOT . '/app/Views/partials/admin_toolbar.php';
?>
<p class="small text-muted"><?= __('Les comptes créés via « Se connecter → Créer un compte » sont des <strong>membres</strong>. Vous pouvez promouvoir un membre en administrateur.') ?></p>

<div class="panel p-0">
  <?php if (!$pager['items']): ?>
    <p class="empty-state"><i class="fa-solid fa-user-shield"></i><?= e(__('Aucun résultat pour cette recherche.')) ?></p>
  <?php else: ?>
  <div class="table-responsive">
    <table class="table admin-table align-middle mb-0">
      <thead><tr><th><?= e(__('Utilisateur')) ?></th><th><?= e(__('Inscription')) ?></th><th><?= e(__('Dernière connexion')) ?></th><th><?= e(__('Rôle')) ?></th><th class="text-end"><?= e(__('Actions')) ?></th></tr></thead>
      <tbody>
        <?php foreach ($pager['items'] as $u): $isMe = (int) $u['id'] === (int) $me['id']; ?>
        <tr>
          <td>
            <div class="d-flex align-items-center gap-3">
              <?= avatar($u, 40) ?>
              <div>
                <strong><?= e($u['username']) ?></strong><?php if ($isMe): ?> <span class="badge bg-light text-dark border"><?= e(__('vous')) ?></span><?php endif; ?>
                <div class="small text-muted"><?= e($u['email']) ?></div>
              </div>
            </div>
          </td>
          <td class="small text-nowrap"><?= e(date_long($u['created_at'], false)) ?></td>
          <td class="small text-nowrap"><?= $u['last_login_at'] ? e(date_long($u['last_login_at'])) : '<span class="text-muted">' . e(__('Jamais')) . '</span>' ?></td>
          <td>
            <form action="<?= url('admin/utilisateurs/' . $u['id'] . '/role') ?>" method="post" class="role-form"
                  data-confirm="<?= e(__('Changer le rôle de :name ?', ['name' => $u['username']])) ?>">
              <?= csrf_field() ?>
              <select name="role" class="form-select form-select-sm role-select role-<?= e($u['role']) ?>" data-autosubmit aria-label="<?= e(__('Rôle')) ?>">
                <?php foreach (User::ROLES as $key => $label): ?>
                <option value="<?= e($key) ?>" <?= $u['role'] === $key ? 'selected' : '' ?>><?= e(__($label)) ?></option>
                <?php endforeach; ?>
              </select>
            </form>
          </td>
          <td class="text-end">
            <?php if (!$isMe):
                $deleteUrl = 'admin/utilisateurs/' . $u['id'] . '/supprimer';
                $deleteLabel = __('Supprimer définitivement le compte de :name ?', ['name' => $u['username']]);
                require ROOT . '/app/Views/partials/delete_button.php';
            endif; ?>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php endif; ?>
</div>
<?php require ROOT . '/app/Views/partials/pagination.php'; ?>
