<?php

/**
 * @Auteur Robert de Jong
 * @Datum 13-5-2013
 */
 
class validator{
    
    static function isInt($getal){
        return is_numeric($getal);
    }
    
    static function isString($string){
        return is_string($string);
    }
}
?>