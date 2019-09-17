<?php

class liveleak
{
    function media_info($url)
    {
        $curl_content = url_get_contents($url);
        if (preg_match_all('<meta property="og:description" content="(.*?)"/>', $curl_content, $match)) {
            $data["title"] = $match[1][0];
        }
        if (preg_match_all('<meta property="og:image" content="(.*?)"/>', $curl_content, $match)) {
            $data["thumbnail"] = $match[1][0];
        }
        if (preg_match_all('/src="(.*?)" (default|) label="(720p|360p|HD|SD)"/', $curl_content, $matches)) {
            $i = 0;
            foreach ($matches[1] as $match) {
                    $data["links"][$i]["url"] = $match;
                    $data["links"][$i]["type"] = "mp4";
                    $data["links"][$i]["quality"] = $matches[3][$i];
                    $data["links"][$i]["size"] = get_file_size($data["links"][$i]["url"]);
                    $i++;
            }
        }
        $data["links"] = array_reverse($data["links"]);
        $data["source"] = "liveleak";
        return $data;
    }
}