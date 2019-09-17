<?php

class flickr
{
    function find_media_id($url)
    {
        $url = $url . "/";
        if (preg_match('~/(\d{5,25})/~', $url, $match)) {
            $media_id = $match[1];
            return $media_id;
        }
    }

    function media_info($url)
    {
        $media_id = $this->find_media_id($url);
        $api_url = "https://api.flickr.com/services/rest/?method=flickr.photos.getSizes&api_key=" . option("api_key.flickr") . "&photo_id=" . $media_id . "&format=json&nojsoncallback=1";
        $rest_api = url_get_contents($api_url);
        $rest_api = json_decode($rest_api, true);
        $data["title"] = "Flickr Media: " . $media_id;
        $data["source"] = "flickr";
        $data["thumbnail"] = $rest_api["sizes"]["size"]["6"]["source"];
        if (!empty($rest_api["sizes"]["size"])) {
            $i = 0;
            foreach ($rest_api["sizes"]["size"] as $key => $media_data) {
                if ($media_data["media"] == "video") {
                    $data["links"][$i]["url"] = $media_data["source"];
                    $data["links"][$i]["type"] = "mp4";
                    $data["links"][$i]["quality"] = $media_data["label"];
                    $data["links"][$i]["size"] = get_file_size($data["links"][$i]["url"]);
                    $i++;
                }
            }
        }
        return $data;
    }
}