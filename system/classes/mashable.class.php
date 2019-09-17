<?php

class mashable
{
    function get_json($url)
    {
        $curl_content = url_get_contents($url);
        if (preg_match_all('@<script class="playerMetadata" type="application/json">(.*?)</script>@si', $curl_content, $match)) {
            return $match[1][0];
        }
    }

    function media_info($url)
    {
        $data["source"] = "mashable";
        $json = json_decode($this->get_json($url), true);
        $data["title"] = $json["player"]["title"];
        $data["thumbnail"] = $json["player"]["image"];
        $i = 0;
        foreach ($json["player"]["sources"] as $url) {
            if (preg_match_all("@/(.*?).mp4@si", $url["file"], $match)) {
                $data["links"][$i]["url"] = $url["file"];
                $data["links"][$i]["type"] = "mp4";
                $data["links"][$i]["quality"] = $match[1][1] . "P";
                $data["links"][$i]["size"] = get_file_size($data["links"][$i]["url"]);
                $i++;
            }
        }
        $data["links"] = array_reverse($data["links"]);
        return $data;
    }
}