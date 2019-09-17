<?php

class ted
{
    function get_json($url)
    {
        $curl_content = url_get_contents($url);
        if (preg_match_all('@"__INITIAL_DATA__":(.*?)\}\)</script>@si', $curl_content, $match)) {
            return $match[1][0];
        }
    }

    function media_info($url){
        $json = json_decode($this->get_json($url), true);
        $data["source"] = "ted";
        $data["title"] = $json["name"];
        $data["thumbnail"] = $json["talks"][0]["hero"];
        $data["duration"] = gmdate(($json["talks"][0]["duration"] > 3600 ? "H:i:s" : "i:s"), $json["talks"][0]["duration"]);
        $i = 0;
        foreach ($json["talks"][0]["downloads"]["nativeDownloads"] as $quality => $url){
            $data["links"][$i]["url"] = $url;
            $data["links"][$i]["type"] = "mp4";
            $data["links"][$i]["quality"] = $quality;
            $data["links"][$i]["size"] = get_file_size($data["links"][$i]["url"]);
            $i++;
        }
        $data["links"][$i]["url"] = unshorten($json["talks"][0]["downloads"]["audioDownload"]);
        $data["links"][$i]["type"] = "mp3";
        $data["links"][$i]["quality"] = "128 Kbps";
        $data["links"][$i]["size"] = get_file_size($data["links"][$i]["url"]);
        return $data;
    }
}