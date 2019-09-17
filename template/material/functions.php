<?php
function get_domain($url)
{
    $pieces = parse_url($url);
    $domain = isset($pieces['host']) ? $pieces['host'] : '';
    if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
        return $regs['domain'];
    }
    return false;
}

function build_menu($footer = false)
{
    $menu = json_decode(option("theme.menu"), true);
    if (!empty($menu)) {
        foreach ($menu as $node) {
            if (!empty($node['title']) && !empty($node['url'])) {
                if ($footer === true) {
                    echo '<li><a href="' . $node['url'] . '">' . $node['title'] . '</a></li>';
                } else {
                    echo '<li class="nav-item"><a class="nav-link" href="' . $node['url'] . '">' . $node['title'] . '</a></li>';
                }
            }
        }
    }
}

function social_links()
{
    $social_links = json_decode(option("theme.general"), true);
    foreach ($social_links as $link => $key) {
        if (!empty($key)) {
            switch ($link) {
                case 'facebook':
                    echo '<a class="btn btn-sm btn-social btn-fill btn-facebook" href="https://facebook.com/' . $key . '"><i class="fab fa-facebook-f"></i></a>';
                    break;
                case 'twitter':
                    echo '<a class="btn btn-sm btn-social btn-fill btn-twitter" href="https://twitter.com/' . $key . '"><i class="fab fa-twitter"></i></a>';
                    break;
                case 'youtube':
                    echo '<a class="btn btn-sm btn-social btn-fill btn-youtube" href="https://youtube.com/' . $key . '"><i class="fab fa-youtube"></i></a>';
                    break;
                case 'google':
                    echo '<a class="btn btn-sm btn-social btn-fill btn-google-plus" href="https://plus.google.com/' . $key . '"><i class="fab fa-google-plus-g"></i></a>';
                    break;
                case 'instagram':
                    echo '<a class="btn btn-sm btn-social btn-fill btn-instagram" href="https://instagram.com/' . $key . '"><i class="fab fa-instagram"></i></a>';
                    break;
            }
        }
    }
}

function list_languages()
{
    foreach (glob(__DIR__ . "/../../language/*.php") as $filename) {
        if (basename($filename) != "index.php") {
            $language = str_replace(".php", null, basename($filename));
            if (language_exists($language) === true) {
                echo '<a class="dropdown-item" href="?lang=' . $language . '">' . strtoupper($language) . '</a>';
            }
        }
    }
}