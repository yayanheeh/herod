<?php

class soundcloud
{
    function base_url()
    {
        return sprintf(
            "%s://%s%s",
            isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
            $_SERVER['SERVER_NAME'],
            $_SERVER['REQUEST_URI']
        );
    }

    function truncate($string, $length)
    {
        $string = trim(strip_tags($string));
        if (strlen($string) > $length) {
            $string = substr($string, 0, $length) . '...';
        }
        return $string;
    }

    function media_info($url)
    {
        $data = array();
        if (!empty(option("api_key.soundcloud"))) {
            $data['found'] = 0;
            $result = url_get_contents("https://api.soundcloud.com/resolve.json?url=" . $url . "&client_id=" . option("api_key.soundcloud"));
            if ($result) {
                $result = json_decode($result, true);
                if (isset($result['kind']) && $result['kind'] == "track") {
                    $data['id'] = $result['id'];
                    $data['found'] = 1;
                    $title = (isset($result['title']) ? htmlentities($result['title'], ENT_QUOTES) : "Track By Soundcloud");
                    $data['title'] = $title;
                    if (empty($result['artwork_url'])) {
                        $data['image'] = config("url") . '/assets/img/soundcloud.png';
                    } else {
                        $data['image'] = str_replace("large", "t300x300", $result['artwork_url']);
                    }
                    $duration = $result['duration'] / 1000;
                    $data['time'] = gmdate(($duration > 3600 ? "H:i:s" : "i:s"), $duration);
                    $stream_links = url_get_contents("https://api.soundcloud.com/i1/tracks/" . $result['id'] . "/streams?client_id=" . option("api_key.soundcloud"));
                    $stream_links = json_decode($stream_links, true);
                    $download_url = $stream_links['http_mp3_128_url'];
                    $audios = array();
                    $formatId = "http_mp3_128_url";
                    $link = array();
                    $format_data = array();
                    $format_data['order'] = 1;
                    $format_data['height'] = "empty";
                    $format_data['ext'] = "mp3";
                    $format_data['resolution'] = "Original";
                    $format_data['video'] = "false";
                    $format_data['video_only'] = "false";
                    $link['data'] = $format_data;
                    $link['formatId'] = $formatId;
                    $link['order'] = 1;
                    $link['url'] = $download_url;
                    $link['title'] = $title . "." . $format_data['ext'];
                    $link['size'] = "unknown";
                    array_push($audios, $link);
                    $data['audios'] = $audios;
                    $video["title"] = $title;
                    $video["source"] = "soundcloud";
                    $video["thumbnail"] = $data["image"];
                    $video["duration"] = $data["time"];
                    $i = 0;
                    foreach ($data["audios"] as $current) {
                        $video["links"][$i]["url"] = $current["url"];
                        $video["links"][$i]["type"] = "mp3";
                        $video["links"][$i]["size"] = get_file_size($video["links"][$i]["url"]);
                        $video["links"][$i]["quality"] = "128 kbps";
                        $video["links"][$i]["mute"] = "no";
                        $i++;
                    }
                    return $video;
                }
            }
        }
    }
}