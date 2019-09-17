<?php

class tumblr
{
    public $url;

    function media_info($url){
        $curl_content = url_get_contents($url);
        $video["title"] = $this->get_title($curl_content);
        $video["source"] = "tumblr";
        $video["thumbnail"] = $this->get_thumbnail($curl_content);
        $video["links"]["0"]["url"] = $this->get_video($this->find_username($_POST["url"]), $this->find_post_id($_POST["url"]));
        $video["links"]["0"]["type"] = "mp4";
        $video["links"]["0"]["size"] = get_file_size($video["links"]["0"]["url"]);
        $video["links"]["0"]["quality"] = "HD";
        $video["links"]["0"]["mute"] = "no";
        return $video;
    }

    function find_username($url)
    {
        $username = explode('.', str_ireplace("www.", "", parse_url($url, PHP_URL_HOST)))[0];
        return $username;
    }

    function find_post_id($url)
    {
        if (preg_match_all('/\/(post|video)\/\d{12,20}/', $url, $match)) {
            $post_id = preg_replace('/\/(post|video)\//', "", $match[0][0]);
            return $post_id;
        }
    }

    function get_video($username, $post_id)
    {
        $curl_content = url_get_contents("https://www.tumblr.com/video/" . $username . "/" . $post_id . "/700/");
        if (preg_match_all('@src="https://' . $username . '.tumblr.com/video_file/(.*?)"@si', $curl_content, $match)) {
            $video_url = str_replace("src=", "", $match[0][0]);
            $video_url = str_replace('"', "", $video_url);
            return $video_url;
        }
    }

    function get_thumbnail($curl_content)
    {
        if (preg_match_all('@<meta property="og:image" content="(.*?)" />@si', $curl_content, $match)) {
            return $match[1][0];
        } else if (preg_match_all("@poster='(.*?)'@si", $curl_content, $match)) {
            return $match[1][0];
        }
    }

    function get_title($curl_content)
    {
        if (preg_match_all('@<title>(.*?)</title>@si', $curl_content, $match)) {
            return $match[1][0];
        }
    }
}