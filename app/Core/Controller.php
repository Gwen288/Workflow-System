<?php
namespace App\Core;

class Controller {
    public function view($view, $data = []) {
        extract($data);
        $layout = "main";
        
        // Output buffering to inject view into layout
        ob_start();
        $viewFile = __DIR__ . "/../Views/{$view}.php";
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            die("View $view not found");
        }
        $content = ob_get_clean();

        require_once __DIR__ . "/../Views/layouts/{$layout}.php";
    }

    public function redirect($url) {
        // Build base path dynamically so it handles sub-directories in XAMPP
        $basePath = str_replace('/public/index.php', '', $_SERVER['SCRIPT_NAME']);
        $target = rtrim($basePath, '/') . '/' . ltrim($url, '/');
        
        header("Location: " . $target);
        exit();
    }

    public function json($data, $status = 200) {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }
}
