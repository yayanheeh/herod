<?php

class tiktok
{
    function media_info($url)
    {
        $web_page = url_get_contents($url);
        preg_match_all('/\bdata\s*=\s*({.+?})\s*;/', $web_page, $match);
        $data = json_decode($match[1][0], true);
        $video["source"] = "tiktok";
        $video["title"] = $data["share_info"]["share_title"];
        $video["thumbnail"] = $data["video"]["cover"]["preview_url"];
        $video["links"][0]["url"] = "https:" . $data["video"]["play_addr"]["url_list"][0];
        $video["links"][0]["type"] = "mp4";
        $video["links"][0]["size"] = get_file_size($video["links"][0]["url"]);
        $video["links"][0]["quality"] = "hd";
        $video["links"][0]["mute"] = "no";
        return $video;
    }
}