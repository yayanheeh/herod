<?php
require_once("config.php");
if (!empty($_POST["url"]) && hash_equals($_SESSION['token'], $_POST['token']) && hash_equals($config["fingerprint"], create_fingerprint(rtrim($config["url"], '/\\'), $config["purchase_code"]))) {
    $domain = str_ireplace("www.", "", parse_url($_POST["url"], PHP_URL_HOST));
    if (!empty(explode('.', str_ireplace("www.", "", parse_url($_POST["url"], PHP_URL_HOST)))[1])) {
        $main_domain = explode('.', str_ireplace("www.", "", parse_url($_POST["url"], PHP_URL_HOST)))[1];
    } else {
        $main_domain = false;
    }
    switch (true) {
        case($domain == "instagram.com"):
            include(__DIR__ . "/classes/instagram.class.php");
            $download = new instagram();
            return_json($download->media_info($_POST["url"]));
            break;
        case($domain == "youtube.com" || $domain == "m.youtube.com" || $domain == "youtu.be"):
            include(__DIR__ . "/classes/youtube.class.php");
            $download = new youtube();
            return_json($download->media_info($_POST["url"]));
            break;
        case($domain == "facebook.com" || $domain == "m.facebook.com" || $domain == "web.facebook.com"):
            include(__DIR__ . "/classes/facebook.class.php");
            $download = new facebook();
            return_json($download->media_info($_POST["url"]));
            break;
        case($domain == "twitter.com" || $domain == "video.twimg.com"):
            include(__DIR__ . "/classes/twitter.class.php");
            $download = new twitter();
            return_json($download->media_info($_POST["url"]));
            break;
        case($domain == "dailymotion.com" || $domain == "dai.ly"):
            include(__DIR__ . "/classes/dailymotion.class.php");
            $download = new dailymotion();
            return_json($download->media_info($_POST["url"]));
            break;
        case ($domain == "vimeo.com"):
            include(__DIR__ . "/classes/vimeo.class.php");
            $download = new vimeo();
            return_json($download->media_info($_POST["url"]));
            break;
        case ($main_domain == "tumblr"):
            include(__DIR__ . "/classes/tumblr.class.php");
            $download = new tumblr();
            return_json($download->media_info($_POST["url"]));
            break;
        case($domain == "imgur.com" || $domain == "0imgur.com"):
            include(__DIR__ . "/classes/imgur.class.php");
            $download = new imgur();
            return_json($download->media_info($_POST["url"]));
            break;
        case ($domain == "liveleak.com"):
            include(__DIR__ . "/classes/liveleak.class.php");
            $download = new liveleak();
            return_json($download->media_info($_POST["url"]));
            break;
        case($domain == "ted.com"):
            include(__DIR__ . "/classes/ted.class.php");
            $download = new ted();
            return_json($download->media_info($_POST["url"]));
            break;
        case($domain == "mashable.com"):
            include(__DIR__ . "/classes/mashable.class.php");
            $download = new mashable();
            return_json($download->media_info($_POST["url"]));
            break;
        case($domain == "vk.com" || $domain == "m.vk.com"):
            include(__DIR__ . "/classes/vkontakte.class.php");
            $download = new vk();
            return_json($download->media_info($_POST["url"]));
            break;
        case($domain == "9gag.com" || $domain == "m.9gag.com"):
            include(__DIR__ . "/classes/ninegag.class.php");
            $download = new ninegag();
            return_json($download->media_info($_POST["url"]));
            break;
        case($domain == "break.com"):
            include(__DIR__ . "/classes/break_dl.class.php");
            $download = new break_dl();
            return_json($download->media_info($_POST["url"]));
            break;
        case($domain == "soundcloud.com"):
            include(__DIR__ . "/classes/soundcloud.class.php");
            $download = new soundcloud();
            return_json($download->media_info($_POST["url"]));
            break;
        case($domain == "tv.com"):
            include(__DIR__ . "/classes/tv.class.php");
            $download = new tv();
            return_json($download->media_info($_POST["url"]));
            break;
        case($domain == "flickr.com"):
            include(__DIR__ . "/classes/flickr.class.php");
            $download = new flickr();
            return_json($download->media_info($_POST["url"]));
            break;
        case($main_domain == "bandcamp"):
            include(__DIR__ . "/classes/bandcamp.class.php");
            $download = new bandcamp();
            return_json($download->media_info($_POST["url"]));
            break;
        case($domain == "espn.com"):
            include(__DIR__ . "/classes/espn.class.php");
            $download = new espn();
            return_json($download->media_info($_POST["url"]));
            break;
        case($domain == "imdb.com" || $domain == "m.imdb.com"):
            include(__DIR__ . "/classes/imdb.class.php");
            $download = new imdb();
            return_json($download->media_info($_POST["url"]));
            break;
        case($domain == "izlesene.com"):
            include(__DIR__ . "/classes/izlesene.class.php");
            $download = new izlesene();
            return_json($download->media_info($_POST["url"]));
            break;
        case($domain == "buzzfeed.com" || $domain == "www.buzzfeed.com"):
            include(__DIR__ . "/classes/buzzfeed.class.php");
            $download = new buzzfeed();
            return_json($download->media_info($_POST["url"]));
            break;
        case($domain == "puhutv.com"):
            include(__DIR__ . "/classes/puhutv.class.php");
            $download = new puhutv();
            return_json($download->media_info($_POST["url"]));
            break;
        case($domain == "m.tiktok.com"):
            include(__DIR__ . "/classes/tiktok.class.php");
            $download = new tiktok();
            return_json($download->media_info($_POST["url"]));
            break;
        default:
            echo "error";
            die();
            break;
    }
} else {
    echo "error";
    die();
}