<?php
require_once __DIR__ . "/db.php";
$config = json_decode(database::find("SELECT * FROM options WHERE option_name='general_settings' LIMIT 1")[0]["option_value"], true);
$template_config = json_decode(database::find("SELECT * FROM options WHERE option_name='theme.general' LIMIT 1")[0]["option_value"], true);
session_start();
if (isset($_SESSION["current_language"]) != "") {
    require_once(__DIR__ . "/../language/" . $_SESSION["current_language"] . ".php");
} else {
    require_once(__DIR__ . "/../language/" . $config["language"] . ".php");
}
include(__DIR__ . "/functions.php");
if (empty($_SESSION['token'])) {
    $_SESSION['token'] = generate_csrf_token();
}