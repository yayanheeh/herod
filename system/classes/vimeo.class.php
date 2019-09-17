<?php

class vimeo
{
    public $url;

    function media_info($url)
    {
        $web_page = url_get_contents($url);
        if (preg_match_all('/window.vimeo.clip_page_config.player\s*=\s*({.+?})\s*;\s*\n/', $web_page, $match)) {
            $config_url = json_decode($match[1][0], true)["config_url"];
            $result = json_decode(url_get_contents($config_url), true);
            $video['title'] = $result["video"]["title"];
            $video["source"] = "vimeo";
            $video['duration'] = gmdate(($result["video"]["duration"] > 3600 ? "H:i:s" : "i:s"), $result["video"]["duration"]);
            $video['thumbnail'] = reset($result["video"]["thumbs"]);
            $i = 0;
            foreach ($result["request"]["files"]["progressive"] as $current) {
                $video["links"][$i]["url"] = $current["url"];
                $video["links"][$i]["type"] = "mp4";
                $video["links"][$i]["size"] = get_file_size($video["links"][$i]["url"]);
                $video["links"][$i]["quality"] = $current["quality"];
                $video["links"][$i]["mute"] = "no";
                $i++;
            }
            $video["links"] = array_reverse($video["links"]);
            return $video;
        }
    }

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
        if (preg_match_all('/https:\/\/vimeo.com\/(channels|([^"]+))(\/staffpicks\/([^"]+)|)/', $url, $match)) {
            if (is_numeric($match[1][0])) {
                return $match[1][0];
            } else if (is_numeric($match[4][0])) {
                return $match[4][0];
            }
        }
    }

    function api_request($video_id)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://vimeo.com/$video_id?action=load_contextual_clips&page=1&stream_pos=0&offset=0",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => "",
            CURLOPT_HTTPHEADER => array(
                "X-Requested-With: XMLHttpRequest",
                "cache-control: no-cache"
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            return '';
        } else {
            return json_decode($response, true)["clips"][0];
        }
    }

    function media_info_old($url)
    {
        $data = array();
        $data['found'] = 0;
        $id = $this->find_video_id($url);
        if (is_numeric($id)) {
            $result = url_get_contents("https://vimeo.com/api/v2/video/" . $id . ".json");
            if ($result != '') {
                $result = json_decode($result, true);
                $data['id'] = $id;
                $data['found'] = 1;
                $title = (isset($result[0]['title']) ? htmlentities($result[0]['title'], ENT_QUOTES) : "Video By Vimeo");
                $data['title'] = $title;
                $data['image'] = (isset($result[0]['thumbnail_large']) ? $result[0]['thumbnail_large'] : $result[0]['thumbnail_medium']);
                $duration = $result[0]['duration'];
                $data['time'] = gmdate(($duration > 3600 ? "H:i:s" : "i:s"), $duration);
                $format_codes = array(
                    "270p" => array("order" => "1", "height" => "270", "ext" => "mp4", "resolution" => "270p", "video" => "true", "video_only" => "false"),
                    "360p" => array("order" => "2", "height" => "360", "ext" => "mp4", "resolution" => "360p", "video" => "true", "video_only" => "false"),
                    "540p" => array("order" => "3", "height" => "540", "ext" => "mp4", "resolution" => "540p", "video" => "true", "video_only" => "false"),
                    "720p" => array("order" => "4", "height" => "720", "ext" => "mp4", "resolution" => "720p", "video" => "true", "video_only" => "false"),
                    "1080p" => array("order" => "5", "height" => "1080", "ext" => "mp4", "resolution" => "1080p", "video" => "true", "video_only" => "false")
                );
                $video_formats_data = url_get_contents("https://player.vimeo.com/video/" . $id);
                $video_formats_data = $this->get_string_between($video_formats_data, '"request":{"files":', ',"lang":"en"');
                $video_formats_data = json_decode($video_formats_data, true);
                $videoStreams = $video_formats_data['progressive'];
                $videos = array();
                foreach ($videoStreams as $stream) {
                    $formatId = $stream['quality'];
                    if (array_key_exists($formatId, $format_codes)) {
                        $link = array();
                        $format_data = $format_codes[$formatId];
                        $link['data'] = $format_data;
                        $link['formatId'] = $formatId;
                        $link['order'] = $format_data['order'];
                        $link['url'] = $stream['url'];
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
        return $this->format_data($data);
    }

    function format_data($data)
    {
        $video["title"] = $data["title"];
        $video["source"] = "vimeo";
        $video["thumbnail"] = $data["image"];
        $video["duration"] = $data["time"];
        $i = 0;
        foreach ($data["videos"] as $current) {
            $video["links"][$i]["url"] = $current["url"];
            $video["links"][$i]["type"] = "mp4";
            $video["links"][$i]["size"] = get_file_size($video["links"][$i]["url"]);
            $video["links"][$i]["quality"] = $current["formatId"];
            $video["links"][$i]["mute"] = "no";
            $i++;
        }
        $video["links"] = array_reverse($video["links"]);
        return $video;
    }
}