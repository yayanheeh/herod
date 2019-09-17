<?php

class logs extends database
{
    public static function list_downloads(){
        $stmt = self::$db->query("SELECT * FROM downloads ORDER BY download_date DESC", PDO::FETCH_ASSOC);
        $stmt->execute();
        $rows = $stmt->fetchAll();
        if (!empty($rows)) {
            return $rows;
        }
    }
}