<?php

class imdb
{
    function orderArray($arrayToOrder, $keys)
    {
        $ordered = array();
        foreach ($keys as $key) {
            if (isset($arrayToOrder[$key])) {
                $ordered[$key] = $arrayToOrder[$key];
            }
        }
        return $ordered;
    }

    function media_info($url)
    {
        preg_match_all('/vi\d{5,20}/m', $url, $video_id, PREG_SET_ORDER, 0);
        $player_url = "https://imdb.com/videoplayer/" . $video_id[0][0];
        $player_page = url_get_contents($player_url);
        $data = array();
        preg_match_all('/window\.IMDbReactInitialState\.push\(({.+?})\);/', $player_page, $player_json);
        $player_json = json_decode($player_json[1][0], true);
        $video_data = array_values($player_json["videos"]["videoMetadata"])[0];
        $data["title"] = $video_data["title"];
        $data["duration"] = $video_data["duration"];
        $data["thumbnail"] = $video_data["slate"]["url"];
        if (!empty($video_data["encodings"])) {
            $i = 0;
            foreach ($video_data["encodings"] as $video) {
                if ($video["mimeType"] == "video/mp4") {
                    $data["links"][$i]["url"] = $video["videoUrl"];
                    $data["links"][$i]["type"] = "mp4";
                    $data["links"][$i]["size"] = get_file_size($data["links"][$i]["url"]);
                    if ($video["definition"] == "SD") {
                        $data["links"][$i]["quality"] = "360p";
                    } else {
                        $data["links"][$i]["quality"] = $video["definition"];
                    }
                    $data["links"][$i]["mute"] = "no";
                    $i++;
                }
            }
        }
        function sort_by_order($a, $b)
        {
            $a['size'] = (int)filter_var($a['size'], FILTER_SANITIZE_NUMBER_INT);
            $b['size'] = (int)filter_var($b['size'], FILTER_SANITIZE_NUMBER_INT);
            return $a['size'] - $b['size'];
        }

        usort($data["links"], "sort_by_order");
        $data["source"] = "imdb";
        return $data;
    }
}