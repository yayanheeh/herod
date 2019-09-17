<?php

class instagram
{
    public $url;

    function media_info($url)
    {
        $curl_content = url_get_contents($url);
        $media_info = $this->media_data($url);
        $video["title"] = $this->get_title($curl_content);
        $video["source"] = "instagram";
        $video["thumbnail"] = $this->get_thumbnail($curl_content);
        $i = 0;
        foreach ($media_info["links"] as $link) {
            switch ($link["type"]) {
                case "video":
                    $video["links"][$i]["url"] = $link["url"];
                    $video["links"][$i]["type"] = "mp4";
                    $video["links"][$i]["size"] = get_file_size($video["links"]["0"]["url"]);
                    $video["links"][$i]["quality"] = "HD";
                    $video["links"][$i]["mute"] = "no";
                    $i++;
                    break;
                case "image":
                    $video["links"][$i]["url"] = $link["url"];
                    $video["links"][$i]["type"] = "jpg";
                    $video["links"][$i]["size"] = get_file_size($video["links"]["0"]["url"]);
                    $video["links"][$i]["quality"] = "HD";
                    $video["links"][$i]["mute"] = "yes";
                    $i++;
                    break;
                default:
                    break;
            }
        }
        return $video;
    }

    function media_data($url)
    {
        $scrape = url_get_contents($url);
        preg_match_all('/window._sharedData = (.*);/', $scrape, $matches);
        if (!$matches) {
            return false;
        } else {
            $json = $matches[1][0];
            $data = json_decode($json, true);
            if ($data['entry_data']['PostPage'][0]['graphql']['shortcode_media']['__typename'] == "GraphImage") {
                $imagesdata = $data['entry_data']['PostPage'][0]['graphql']['shortcode_media']['display_resources'];
                $length = count($imagesdata);
                $media_info['links'][0]['type'] = 'image';
                $media_info['links'][0]['url'] = $imagesdata[$length - 1]['src'];
                $media_info['links'][0]['status'] = 'success';
            } else {
                if ($data['entry_data']['PostPage'][0]['graphql']['shortcode_media']['__typename'] == "GraphSidecar") {
                    $counter = 0;
                    $multipledata = $data['entry_data']['PostPage'][0]['graphql']['shortcode_media']['edge_sidecar_to_children']['edges'];
                    foreach ($multipledata as &$media) {
                        if ($media['node']['is_video'] == "true") {
                            $media_info['links'][$counter]["url"] = $media['node']['video_url'];
                            $media_info['links'][$counter]["type"] = 'video';
                        } else {
                            $length = count($media['node']['display_resources']);
                            $media_info['links'][$counter]["url"] = $media['node']['display_resources'][$length - 1]['src'];
                            $media_info['links'][$counter]["type"] = 'image';
                        }
                        $counter++;
                        $media_info['type'] = 'media';
                    }
                    $media_info['status'] = 'success';
                } else {
                    if ($data['entry_data']['PostPage'][0]['graphql']['shortcode_media']['__typename'] == "GraphVideo") {
                        $videolink = $data['entry_data']['PostPage'][0]['graphql']['shortcode_media']['video_url'];
                        $media_info['links'][0]['type'] = 'video';
                        $media_info['links'][0]['url'] = $videolink;
                        $media_info['links'][0]['status'] = 'success';
                    } else {
                        $media_info['links']['status'] = 'fail';
                    }
                }
            }
            $owner = $data['entry_data']['PostPage'][0]['graphql']['shortcode_media']['owner'];
            $media_info['username'] = $owner['username'];
            $media_info['full_name'] = $owner['full_name'];
            $media_info['profile_pic_url'] = $owner['profile_pic_url'];
            return $media_info;
        }
    }

    function get_type($curl_content)
    {
        if (preg_match_all('@<meta property="og:type" content="(.*?)" />@si', $curl_content, $match)) {
            return $match[1][0];
        }
    }

    function get_image($curl_content)
    {
        if (preg_match_all('@<meta property="og:image" content="(.*?)" />@si', $curl_content, $match)) {
            return $match[1][0];
        }

    }

    function get_video($curl_content)
    {

        if (preg_match_all('@<meta property="og:video" content="(.*?)" />@si', $curl_content, $match)) {
            return $match[1][0];
        }

    }

    function get_thumbnail($curl_content)
    {
        if (preg_match_all('@<meta property="og:image" content="(.*?)" />@si', $curl_content, $match)) {
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