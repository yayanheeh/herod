<?php
require_once __DIR__ . "/system/config.php";
$template = $config["template"];
include(__DIR__ . "/template/" . $template . "/functions.php");
ob_start("sanitize_output");
switch (true) {
    default:
        include(__DIR__ . "/template/" . $template . "/header.php");
        include(__DIR__ . "/template/" . $template . "/main.php");
        include(__DIR__ . "/template/" . $template . "/footer.php");
        break;
    case(!empty($_GET["source"]) && !empty($_GET["title"]) && !empty($_GET["download"]) && !empty($_GET["type"])):
        switch (true) {
            case(isset($config["bandwidth_saving"]) == "true" OR strip_tags($_GET["source"]) == "izlesene"):
                redirect(strip_tags(base64_decode($_GET["download"])));
                break;
            default:
                force_download(strip_tags(base64_decode($_GET["download"])), urldecode(base64_decode($_GET["title"])), clear_string($_GET["type"]));
                break;
        }
        break;
    case(isset($_GET["lang"]) != ""):
        if (language_exists(clear_string($_GET["lang"])) === true) {
            $_SESSION["current_language"] = clear_string($_GET["lang"]);
            redirect($config["url"]);
        } else {
            redirect($config["url"]);
        }
        break;
    case(isset($_GET["page"])):
        $slug = htmlentities(urlencode($_GET["page"]));
        $page_exists = database::slug_exists($slug);
        if ($page_exists === 0) {
            redirect($config["url"]);
        }
        include(__DIR__ . "/template/" . $template . "/header.php");
        include(__DIR__ . "/template/" . $template . "/page.php");
        include(__DIR__ . "/template/" . $template . "/footer.php");
        break;
}