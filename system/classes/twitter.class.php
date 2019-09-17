<?php

class twitter
{
    public $url;

    function get_api($url)
    {
        $ch = curl_init();
        $headers = array();
        $headers[] = 'x-guest-token: 1006888461029269504';
        $headers[] = 'Authorization: Bearer AAAAAAAAAAAAAAAAAAAAAIK1zgAAAAAA2tUWuhGZ2JceoId5GwYWU5GspY4%3DUq7gzFoCZs1QfwGoVdvSac3IniczZEYXIcDyumCauIXpcAPorE';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
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

    function find_media_id($url)
    {
        $domain = str_ireplace("www.", "", parse_url($url, PHP_URL_HOST));
        switch ($domain) {
            case "twitter.com":
                $arr = explode("/", $url);
                return end($arr);
                break;
            case "mobile.twitter.com":
                $arr = explode("/", $url);
                return end($arr);
                break;
            default:
                $arr = explode("/", $url);
                return end($arr);
                break;
        }
    }

    function _media_info($url)
    {
        $tweet_id = $this->find_media_id($url);
        $api_url = "https://api.twitter.com/1.1/videos/tweet/config/" . $tweet_id . ".json";
        $web_page = $this->get_api($api_url);
        $tweet_data = json_decode($web_page, true);
        $data["title"] = $this->get_title($tweet_data["track"]["expandedUrl"]);
        $data["thumbnail"] = $tweet_data["posterImage"];
        $data["links"]["0"]["url"] = $tweet_data["track"]["playbackUrl"];
        $data["links"]["0"]["type"] = "mp4";
        $data["links"]["0"]["size"] = get_file_size($data["links"]["0"]["url"]);
        $data["links"]["0"]["quality"] = "HD";
        $data["links"]["0"]["mute"] = "no";
        $data["source"] = "twitter";
        return $data;
    }

    function media_info($url)
    {
        $curl_content = url_get_contents(strtok($url, '?'));
        $query_str = parse_url($url, PHP_URL_QUERY);
        parse_str($query_str, $query);
        $video["title"] = $this->get_title($curl_content);
        $video["source"] = "twitter";
        $video["thumbnail"] = $this->get_thumbnail($curl_content);
        $i = 0;
        foreach ($query as $key => $value) {
            if (!empty($value) && $value != "undefined") {
                $video["links"][$i]["url"] = $value;
                $video["links"][$i]["type"] = "mp4";
                $video["links"][$i]["size"] = get_file_size($video["links"][$i]["url"]);
                $video["links"][$i]["quality"] = $this->get_quality($value);
                $video["links"][$i]["mute"] = "no";
                $i++;
            }
        }
        function sort_by_size($a, $b)
        {
            return get_file_size($a["url"], false) - get_file_size($b["url"], false);
        }

        usort($video["links"], 'sort_by_size');
        return $video;
    }

    function convert_url($url)
    {
        $url = str_replace("\\", "", $url);
        $url = str_replace("&amp", "&", $url);
        $url = str_replace("&;", "&", $url);
        return $url;
    }

    function get_title($curl_content)
    {
        if (preg_match('@<meta  property="og:description" content="(.*?)">@si', $curl_content, $match)) {
            return $match[1];
        } else if (preg_match('@<div class="tweet-text" data-id="(.*?)">(.*?)</div>@si', $curl_content, $match)) {
            $str = preg_replace('#<a.*?>.*?</a>#i', '', $match[0]);
            $str = strip_tags($str);
            $str = trim(preg_replace('/\s+/', ' ', $str));
            return $str;
        } else if (preg_match('@<title>(.*?)</title>">@si', $curl_content, $match)) {
            return $match;
        } else {
            return "Twitter Video";
        }
    }

    function _get_title($url)
    {
        $curl_content = url_get_contents($url);
        if (preg_match('@<meta  property="og:description" content="(.*?)">@si', $curl_content, $match)) {
            return $match[1];
        } else if (preg_match('@<div class="tweet-text" data-id="(.*?)">(.*?)</div>@si', $curl_content, $match)) {
            $str = preg_replace('#<a.*?>.*?</a>#i', '', $match[0]);
            $str = strip_tags($str);
            $str = trim(preg_replace('/\s+/', ' ', $str));
            return $str;
        } else if (preg_match('@<title>(.*?)</title>">@si', $curl_content, $match)) {
            return $match;
        } else {
            return "Twitter Video";
        }
    }

    function get_thumbnail($curl_content)
    {
        if (preg_match('@<meta  property="og:image" content="(.*?)">@si', $curl_content, $match)) {
            return $match[1];
        } else if (preg_match('@<img src="(.*?):small"/>@si', $curl_content, $match)) {
            return $match[1];
        } else {
            return "template/start/assets/img/placeholder.png";
        }
    }

    function get_video($curl_content)
    {
        if (preg_match('@<meta  property="og:video:url" content="(.*?)">@si', $curl_content, $match)) {
            $curl_content = $this->convert_url(url_get_contents($match[1]));
            if (preg_match('@https://video.twimg.com/tweet_video/(.*?).mp4@si', $curl_content, $match)) {
                return "https://video.twimg.com/tweet_video/" . $match[1] . ".mp4";
            }
        }
    }

    function get_quality($url)
    {
        preg_match('@pu/vid/(.*?)/@si', $url, $resolution);
        if (!empty($resolution[1])) {
            return $resolution[1];
        } else {
            return "gif";
        }
    }
}