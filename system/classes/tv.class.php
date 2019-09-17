<?php

class tv
{
    function media_info($url)
    {
        $web_page = url_get_contents($url);
        if (preg_match_all('@<meta property="og:title" content="(.*?)"/>@si', $web_page, $match)) {
            $data["title"] = $match[1][0];
        }
        if (preg_match_all('@<meta property="og:image" content="(.*?)"/>@si', $web_page, $match)) {
            $data["thumbnail"] = $match[1][0];
        }
        $data["source"] = "tv.com";
        if (preg_match_all('@<iframe width="770" alt="" frameborder="0" src="(.*?)" height="433"></iframe>@si', $web_page, $match)) {
            $embed_url = $match[1][0];
        }
        $embed_page = url_get_contents($embed_url);
        if (preg_match_all("@{(.*?)};@si", $embed_page, $match)) {
            $video_data = json_decode("{" . $match[1][5] . "}", true);
            $quality[3] = "360p";
            $quality[2] = "480p";
            $quality[1] = "720p";
            $quality[0] = "1080p";
            $i = 0;
            foreach ($video_data["files"]["MPEG4"] as $key => $value) {
                if ($key != "HDS") {
                    foreach ($value as $video) {
                        $data["links"][$i]["url"] = $video["url"];
                        $data["links"][$i]["type"] = "mp4";
                        $data["links"][$i]["size"] = get_file_size($data["links"][$i]["url"]);
                        $data["links"][$i]["quality"] = $quality[$i];
                        $data["links"][$i]["mute"] = "no";
                        $i++;
                    }
                }
            }
            $data["links"] = array_reverse($data["links"]);
        }
        return $data;
    }
}