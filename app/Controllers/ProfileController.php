<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Upload;
use App\Core\Validator;
use App\Models\User;

/**
 * Profil de l'utilisateur connecté (admin ou membre) :
 * consultation, modification du nom d'utilisateur / email / photo, changement de mot de passe,
 * et configuration obligatoire du compte admin par défaut à la première connexion.
 */
class ProfileController extends Controller
{
    private const DEFAULT_USERNAME = 'admin';
    private const DEFAULT_EMAIL = 'admin@test.com';
    private const DEFAULT_PASSWORD = 'admin';

    public function show(): void
    {
        $this->render('profile/show', ['title' => __('Mon profil'), 'user' => Auth::user()], 'admin');
    }

    /** Mise à jour du nom d'utilisateur, de l'email et de la photo. */
    public function update(): void
    {
        $user = Auth::user();
        $id = (int) $user['id'];
        $username = $this->input('username');
        $email = mb_strtolower($this->input('email'));

        $v = $this->identityRules($_POST, $username, $email, $id);
        $photo = $this->handlePhoto($v, $user);
        if ($v->fails()) {
            $this->back('profil', $v->errors, $_POST);
        }

        User::update($id, ['username' => $username, 'email' => $email, 'photo' => $photo]);
        flash('success', __('Votre profil a été mis à jour.'));
        redirect('profil');
    }

    public function password(): void
    {
        $user = Auth::user();
        $v = (new Validator($_POST))
            ->required('current_password', 'Le mot de passe actuel')
            ->required('password', 'Le nouveau mot de passe')->min('password', 8, 'Le nouveau mot de passe')
            ->required('password_confirm', 'La confirmation')
            ->same('password', 'password_confirm', 'Les mots de passe ne correspondent pas.')
            ->check(!password_verify((string) ($_POST['current_password'] ?? ''), $user['password']), 'current_password', 'Mot de passe actuel incorrect.');

        if ($v->fails()) {
            $this->back('profil#securite', $v->errors, [], __('Le mot de passe n’a pas été modifié.'));
        }

        User::update((int) $user['id'], ['password' => User::hash((string) $_POST['password'])]);
        session_regenerate_id(true);
        flash('success', __('Votre mot de passe a été modifié.'));
        redirect('profil');
    }

    public function deletePhoto(): void
    {
        $user = Auth::user();
        Upload::delete($user['photo']);
        User::update((int) $user['id'], ['photo' => null]);
        flash('success', __('Photo supprimée : vos initiales sont affichées à la place.'));
        redirect('profil');
    }

    /* ---------- Première connexion de l'admin par défaut ---------- */

    public function setupForm(): void
    {
        if (!Auth::mustChangeCredentials()) {
            redirect(Auth::homePath());
        }
        $this->render('profile/setup', ['title' => __('Sécuriser le compte'), 'user' => Auth::user()], 'auth');
    }

    public function setup(): void
    {
        if (!Auth::mustChangeCredentials()) {
            redirect(Auth::homePath());
        }
        $user = Auth::user();
        $id = (int) $user['id'];
        $username = $this->input('username');
        $email = mb_strtolower($this->input('email'));
        $password = (string) ($_POST['password'] ?? '');

        $v = $this->identityRules($_POST, $username, $email, $id)
            ->required('password', 'Le nouveau mot de passe')->min('password', 8, 'Le mot de passe')
            ->required('password_confirm', 'La confirmation')
            ->same('password', 'password_confirm', 'Les mots de passe ne correspondent pas.')
            ->check(mb_strtolower($username) === self::DEFAULT_USERNAME, 'username', 'Choisissez un nom d’utilisateur différent de « admin ».')
            ->check($email === self::DEFAULT_EMAIL, 'email', 'Indiquez une vraie adresse email (différente de admin@test.com).')
            ->check($password === self::DEFAULT_PASSWORD || password_verify($password, $user['password']), 'password', 'Le nouveau mot de passe doit être différent de l’ancien.');

        $photo = $this->handlePhoto($v, $user);
        if ($v->fails()) {
            $this->back('compte/configuration', $v->errors, $_POST);
        }

        User::update($id, [
            'username'                => $username,
            'email'                   => $email,
            'photo'                   => $photo,
            'password'                => User::hash($password),
            'must_change_credentials' => 0,
        ]);
        session_regenerate_id(true);
        Auth::refresh();

        flash('success', __('Compte sécurisé ! Utilisez désormais vos nouveaux identifiants pour vous connecter.'));
        redirect(Auth::homePath());
    }

    /* ---------- Outils ---------- */

    private function identityRules(array $data, string $username, string $email, int $id): Validator
    {
        return (new Validator($data))
            ->required('username', "Le nom d'utilisateur")->min('username', 3, "Le nom d'utilisateur")->max('username', 50, "Le nom d'utilisateur")
            ->pattern('username', '/^[\p{L}0-9 ._-]+$/u', 'Lettres, chiffres, espaces, points, tirets et _ uniquement.')
            ->required('email', "L'email")->email('email')->max('email', 150, "L'email")
            ->check(User::usernameTaken($username, $id), 'username', "Ce nom d'utilisateur est déjà pris.")
            ->check(User::emailTaken($email, $id), 'email', 'Cet email est déjà utilisé.');
    }

    /** Enregistre la nouvelle photo si elle est valide et retourne le chemin à conserver. */
    private function handlePhoto(Validator $v, array $user): ?string
    {
        if ($v->fails() || !Upload::has($_FILES['photo'] ?? null)) {
            return $user['photo'];
        }
        try {
            $path = Upload::store($_FILES['photo'], 'users')['path'];
            Upload::delete($user['photo']);
            return $path;
        } catch (\RuntimeException $e) {
            $v->check(true, 'photo', $e->getMessage());
            return $user['photo'];
        }
    }
}
