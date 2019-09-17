<?php

class vk
{
    function media_info($url)
    {
        $curl_content = url_get_contents($url);
        if (preg_match('@"md_title":"(.*?)"@si', $curl_content, $title)) {
            $data['title'] = $title[1];
            $data['title'] = mb_convert_encoding($data['title'], 'UTF-8', 'auto');
        } else {
            $data["title"] = "VK-" . $this->generate_random_string();
        }
        if (preg_match('@"first_frame_320":"(.*?)"@si', $curl_content, $thumbnail)) {
            $data['thumbnail'] = $this->convert_url($thumbnail[1]);
        }
        if (preg_match('@"duration":(.*?),@si', $curl_content, $duration)) {
            $data['duration'] = gmdate(($duration[1] > 3600 ? "H:i:s" : "i:s"), $duration[1]);
        }
        if (preg_match_all('/"url(240|360|480|720|1080)":"https(.*?)"/', $curl_content, $matches)) {
            $i = 0;
            foreach ($matches[2] as $match) {
                $data["links"][$i]["url"] = $this->convert_url("https" . $match);
                $data["links"][$i]["type"] = "mp4";
                $data["links"][$i]["quality"] = $matches[1][$i] . "P";
                $data["links"][$i]["size"] = get_file_size($data["links"][$i]["url"]);
                $i++;
            }
        }
        $data["source"] = "vkontakte";
        return $data;
    }

    function convert_url($url)
    {
        $url = str_replace("\\", "", $url);
        $url = str_replace("&amp", "&", $url);
        $url = str_replace("&;", "&", $url);
        return $url;
    }

    function generate_random_string($length = 4)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    function check_redirect($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        $out = curl_exec($ch);
        $out = str_replace("\r", "", $out);
        $headers_end = strpos($out, "\n\n");
        if ($headers_end !== false) {
            $out = substr($out, 0, $headers_end);
        }
        $headers = explode("\n", $out);
        foreach ($headers as $header) {
            if (substr($header, 0, 10) == "Location: ") {
                $target = substr($header, 10);
                return $target;
            }
        }
        return false;
    }
}