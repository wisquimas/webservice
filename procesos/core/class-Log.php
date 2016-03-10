<?php

namespace Wisquimas;

class Log{
    public static function write_Log( $texto ){
        $texto.='
';
        file_put_contents(__DIR__.DIRECTORY_SEPARATOR.'log.txt', $texto ,FILE_APPEND);
    }

}
