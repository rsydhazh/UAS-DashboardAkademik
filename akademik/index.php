
<?php
include_once __DIR__."/includes/config.php";
include_once __DIR__."/includes/class.php";

// Pastikan sesi dimulai
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Buat instance App
try {
    $app = new App($config);
} catch (Exception $e) {
    echo "Error initializing application: " . $e->getMessage();
    exit;
}

ob_start();
$component = isset($_REQUEST["com"]) ? $_REQUEST["com"] : "Beranda";
$task = isset($_REQUEST["task"]) ? $_REQUEST["task"] : "index";

// Jika ini adalah halaman utama (root)
if ($component == "Beranda" && $task == "index") {
    // Jika pengguna belum login, tampilkan halaman login
    if (!isset($_SESSION["pengguna"])) {
        include_once $app->config["server"]."/webpages/login.php";
    } 
    // Jika pengguna sudah login, redirect ke admin
    else {
        header("Location:".$app->config["site"]."/admin");
        exit;
    }
} 
// Untuk semua kasus lainnya, load component dan tampilkan konten
else {
    try {
        $content = $app->loadComponent();
        echo $content;
    } catch (Exception $e) {
        echo "Error loading component: " . $e->getMessage();
    }
}

ob_end_flush();
?>







