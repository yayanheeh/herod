<?php

class ninegag
{
    public function get_string_between($string, $start, $end)
    {
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) return '';
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }

    public function media_info($url)
    {
        $path = parse_url($url, PHP_URL_PATH);
        $pieces = explode('/', $path);
        $id = $pieces[2];
        $result = url_get_contents($url);
        $video_HD_link = "https://img-9gag-fun.9cache.com/photo/" . $id . "_460sv.mp4";
        $video_SD_link = "https://img-9gag-fun.9cache.com/photo/" . $id . "_460svwm.webm";
        if ($video_SD_link) {
            $data['found'] = 1;
            $data['id'] = $id;
            $links = array();
            $links['SD'] = $video_SD_link;
            if (!empty($video_HD_link)) {
                $links['HD'] = $video_HD_link;
            }
            $image = "http://images-cdn.9gag.com/photo/" . $id . "_460s.jpg";
            $data['image'] = $image;
            if ($result) {
                $title = $this->get_string_between($result, '<meta property="og:title" content="', '" />');;
                $data['title'] = $title;
            }
            $format_codes = array(
                "SD" => array("order" => "1", "height" => "{{height}}", "ext" => "mp4", "resolution" => "SD", "video" => "true", "video_only" => "false"),
                "HD" => array("order" => "2", "height" => "{{height}}", "ext" => "mp4", "resolution" => "HD", "video" => "true", "video_only" => "false")
            );
            $videos = array();
            foreach ($format_codes as $format_id => $format_data) {
                if (isset($links[$format_id])) {
                    $link = array();
                    $link['data'] = $format_data;
                    $link['formatId'] = $format_id;
                    $link['order'] = $format_data['order'];
                    $link['url'] = $links[$format_id];
                    $link['title'] = $title . "." . $format_data['ext'];
                    array_push($videos, $link);
                }
            }
            $data['videos'] = $videos;
        }
        $media_info = $data;
        $video["source"] = "9gag";
        $video["title"] = $media_info["title"];
        $video["thumbnail"] = $media_info["image"];
        $i = 0;
        foreach ($media_info["videos"] as $current) {
            $video["links"][$i]["url"] = $current["url"];
            $video["links"][$i]["type"] = "mp4";
            $video["links"][$i]["size"] = get_file_size($video["links"][$i]["url"]);
            $video["links"][$i]["quality"] = $current["formatId"];
            $video["links"][$i]["mute"] = "no";
            $i++;
        }
        return $video;
    }
}