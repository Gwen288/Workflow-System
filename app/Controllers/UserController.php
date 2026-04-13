<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;

class UserController extends Controller {
    public function __construct() {
        if (!auth()) {
            $this->redirect('/login');
        }
        
        // Strict Isolation: Only Admins can access this controller
        if (auth_user()['role'] !== 'Admin') {
            $this->redirect('/dashboard');
        }
    }

    public function index() {
        $userModel = new User();
        $users = $userModel->all();
        $this->view('users/index', ['users' => $users]);
    }

    public function edit($id) {
        $userModel = new User();
        $user = $userModel->find($id);
        
        if (!$user || $id == auth()) { // Block empty users or self-editing
            return $this->redirect('/users');
        }

        $roles = ['Student', 'Staff', 'HOD', 'Library', 'CFO', 'Finance Officer', 'Registry', 'Logistics', 'Admin'];
        $this->view('users/edit', [
            'user' => $user,
            'roles' => $roles
        ]);
    }

    public function update($id) {
        if ($id == auth()) { // Double-guard against self-updates via POST
            return $this->redirect('/users');
        }
        
        $userModel = new User();
        
        $data = [
            'role' => $_POST['role'] ?? 'Student',
            'department' => $_POST['department'] ?? 'General'
        ];

        if ($userModel->update($id, $data)) {
            // If the current admin is updating themselves, refresh the session
            if ($id == auth()) {
                $_SESSION['user']['role'] = $data['role'];
                $_SESSION['user']['department'] = $data['department'];
            }
            return $this->redirect('/users');
        }

        return $this->redirect("/users/edit/$id");
    }
}
