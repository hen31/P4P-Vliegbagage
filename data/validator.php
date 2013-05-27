<?php

/**
 * @Auteur Robert de Jong
 * @Datum 13-5-2013
 */
 
class validator{
    //kijken of het een nummer is
    static function isInt($getal){
        return is_numeric($getal);
    }
    //kijken of het een string is
    static function isString($string){
        return is_string($string);
    }
    //kijken of het een valide email adres is
    static function isEmail($email){
        $regex = "/^[a-zA-Z0-9_.-]+@[a-zA-Z0-9-]+.[a-zA-Z0-9-.]+$/";
        return preg_match($regex, $email);
    }
    //checken hoelang een string is geworden
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
    //Kijken of het een float is
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
    //kijken of de captcha klopt
    static function validateCaptcha($input, $hash){
        return ((sha1(strtolower($input) ."iuherkdjcby8rhb") == $hash));
    }
    
    //Kijken of het een url is
    static function isUrl($url){
        return filter_var($url, FILTER_VALIDATE_URL);
    }
}
?>