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
        $password = $_POST['password'] ?? '';
        
        $userModel = new User();
        $user = $userModel->findByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user'] = $user;
            return $this->redirect('/dashboard');
        }

        $this->view('auth/login', ['error' => 'Invalid email address or password. Please try again.']);
    }

    public function showRegister() {
        if (auth()) {
            return $this->redirect('/dashboard');
        }
        $this->view('auth/register');
    }

    public function register() {
        $firstName = $_POST['first_name'] ?? '';
        $lastName = $_POST['last_name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if (empty($firstName) || empty($lastName) || empty($email) || empty($password)) {
            return $this->view('auth/register', ['error' => 'All fields are required.']);
        }

        if ($password !== $confirmPassword) {
            return $this->view('auth/register', ['error' => 'Passwords do not match.']);
        }

        $userModel = new User();
        
        // Check if user exists
        if ($userModel->findByEmail($email)) {
            return $this->view('auth/register', ['error' => 'Email address is already in use.']);
        }

        // Hash password and save
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $role = 'Student'; // Default role
        $name = trim($firstName . ' ' . $lastName);
        $userId = $userModel->create([
            'name' => $name,
            'email' => $email,
            'role' => $role,
            'password' => $hashedPassword,
            'department' => 'General'
        ]);

        if ($userId) {
            // Auto login after registration
            $user = $userModel->find($userId);
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user'] = $user;
            return $this->redirect('/dashboard');
        }

        $this->view('auth/register', ['error' => 'Registration failed. Please try again later.']);
    }

    public function logout() {
        session_destroy();
        return $this->redirect('/login');
    }
}
