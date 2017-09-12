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

    public static function getURL($url, $params = array()) {

        if ($params) {
            $url .= '?' . http_build_query($params);
        }

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');

        $output = curl_exec($ch);
        curl_close ($ch);

        return $output;

    }

    public static function getURLBodyAndHeaders($url, $params) {

        $url .= '?' . http_build_query($params);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);

        $output = curl_exec($ch);

        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $response['headers'] = substr($output, 0, $header_size);
        $response['body'] = substr($output, $header_size);

        curl_close ($ch);

        return $response;
    }

    public static function log($message) {

        if (is_string($message)) {
            echo $message . PHP_EOL;
        } else {
            print_r($message);
        }

    }

    /** DB **/
    public static function selectDB($db, $query) {

        $result = $db->query($query);
        return $result;

    }

    public static function insertDB($db, $tableName, $data) {

        $dataParsed = self::implodeindexesvalues($data);
        $query = "INSERT INTO " . $tableName . " (" . $dataParsed['indexes'] . ") VALUES (" . $dataParsed['values'] . ")";
        $db->query($query);

    }

    public static function insertMultipleDB($db, $tableName, $indexes, $data) {

        $query = "INSERT INTO " . $tableName . " (" . implode(",", $indexes) . ") VALUES ";
        foreach ($data as $value) {
            $query .= "(" . self::addQuotesAndImplode($value) . "),";
        }
        $query = rtrim($query, ",");

        $db->query($query);

    }

    public static function addQuotesAndImplode($array) {

        foreach ($array as &$element) {
            $element = "'" . iconv("UTF-8", "CP1252", self::escapeSingleQuotes($element)) . "'";
        }

        return implode(",", $array);

    }


    public static function implodeindexesvalues($data) {
        $indexes = "";
        $values = "";
        foreach ($data as $index=>$value) {
            $indexes .= $index . ",";
            if (is_string($value)) {
                $value = self::escapeSingleQuotes($value);
            }
            $values .= "'". iconv("UTF-8", "CP1252", $value) . "',";
        }
        $dataParsed['indexes'] = rtrim($indexes, ',');
        $dataParsed['values'] = rtrim($values, ',');
        return $dataParsed;

    }

    public static function escapeSingleQuotes($string) {
        return str_replace("'","\'",str_replace("\'","'",$string));
    }

}