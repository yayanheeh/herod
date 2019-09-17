<?php

class buzzfeed
{
    function media_info($url)
    {
        $web_page = url_get_contents($url);
        if (preg_match('@<fb:post class="fb-post js-fb-post js-hidden" data-href="(.*?)" style="width: 100%"></fb:post>@si', $web_page, $video_url)) {
            $original_url = $video_url[1];
            include(__DIR__ . "/facebook.class.php");
            $download = new facebook();
            return $download->media_info($original_url);
        } elseif (preg_match('@"videoId": "(.*?)"@si', $web_page, $video_url)) {
            $original_url = "https://www.youtube.com/watch?v=" . $video_url[1];
            include(__DIR__ . "/youtube.class.php");
            $download = new youtube();
            return $download->media_info($original_url);
        } else {
            return false;
        }
    }
}