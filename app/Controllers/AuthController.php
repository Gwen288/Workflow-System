<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;

class AuthController extends Controller {
    public function showLogin() {
        if (auth()) {
            return $this->redirect('/dashboard');
        }
        $this->view('auth/login');
    }

    public function login() {
        $email = $_POST['email'] ?? '';
        $userModel = new User();
        $user = $userModel->findByEmail($email);

        if ($user) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user'] = $user;
            return $this->redirect('/dashboard');
        }

        $this->view('auth/login', ['error' => 'Invalid email address. Please try again.']);
    }

    public function logout() {
        session_destroy();
        return $this->redirect('/login');
    }
}
