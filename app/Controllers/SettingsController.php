<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;

class SettingsController extends Controller {
    public function __construct() {
        if (!auth()) {
            $this->redirect('/login');
        }
    }

    public function index() {
        $user = auth_user();
        
        $this->view('settings/index', [
            'user' => $user
        ]);
    }

    public function updateProfile() {
        $userModel = new User();
        $userId = auth_user()['user_id'];
        
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        
        if (!empty($name) && !empty($email)) {
            $userModel->update($userId, [
                'name' => $name,
                'email' => $email
            ]);
            
            // Update session
            $_SESSION['user']['name'] = $name;
            $_SESSION['user']['email'] = $email;
        }

        $this->redirect('/settings');
    }
}
