<?php

/**
 * @Auteur Robert de Jong
 * @Datum 13-5-2013
 */
 
class validator{
    
    static function isInt($getal){
        return is_int($getal);
    }
    
    static function isString($string){
        return is_string($string);
    }
    
    static function isEmail($email){
        $regex = "/^[a-zA-Z0-9_.-]+@[a-zA-Z0-9-]+.[a-zA-Z0-9-.]+$/";
        return preg_match($regex, $email);
    }
    
    static function stringLimit($min, $max, $string){
        $strlen = strlen($string);
        if($strlen < $min){
            return false;
        }
        if($strlen > $max){
            return false;
        }
        return true;
    }
    
    static function isValuta($bedrag){
        if(!is_float($bedrag)){
            return false;
        }
        
        $round = round($bedrag, 2);
        if($round !== $bedrag){
            return false;
        }
        
        return true;
    }
}
?>