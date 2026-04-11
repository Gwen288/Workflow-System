<?php

function _base_path() {
    return rtrim(str_replace('/public/index.php', '', $_SERVER['SCRIPT_NAME']), '/');
}

function asset($path) {
    return _base_path() . '/' . ltrim($path, '/');
}

function url($path) {
    return _base_path() . '/' . ltrim($path, '/');
}

function auth() {
    return isset($_SESSION['user_id']) ? $_SESSION['user_id'] : false;
}

function auth_user() {
    return isset($_SESSION['user']) ? $_SESSION['user'] : null;
}

function is_admin() {
    $user = auth_user();
    return $user && $user['role'] === 'Admin';
}
