<?php

namespace App\Controllers;

use App\Services\Interfaces\ILoginService;
use App\Services\LoginService;
use App\Models\User;
use Exception;

class LoginController
{
    private ILoginService $loginService;

    public function __construct()
    {
        $this->loginService = new LoginService();
    }

    public function showLogin(): void
    {
        require __DIR__ . '/../Views/login.php';
    }

    public function login(): void
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                header('Location: /login');
                exit();
            }

            if (isset($_SESSION['user'])) {
                unset($_SESSION['user']);
            }

            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';

            if ($username === '' || $password === '') {
                $_SESSION['invalid_credentials'] = 'Please enter username and password.';
                header('Location: /login');
                exit();
            }

            $user = $this->loginService->login($username, $password);

            if (!$user) {
                $_SESSION['invalid_credentials'] = 'Invalid username or password.';
                header('Location: /login');
                exit();
            }

            $_SESSION['user'] = [
                'id' => $user->id,
                'username' => $user->username,
                'role' => $user->role
            ];

            header('Location: /');
            exit();

        } catch (Exception $e) {
            $_SESSION['invalid_credentials'] = 'Something went wrong. Please try again.';
            header('Location: /login');
            exit();
        }
    }

    public function logout()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                header('Location: /');
                exit();
            }

            unset($_SESSION['user']);

            $_SESSION['confirm_logout'] = 'You have been logged out successfully.';
            header('Location: /login');
            exit();

        } catch (Exception $e) {
            $_SESSION['invalid_credentials'] = 'Error logging out.';
            header('Location: /login');
            exit();
        }
    }

    public function showCreateAccount()
    {
        $user = new User();
        require __DIR__ . '/../Views/createaccount.php';
    }

    public function createAccount()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                header('Location: /createaccount');
                exit();
            }

            $user = new User();
            $user->firstname = trim($_POST['firstname'] ?? '');
            $user->lastname = trim($_POST['lastname'] ?? '');
            $user->username = trim($_POST['username'] ?? '');
            $user->email = trim($_POST['email'] ?? '');
            $user->password = $_POST['password'] ?? '';

            $confirmPassword = $_POST['confirmPassword'] ?? '';

            $this->loginService->createAccount($user, $confirmPassword);

            $_SESSION['account_created'] = 'Account created successfully. You can login now.';
            header('Location: /login');
            exit();

        } catch (Exception $e) {
            $_SESSION['username_email_validate'] = $e->getMessage();
            require __DIR__ . '/../Views/createaccount.php';
        }
    }
}