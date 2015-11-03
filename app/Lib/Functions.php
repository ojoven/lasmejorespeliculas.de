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

}