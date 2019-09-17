<?php

class dailymotion
{
    public $url;

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

    function get_string_between($string, $start, $end)
    {
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) return '';
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }

    function find_video_id($url)
    {
        $domain = str_ireplace("www.", "", parse_url($url, PHP_URL_HOST));
        switch (true) {
            case($domain == "dai.ly"):
                $video_id = str_replace('https://dai.ly/', "", $url);
                $video_id = str_replace('/', "", $video_id);
                return $video_id;
                break;
            case($domain == "dailymotion.com"):
                $url_parts = parse_url($url);
                $path_arr = explode("/", rtrim($url_parts['path'], "/"));
                $video_id = $path_arr[2];
                return $video_id;
                break;
        }
    }

    function media_info($url)
    {
        $url = "http://www.dailymotion.com/video/" . $this->find_video_id($url);
        $data = array();
        $data['found'] = 0;
        $url_parts = parse_url($url);
        if (isset($url_parts['path'])) {
            $pathArr = explode("/", rtrim($url_parts['path'], "/"));
            if (count($pathArr) == 3 && $pathArr[1] == "video") {
                $id = $pathArr[2];
                $result = url_get_contents("https://api.dailymotion.com/video/" . $id . "?fields=title,description,thumbnail_url,duration");
                if ($result) {
                    $result = json_decode($result, true);
                    $data['found'] = 1;
                    $data['id'] = $id;
                    $title = (isset($result['title']) ? htmlentities($result['title'], ENT_QUOTES) : "Video By Dailymotion");
                    $data['title'] = $title;
                    $data['image'] = str_replace("http", "https", $result['thumbnail_url']);
                    $duration = $result['duration'];
                    $data['time'] = gmdate(($duration > 3600 ? "H:i:s" : "i:s"), $duration);
                    $format_codes = array(
                        "144" => array("order" => "1", "height" => "144", "ext" => "mp4", "resolution" => "144p", "video" => "true", "video_only" => "false"),
                        "240" => array("order" => "2", "height" => "240", "ext" => "mp4", "resolution" => "240p", "video" => "true", "video_only" => "false"),
                        "380" => array("order" => "3", "height" => "380", "ext" => "mp4", "resolution" => "380p", "video" => "true", "video_only" => "false"),
                        "480" => array("order" => "4", "height" => "480", "ext" => "mp4", "resolution" => "480p", "video" => "true", "video_only" => "false"),
                        "720" => array("order" => "5", "height" => "720", "ext" => "mp4", "resolution" => "720p", "video" => "true", "video_only" => "false"),
                        "1080" => array("order" => "6", "height" => "1080", "ext" => "mp4", "resolution" => "1080p", "video" => "true", "video_only" => "false")
                    );
                    $video_formats_data = url_get_contents("https://www.dailymotion.com/embed/video/" . $id);
                    $video_formats_data = $this->get_string_between($video_formats_data, "config = ", "}};");
                    $video_formats_data .= "}}";
                    $video_formats_data = json_decode($video_formats_data, true);
                    $streams = $video_formats_data['metadata']['qualities'];
                    $videos = array();
                    foreach ($streams as $format_id => $stream) {
                        if (array_key_exists($format_id, $format_codes)) {
                            $link = array();
                            $format_data = $format_codes[$format_id];
                            $link['data'] = $format_data;
                            $link['formatId'] = $format_id;
                            $link['order'] = $format_data['order'];
                            $link['url'] = $stream[1]['url'];
                            $link['title'] = $title . "." . $format_data['ext'];
                            $link['size'] = "unknown";
                            array_push($videos, $link);
                        }
                    }
                    $orders = array();
                    foreach ($videos as $key => $row) {
                        $orders[$key] = $row['order'];
                    }
                    array_multisort($orders, SORT_DESC, $videos);
                    $data['videos'] = $videos;
                }
            }
        }
        return $this->format_data($data);
    }

    function format_data($data)
    {
        $video["title"] = $data["title"];
        $video["source"] = "dailymotion";
        $video["thumbnail"] = $data["image"];
        $video["duration"] = $data["time"];
        $i = 0;
        foreach ($data["videos"] as $current) {
            $video["links"][$i]["url"] = $current["url"];
            $video["links"][$i]["type"] = "mp4";
            $video["links"][$i]["size"] = get_file_size($video["links"][$i]["url"]);
            $video["links"][$i]["quality"] = $current["formatId"] . "P";
            $video["links"][$i]["mute"] = "no";
            $i++;
        }
        $video["links"] = array_reverse($video["links"]);
        return $video;
    }
}