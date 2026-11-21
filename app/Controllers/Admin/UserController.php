<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Upload;
use App\Models\User;

/** Gestion des comptes : l'admin peut promouvoir un membre en admin (ou l'inverse) et supprimer un compte. */
class UserController extends AdminController
{
    private const BASE = 'admin/utilisateurs';

    public function index(): void
    {
        $this->view('users/index', ['title' => __('Utilisateurs'), 'pager' => $this->listing(User::class)]);
    }

    public function role(int $id): void
    {
        $user = User::find($id) ?? $this->notFound(self::BASE);
        $role = $this->input('role');

        if (!isset(User::ROLES[$role])) {
            flash('error', __('Rôle invalide.'));
            redirect(self::BASE);
        }
        if ($user['role'] === User::ROLE_ADMIN && $role !== User::ROLE_ADMIN && User::adminCount() <= 1) {
            flash('error', __('Impossible : il doit rester au moins un administrateur.'));
            redirect(self::BASE);
        }

        User::update($id, ['role' => $role]);
        flash('success', __(':name est maintenant :role.', ['name' => $user['username'], 'role' => mb_strtolower(__(User::ROLES[$role]))]));
        redirect($id === Auth::id() && $role !== User::ROLE_ADMIN ? 'profil' : self::BASE);
    }

    public function destroy(int $id): void
    {
        $user = User::find($id) ?? $this->notFound(self::BASE);
        if ($id === Auth::id()) {
            flash('error', __('Vous ne pouvez pas supprimer votre propre compte.'));
            redirect(self::BASE);
        }
        if ($user['role'] === User::ROLE_ADMIN && User::adminCount() <= 1) {
            flash('error', __('Impossible de supprimer le dernier administrateur.'));
            redirect(self::BASE);
        }
        Upload::delete($user['photo']);
        User::delete($id);
        flash('success', __('Le compte de :name a été supprimé.', ['name' => $user['username']]));
        redirect(self::BASE);
    }
}
