<?php

namespace App\Lib;

class Functions {

    public static function redirectIfFacebook() {

        if(isset($_GET['_escaped_fragment_'])) {
            Header( "HTTP/1.1 301 Moved Permanently" );
            header('Location: http://'.$_SERVER['HTTP_HOST'].$_GET['_escaped_fragment_']);
            die();
        }
    }

}