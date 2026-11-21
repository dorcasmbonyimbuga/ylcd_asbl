<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Validator;
use App\Models\User;

/** Connexion, inscription et déconnexion. */
class AuthController extends Controller
{
    public function loginForm(): void
    {
        $this->render('auth/login', ['title' => __('Se connecter')], 'auth');
    }

    public function login(): void
    {
        $login = $this->input('login');
        $password = (string) ($_POST['password'] ?? '');

        $v = (new Validator($_POST))
            ->required('login', "Le nom d'utilisateur ou l'email")
            ->required('password', 'Le mot de passe');
        if ($v->fails()) {
            $this->back('connexion', $v->errors, $_POST);
        }

        // Petite protection contre les essais en rafale
        $attempts = $_SESSION['_login_attempts'] ?? ['count' => 0, 'until' => 0];
        if ($attempts['until'] > time()) {
            $this->back('connexion', [], $_POST, __('Trop de tentatives. Réessayez dans quelques minutes.'));
        }

        $user = User::findByLogin($login);
        if (!$user || !password_verify($password, $user['password'])) {
            $attempts['count']++;
            if ($attempts['count'] >= 5) {
                $attempts = ['count' => 0, 'until' => time() + 300];
            }
            $_SESSION['_login_attempts'] = $attempts;
            $this->back('connexion', [], $_POST, __('Identifiants incorrects.'));
        }
        unset($_SESSION['_login_attempts']);

        if (password_needs_rehash($user['password'], PASSWORD_DEFAULT)) {
            User::update((int) $user['id'], ['password' => User::hash($password)]);
        }

        Auth::login($user);

        if ($user['must_change_credentials']) {
            flash('warning', __('Première connexion : pour sécuriser le compte, modifiez le nom d’utilisateur, l’email et le mot de passe par défaut.'));
            redirect('compte/configuration');
        }

        flash('success', __('Bienvenue, :name !', ['name' => $user['username']]));
        $intended = $_SESSION['_intended'] ?? null;
        unset($_SESSION['_intended']);
        redirect($intended && str_starts_with($intended, base_path() . '/') ? $intended : Auth::homePath());
    }

    public function registerForm(): void
    {
        $this->render('auth/register', ['title' => __('Créer un compte')], 'auth');
    }

    public function register(): void
    {
        $username = $this->input('username');
        $email = mb_strtolower($this->input('email'));

        $v = (new Validator($_POST))
            ->required('username', "Le nom d'utilisateur")->min('username', 3, "Le nom d'utilisateur")->max('username', 50, "Le nom d'utilisateur")
            ->pattern('username', '/^[\p{L}0-9 ._-]+$/u', "Lettres, chiffres, espaces, points, tirets et _ uniquement.")
            ->required('email', "L'email")->email('email')->max('email', 150, "L'email")
            ->required('password', 'Le mot de passe')->min('password', 8, 'Le mot de passe')
            ->required('password_confirm', 'La confirmation')
            ->same('password', 'password_confirm', 'Les mots de passe ne correspondent pas.')
            ->check(User::usernameTaken($username), 'username', "Ce nom d'utilisateur est déjà pris.")
            ->check(User::emailTaken($email), 'email', 'Cet email est déjà utilisé.');

        $photo = null;
        if (!$v->fails() && \App\Core\Upload::has($_FILES['photo'] ?? null)) {
            try {
                $photo = \App\Core\Upload::store($_FILES['photo'], 'users')['path'];
            } catch (\RuntimeException $e) {
                $v->check(true, 'photo', $e->getMessage());
            }
        }

        if ($v->fails()) {
            $this->back('inscription', $v->errors, $_POST);
        }

        // Le tout premier compte créé devient administrateur ; les suivants sont membres.
        $role = User::count() === 0 ? User::ROLE_ADMIN : User::ROLE_MEMBRE;

        $id = User::create([
            'username' => $username,
            'email'    => $email,
            'photo'    => $photo,
            'password' => User::hash((string) $_POST['password']),
            'role'     => $role,
        ]);

        Auth::login(User::find($id));
        flash('success', __('Votre compte a été créé. Bienvenue, :name !', ['name' => $username]));
        redirect(Auth::homePath());
    }

    public function logout(): void
    {
        Auth::logout();
        session_start();
        flash('success', __('Vous êtes déconnecté.'));
        redirect('connexion');
    }
}
