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

    public static function log($message) {

        echo $message . PHP_EOL;

    }

}