<?php

class facebook
{
    public $url;

    function get_domain($url)
    {
        $pieces = parse_url($url);
        $domain = isset($pieces['host']) ? $pieces['host'] : '';
        if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
            return $regs['domain'];
        } else {
            return false;
        }
    }

    function media_info($url)
    {
        $url = unshorten($this->remove_m($url));
        $curl_content = url_get_contents($url);
        $video["title"] = $this->get_title($curl_content);
        $video["source"] = "facebook";
        $video["thumbnail"] = $this->get_thumbnail($curl_content);
        $sd_link = $this->sd_link($curl_content);
        $video["links"]["0"]["url"] = $sd_link;
        $video["links"]["0"]["type"] = "mp4";
        $video["links"]["0"]["size"] = get_file_size($video["links"]["0"]["url"]);
        $video["links"]["0"]["quality"] = "SD";
        $video["links"]["0"]["mute"] = "no";
        $hd_link = $this->hd_link($curl_content);
        if (!empty($hd_link)) {
            $video["links"]["1"]["url"] = $hd_link;
            $video["links"]["1"]["type"] = "mp4";
            $video["links"]["1"]["size"] = get_file_size($video["links"]["1"]["url"]);
            $video["links"]["1"]["quality"] = "HD";
            $video["links"]["1"]["mute"] = "no";
        }
        return $video;
    }

    function change_domain($url)
    {
        $domain = $this->get_domain($url);
        $parse_url = parse_url($url);
        switch ($domain) {
            case "facebook.com":
                return "https://m.facebook.com" . $parse_url["path"] . "?" . $parse_url["query"];
                break;
            case "m.facebook.com":
                return "https://www.facebook.com" . $parse_url["path"] . "?" . $parse_url["query"];
                break;
            default:
                return "https://www.facebook.com" . $parse_url["path"] . "?" . $parse_url["query"];
                break;
        }
    }

    function clean_str($str)
    {
        return html_entity_decode(strip_tags($str), ENT_QUOTES, 'UTF-8');
    }

    function convert_url($url)
    {
        $url = str_replace("\\", "", $url);
        $url = str_replace("&amp", "&", $url);
        $url = str_replace("&;", "&", $url);
        return $url;
    }

    function remove_m($url)
    {
        $url = str_replace("m.facebook.com", "www.facebook.com", $url);
        return $url;
    }

    function mobil_link($curl_content)
    {
        $regex = '@&quot;https:(.*?)&quot;,&quot;@si';
        if (preg_match_all($regex, $curl_content, $match)) {
            return $match[1][0];
        }
    }

    function hd_link($curl_content)
    {
        $regex = '/hd_src_no_ratelimit:"([^"]+)"/';
        if (preg_match($regex, $curl_content, $match)) {
            return $match[1];
        } else if (preg_match('/hd_src:"([^"]+)"/', $curl_content, $match)) {
            return $match[1];
        }
    }

    function sd_link($curl_content)
    {
        $regex = '/sd_src_no_ratelimit:"([^"]+)"/';
        if (preg_match($regex, $curl_content, $match)) {
            return $match[1];
        } else {
            $mobil_link = $this->mobil_link($curl_content);
            if (!empty($mobil_link)) {
                return $mobil_link;
            }
        }
    }

    function get_title($curl_content)
    {
        if (preg_match('@<meta name="description" content="(.*?)" />@si', $curl_content, $match)) {
            return $match[1];
        }
    }

    function get_thumbnail($curl_content)
    {
        if (preg_match('/og:image"\s*content="([^"]+)"/', $curl_content, $match)) {
            return $match[1];
        }
    }

    function get_duration($curl_content)
    {
        if (preg_match('@<img class="_4lpf" src="(.*?)" />@si', $curl_content, $match)) {
            return $match[1];
        }
    }
}