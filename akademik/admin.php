<?php
include_once __DIR__."/includes/config.php";
include_once __DIR__."/includes/class.php";

// Pastikan sesi dimulai
session_start();

$app = new App($config);

// Jika pengguna belum login, redirect ke halaman login
if (!isset($_SESSION["pengguna"])) {
    header("Location:".$app->config["site"]);
    exit;
}

ob_start();
$component = isset($_REQUEST["com"]) ? $_REQUEST["com"] : "Beranda";
$content = $app->loadComponent();

if ($component != "Api") {
    include_once $app->config["server"]."/webpages/administrator.php";
} else {
    echo $content;
}
ob_end_flush();
?>

