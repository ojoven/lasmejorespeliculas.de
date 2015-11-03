<?php

namespace App\Lib;

class Functions {

    // URLs
    public static function redirectIfFacebook() {

        if(isset($_GET['_escaped_fragment_'])) {
            Header( "HTTP/1.1 301 Moved Permanently" );
            header('Location: http://'.$_SERVER['HTTP_HOST'].$_GET['_escaped_fragment_']);
            die();
        }
    }

    // Parse strings
    public static function createDescriptionForResult($result) {

        $description = "Mira el listado con las mejores películas de " . $result['name'] . ": ";
        $films = $result['films'];
        foreach ($films as $index=>$film) {
            $description .= $film['name'] . " (" . $film['year'] . ") " . $film['rating'];
            if ($index < count($films) - 1) $description .= ", ";
        }

        return $description;

    }

    public static function getLastCronDate() {

        $date = file_get_contents(base_path() . "/scraper/last");
        $ago = ($date) ? Functions::ago($date) : false;

        return $ago;
    }

    public static function ago($date) {

        $str = $date;
        $today = strtotime(date('Y-m-d H:i:s'));

        $time_differnce = $today - $str;
        $years = 60 * 60 * 24 * 365;
        $months = 60 * 60 * 24 * 30;
        $days = 60 * 60 * 24;
        $hours = 60 * 60;
        $minutes = 60;

        if (intval($time_differnce / $years) > 1) {
            $datediff = 'hace ' . intval($time_differnce / $years) . ' años';
        } else if (intval($time_differnce / $years) > 0) {
            $datediff = 'hace ' . intval($time_differnce / $years) . ' año';
        } else if (intval($time_differnce / $months) > 1) {
            $datediff = 'hace ' . intval($time_differnce / $months) . ' meses';
        } else if (intval(($time_differnce / $months)) > 0) {
            $datediff = 'hace ' . intval(($time_differnce / $months)) . ' mes';
        } else if (intval(($time_differnce / $days)) > 1) {
            $datediff = 'hace ' . intval(($time_differnce / $days)) . ' días';
        } else if (intval(($time_differnce / $days)) > 0) {
            $datediff = 'hace ' . intval(($time_differnce / $days)) . ' día';
        } else if (intval(($time_differnce / $hours)) > 1) {
            $datediff = 'hace ' . intval(($time_differnce / $hours)) . ' horas';
        } else if (intval(($time_differnce / $hours)) > 0) {
            $datediff = 'hace ' . intval(($time_differnce / $hours)) . ' hora';
        } else if (intval(($time_differnce / $minutes)) > 1) {
            $datediff = 'hace ' . intval(($time_differnce / $minutes)) . ' minutos';
        } else if (intval(($time_differnce / $minutes)) > 0) {
            $datediff = 'hace ' . intval(($time_differnce / $minutes)) . ' minuto';
        } else if (intval(($time_differnce)) > 1) {
            $datediff = 'hace ' . intval(($time_differnce)) . ' segundos';
        } else {
            $datediff = 'hace unos segundos';
        }

        return $datediff;
    }

}