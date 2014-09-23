<?php

namespace InfraEstrutura\Utils;

/**
 * Description of ArrayUtil
 *
 * @author dilson
 */
class ArrayUtil {

    public static function toListArrayHtml(array $array) {
        $out = "<ul>";
        
        foreach ($array as $var) {
            if (is_array($var)) {
                $out .= '<ul>' . self::toListArrayHtml($var) . '</ul>';
            } else {
                $out .= "<li>" . $var . "</li>";
            }
        }
        
        return $out . "</ul>";
    }
    
}

?>
