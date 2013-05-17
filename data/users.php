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
        $check = UserNameExists($username);
        if(check != true)
        {
            //gebruiker aanmaken
        }
        $userId;
                                 
    }
    public static function deleteUser($userid)
    {
        
        $userId;
                               
    }
    
    public static function changeUser($userid,$username,$userPassword)
    {
        $check = UserNameExist($username);
        $user =GetUser($userid);
        if(check == false|| $user->userName == $username )
        {
           
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
     
    }   
}
?>