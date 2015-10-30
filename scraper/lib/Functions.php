<?php

class Functions {

    public static function postURL($url, $params) {

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $output = curl_exec($ch);
        curl_close ($ch);

        return $output;
    }

    public static function getURL($url, $params) {

        $url .= '?' . http_build_query($params);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $output = curl_exec($ch);
        curl_close ($ch);

        return $output;

    }

    public static function log($message) {

        echo $message . PHP_EOL;

    }

    /** DB **/
    public static function insertDB($db, $tableName, $data) {

        $dataParsed = self::implodeindexesvalues($data);
        $query = "INSERT INTO " . $tableName . " (" . $dataParsed['indexes'] . ") VALUES (" . $dataParsed['values'] . ")";
        $db->query($query);

    }


    public static function implodeindexesvalues($data) {
        $indexes = "";
        $values = "";
        foreach ($data as $index=>$value) {
            $indexes .= $index . ",";
            if (is_string($value)) {
                $value = self::escapeSingleQuotes($value);
            }
            $values .= "'". $value . "',";
        }
        $dataParsed['indexes'] = rtrim($indexes, ',');
        $dataParsed['values'] = rtrim($values, ',');
        return $dataParsed;

    }

    public static function escapeSingleQuotes($string) {
        return str_replace("'","\'",str_replace("\'","'",$string));
    }

}