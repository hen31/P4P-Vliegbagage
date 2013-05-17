<?php

require_once("includeAll.php");

class user
{
    public $id;
    public $userName;

    private static function UsernameExists($username)
    {
        $userExist = DbHandler::Query("SELECT username FROM users WHERE username = '$username'");
        return $userExist;
    }
    
    public static function createUser($username,$userPassword)
    {
        $check = UserNameExists();
        if(check == true)
        {
            echo 'Gebruikernaam bestaat al';
        }
        $userId;
                                 
    }
    public static function deleteUser($userid)
    {
        $check = UserNameExists();
        if(check == false)
        {
            echo 'Gebruikersnaam bestaat niet';
        }
        $userId;
                               
    }
    
    public static function changeUser($userid,$username,$userPassword)
    {
        $check = UserNameExist();
        if(check == false)
        {
            echo 'Gebruikersnaam bestaat niet';
        }
        $userId;
                               
    }
    
    public static function GetUser($userid)
    {
        $userId;
                               
    }
    
    public static function GetUsers($searchTerm, $start,$end)
    {
        $userId;
                               
    }
    
    public static function login($username,$userPassword)
    {
        $check = UserNameExist();
        if(check == false)
        {
            echo 'Username bestaat niet';
        }
    }   
}
?>