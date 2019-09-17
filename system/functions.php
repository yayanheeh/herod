<?php
function option($option_name = "general_settings", $echo = false)
{
    $option_value = database::find("SELECT * FROM options WHERE option_name='$option_name' LIMIT 1")[0]["option_value"];
    if ($echo === true) {
        echo $option_value;
    } else {
        return $option_value;
    }
}

function content($content_slug)
{
    $content = database::find("SELECT * FROM contents WHERE content_slug='$content_slug' LIMIT 1")[0];
    return $content;
}

function language_exists($language)
{
    if (file_exists(__DIR__ . "/../language/" . $language . ".php")) {
        return true;
    } else {
        return false;
    }
}

function return_json($array)
{
    if (empty($array["links"]["0"]["url"])) {
        echo "error";
        die();
    } else {
        database::create_log($array);
        header('Content-Type: application/json');
        echo json_encode($array);
        die();
    }
}

function check_url($url)
{
    if (empty($url)) {
        echo "error";
        die();
    }
}

function redirect($url)
{
    header('Location: ' . $url);
}

function format_seconds($seconds)
{
    return gmdate(($seconds > 3600 ? "H:i:s" : "i:s"), $seconds);
}

function sanitize_output($buffer)
{
    $search = array(
        '/\>[^\S ]+/s',
        '/[^\S ]+\</s',
        '/(\s)+/s',
        '/<!--(.|\s)*?-->/'
    );
    $replace = array(
        '>',
        '<',
        '\\1',
        ''
    );
    $buffer = preg_replace($search, $replace, $buffer);
    return $buffer;
}

function generate_string($length = 10)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function create_fingerprint($string1, $string2)
{
    $fingerprint = sha1($string1 . $string2);
    return $fingerprint;
}

function format_size($bytes)
{
    switch ($bytes) {
        case $bytes < 1024:
            $size = $bytes . " B";
            break;
        case $bytes < 1048576:
            $size = round($bytes / 1024, 2) . " KB";
            break;
        case $bytes < 1073741824:
            $size = round($bytes / 1048576, 2) . " MB";
            break;
        case $bytes < 1099511627776:
            $size = round($bytes / 1073741824, 2) . " GB";
            break;
    }
    if (!empty($size)) {
        return $size;
    } else {
        return "";
    }
}

function integrity_check()
{
    $file = __DIR__ . "/action.php";
    $sha1 = sha1_file($file);
    if (hash_equals($sha1, url_get_contents("https://drive.google.com/uc?export=download&id=1wxkGsnTLVT0TkWPIunJJq_icSibF2fiI"))) {
        return true;
    } else {
        return false;
    }
}

function url_get_contents($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.135 Safari/537.36 Edge/12.10240');
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}

function unshorten($url, $max_redirs = 3)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_MAXREDIRS, $max_redirs);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Niche Office Link Checker 1.0');
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_exec($ch);
    $url = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
    curl_close($ch);
    return $url;
}

function get_file_size($url, $format = true)
{
    $headers = get_headers($url, 1);
    if (is_array($headers) && count($headers) > 0) {
        $size = $headers['Content-Length'];
        if (is_array($size)) {
            foreach ($size as $value) {
                if ($value != 0) {
                    $size = $value;
                    break;
                }
            }
        }
        if ($format === true) {
            return format_size($size);
        } else {
            return $size;
        }
    } else {
        return "unknown";
    }
}

function sanitize_filename($string, $type)
{
    $string = htmlentities($string, ENT_QUOTES, 'UTF-8');
    $string = preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', $string);
    $string = html_entity_decode($string, ENT_QUOTES, 'UTF-8');
    $string = preg_replace(array('~[^0-9a-z]~i', '~[ -]+~'), ' ', $string);
    if (empty(trim($string, ' -'))) {
        $name = generate_string();
    } else {
        $name = trim($string, ' -');
    }
    $file_name = $name . "." . $type;
    return str_replace(array("\r", "\n"), "", $file_name);
}

function clear_string($data)
{
    $data = stripslashes(trim($data));
    $data = strip_tags($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

function xss_clean($data)
{
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

function force_download($url, $title, $type)
{
    $context_options = array(
        "ssl" => array(
            "verify_peer" => false,
            "verify_peer_name" => false,
        ),
    );
    header('Content-Disposition: attachment; filename="' . sanitize_filename($title, $type) . '"');
    header("Content-Transfer-Encoding: binary");
    header('Expires: 0');
    header('Pragma: no-cache');
    $file_size = get_file_size($url, false);
    if ($file_size > 0) {
        header('Content-Length: ' . $file_size);
    }
    if (isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== FALSE) {
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
    }
    ob_clean();
    ob_end_flush();
    readfile($url, "", stream_context_create($context_options));
    exit;
}

function generate_csrf_token()
{
    if (defined('PHP_MAJOR_VERSION') && PHP_MAJOR_VERSION > 5) {
        return bin2hex(random_bytes(32));
    } else {
        if (function_exists('mcrypt_create_iv')) {
            return bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM));
        } else {
            return bin2hex(openssl_random_pseudo_bytes(32));
        }
    }
}