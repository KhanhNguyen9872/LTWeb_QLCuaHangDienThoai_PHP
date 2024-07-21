<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');

$type = isset($_GET['type']) ? $_GET['type'] : header('Location: /admin/auth.php?type=login');

if ($type != 'logout') {
    session_start();

    if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
        header("location: /admin");
        exit;
    }

    $allow = array('login', 'register', 'forgot');

    $isAllow = false;
    foreach($allow as $s) {
        if ($s == $type) {
            $isAllow = true;
        }
    }
    
    if(!$isAllow) {
        header('Location: /admin/auth.php?type=login');
    }
}

require_once "../config/db.php";
require_once "../config/lib.php";

include "pages/" . $type . ".php";
?>