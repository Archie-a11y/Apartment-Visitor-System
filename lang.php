<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// 切换语言请求处理（通过 GET 参数 lang）
if (isset($_GET['lang'])) {
    $langReq = preg_replace('/[^a-zA-Z_\-]/', '', $_GET['lang']);
    $_SESSION['lang'] = $langReq;
    $redirect = strtok($_SERVER['REQUEST_URI'], '?');
    header('Location: ' . $redirect);
    exit;
}

$lang = isset($_SESSION['lang']) ? $_SESSION['lang'] : 'en';
$langFile = __DIR__ . '/lang/' . $lang . '.php';
if (file_exists($langFile)) {
    $translations = include $langFile;
} else {
    $translations = include __DIR__ . '/lang/en.php';
}

function t($key) {
    global $translations;
    return isset($translations[$key]) ? $translations[$key] : $key;
}

function lang_selected($code) {
    return (isset($_SESSION['lang']) && $_SESSION['lang'] === $code) ? 'selected' : '';
}
