<?php

class database
{
    /**
     * @var PDO
     */

    public static $db;

    public static function connect($dsn, $username, $password)
    {
        self::$db = new PDO($dsn, $username, $password);
        self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        self::$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    }

    public static function row_list($table, $order_by = "ID")
    {
        $stmt = self::$db->query("SELECT * FROM '$table' ORDER BY '$order_by'", PDO::FETCH_ASSOC);
        $stmt->execute();
        $rows = $stmt->fetchAll();
        if (!empty($rows)) {
            return $rows;
        }
    }

    public static function list_pages()
    {
        $stmt = self::$db->query("SELECT * FROM contents WHERE content_type=0 ORDER BY content_name", PDO::FETCH_ASSOC);
        $stmt->execute();
        $rows = $stmt->fetchAll();
        if (!empty($rows)) {
            return $rows;
        }
    }

    public static function find($query)
    {
        $stmt = self::$db->query($query, PDO::FETCH_ASSOC);
        $stmt->execute();
        $row = $stmt->fetchAll();
        if (!empty($row)) {
            return $row;
        }
    }

    public static function find_user($email)
    {
        $stmt = self::$db->prepare("SELECT * FROM users WHERE user_email='$email' LIMIT 1");
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!empty($row)) {
            return $row;
        }
    }

    public static function find_content($content_id)
    {
        $stmt = self::$db->prepare("SELECT * FROM contents WHERE ID='$content_id' LIMIT 1");
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!empty($row)) {
            return $row;
        }
    }

    public static function slug_exists($slug)
    {
        $stmt = self::$db->query("SELECT * FROM contents WHERE content_slug='$slug'", PDO::FETCH_ASSOC);
        $stmt->execute();
        $rows = $stmt->fetchAll();
        return count($rows);
    }

    public static function check_password($email, $password)
    {
        $stmt = self::$db->prepare("SELECT * FROM users WHERE user_email='$email' AND user_pass='$password' LIMIT 1");
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!empty($row)) {
            return $row;
        } else {
            return false;
        }
    }

    public static function delete_content($content_id)
    {
        $query = self::$db->prepare("DELETE FROM contents WHERE ID=:id");
        $delete = $query->execute(array(
            'id' => $content_id
        ));
        if ($delete) {
            return true;
        } else {
            return false;
        }
    }

    public static function update_option($option_name, $option_value)
    {
        $query = self::$db->prepare("UPDATE options SET option_value=:option_value WHERE option_name=:option_name");
        $update = $query->execute(array(
            "option_name" => $option_name,
            "option_value" => $option_value
        ));
        if ($update) {
            return true;
        } else {
            return false;
        }
    }

    public static function update_content($content)
    {
        $query = self::$db->prepare("UPDATE contents SET content_text=:content_text, content_slug=:content_slug, content_name=:content_name, content_meta=:content_meta WHERE ID=:content_id");
        $update = $query->execute(array(
            "content_id" => $content["id"],
            "content_slug" => $content["slug"],
            "content_text" => $content["text"],
            "content_name" => $content["name"],
            "content_meta" => $content["meta"]
        ));
        if ($update) {
            return true;
        } else {
            return false;
        }
    }

    public static function update_password($user_email, $new_password)
    {
        $query = self::$db->prepare("UPDATE users SET user_pass=:new_password WHERE user_email=:user_email");
        $update = $query->execute(array(
            "new_password" => $new_password,
            "user_email" => $user_email
        ));
        if ($update) {
            return true;
        } else {
            return false;
        }
    }

    public static function create_content($content)
    {
        $query = self::$db->prepare("INSERT INTO contents SET content_name = ?, content_slug = ?, content_text = ?, content_type = ?, content_meta = ?");
        $insert = $query->execute(array(
            $content["name"], $content["slug"], $content["text"], $content["type"], $content["meta"]
        ));
        if ($insert) {
            $last_id = self::$db->lastInsertId();
            return true;
        } else {
            return false;
        }
    }

    public static function create_log($data)
    {
        $download_source = $data["source"];
        $download_links = $data["links"];
        unset($data["source"]);
        unset($data["links"]);
        $download_meta = $data;
        $query = self::$db->prepare("INSERT INTO downloads SET download_meta = ?, download_links = ?, download_source = ?");
        $insert = $query->execute(array(
            json_encode($download_meta), json_encode($download_links), $download_source
        ));
        if ($insert) {
            $last_id = self::$db->lastInsertId();
            return true;
        } else {
            return false;
        }
    }

    public static function allTotal($date = '')
    {
        switch ($date) {
            case 'today':
                $query = "SELECT * FROM downloads WHERE DATE(`download_date`) = CURDATE()";
                break;
            case 'yesterday':
                $query = "SELECT * FROM downloads WHERE download_date BETWEEN CURDATE() - INTERVAL 1 DAY AND CURDATE()";
                break;
            case 'this_week':
                $query = "SELECT * FROM downloads WHERE WEEK(download_date, 1) = WEEK(CURDATE(), 1)";
                break;
            case 'week':
                $query = "SELECT * FROM downloads WHERE download_date >= DATE_SUB(CURDATE(), INTERVAL 1 WEEK)";
                break;
            case 'month':
                $query = "SELECT * FROM downloads WHERE download_date >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)";
                break;
            case 'all':
                $query = "SELECT * FROM downloads";
                break;
            default:
                $query = "SELECT * FROM downloads";
                break;
        }
        $stmt = self::$db->query($query, PDO::FETCH_ASSOC);
        $stmt->execute();
        $rows = $stmt->fetchAll();
        if (!empty($rows)) {
            return count($rows);
        } else {
            return count($rows);
        }
    }

    public static function website_stats()
    {
        $stmt = self::$db->query("SELECT * FROM downloads", PDO::FETCH_ASSOC);
        $stmt->execute();
        $rows = $stmt->fetchAll();
        $website_stats = array();
        $website_stats["total"] = 0;
        foreach ($rows as $row) {
            if (!isset($website_stats[$row["download_source"]])) {
                $website_stats[$row["download_source"]] = 0;
            }
            $website_stats[$row["download_source"]] += 1;
            $website_stats["total"] += 1;
        }
        return $website_stats;
    }

    public static function formatted_stats()
    {
        $website_stats = self::website_stats();
        $i = 0;
        $stats = array();
        foreach ($website_stats as $key => $value) {
            $stats[$i]["title"] = $key;
            $stats[$i]["value"] = $value;
            $i++;
        }
        function sort_by_size($a, $b)
        {
            return $b["value"] - $a["value"];
        }

        usort($stats, 'sort_by_size');
        return $stats;
    }

    public static function monthly_stats()
    {
        $query = "SELECT download_date, COUNT(*) FROM downloads WHERE download_date >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH) GROUP BY DATE(`download_date`)";
        $stmt = self::$db->query($query, PDO::FETCH_ASSOC);
        $stmt->execute();
        $rows = $stmt->fetchAll();
        $group = array();
        $i = 0;
        foreach ($rows as $row) {
            $group[$i]["date"] = date('d/m/Y', strtotime($row["download_date"]));
            $group[$i]["count"] = $row['COUNT(*)'];
            $i++;
        }
        return $group;
    }
}