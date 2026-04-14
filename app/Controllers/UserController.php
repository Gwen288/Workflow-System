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
        $userModel = new User();
        $existingUser = $userModel->find($id);

        if (!$existingUser) {
            return $this->redirect('/users');
        }

        $role = $_POST['role'] ?? $existingUser['role'];
        
        // Safety: Prevent an admin from changing their own role to avoid lockouts
        if ($id == auth()) {
            $role = $existingUser['role'];
        }
        
        $data = [
            'role' => $role,
            'department' => $_POST['department'] ?? 'General'
        ];

        if ($userModel->update($id, $data)) {
            // Refresh session if updating current user
            if ($id == auth()) {
                $_SESSION['user']['role'] = $data['role'];
                $_SESSION['user']['department'] = $data['department'];
                
                // If it was a self-update, redirect to dashboard as they might have moved themselves out of Admin (though we blocked it above)
                return $this->redirect('/dashboard');
            }
            return $this->redirect('/users');
        }

        return $this->redirect("/users/edit/$id");
    }
}
