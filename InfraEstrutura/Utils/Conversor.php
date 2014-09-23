<?php

namespace InfraEstrutura\Utils;

/**
 * Funções de conversões.
 *
 * @author Dilson José <dilsonrabelo.unasus@gmail.com>
 * @version 1.0
 */
class Conversor {
    public static function convertMD5($palavra){
        return md5(trim($palavra));
    }
    
    public static function convertSHA1($palavra){
        return sha1($palavra);
    }
}

?>
