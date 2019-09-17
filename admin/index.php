<?php
include __DIR__ . "/functions.php";
require_once __DIR__ . "/../system/db.php";
ob_start("sanitize_output");
session_start();
switch (true) {
    default:
        include(__DIR__ . "/includes/login.php");
        break;
    case(isset($_SESSION["logged"]) === true):
        include(__DIR__ . "/includes/dashboard.php");
        break;
    case(!empty($_POST)):
        $config = json_decode(option(), true);
        if (isset($_POST["email"]) != "" && isset($_POST["password"]) != "") {
            $find_user = database::check_password($_POST["email"], sha1($_POST["password"]));
            if (isset($find_user["user_level"]) == 1) {
                $_SESSION["user"] = $find_user;
                $_SESSION["logged"] = true;
                redirect($config["url"] . "/admin");
            } else {
                redirect($config["url"] . "/admin/?failed-login=1");
            }
        } else {
            redirect($config["url"] . "/admin/?failed-login=1");
        }
        break;
}