<?php

namespace App;

class Log {
    static function record($m)
    {
        if( true === WP_DEBUG ){
            if( is_array($m) || is_object($m) ){
                error_log(print_r($m, true));
            } else {
                error_log($m);
            }
        }
    }
}
