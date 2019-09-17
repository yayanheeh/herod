<?php

class odnoklassniki
{
    function media_info($url)
    {
        $web_page = url_get_contents($url);
        if (preg_match_all('@data-module="OKVideo" data-options="(.*?)"@si', $web_page, $match)) {
            return $match;
        }
    }
}