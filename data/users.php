<?php

require_once("includeAll.php");

class user
{
    public $id;
    public $userName;
    private static $SALT = "iefoafinfawhq91q9h''''aoiash[][]{}}{";
    
    //Set properties    
    public function SetProperties($id, $Name)
    {
            $this->userid = $id;
            $this->user = $Name; 
    }
    
    //Encrypt wachtwoord functie.
    public static function incryptPass($pass)
    {
        $incryptPass = sha1($pass . user::$SALT);
        
        return $incryptPass;
    }
            
    //Een check die kijkt of de username al bestaat. Geeft true of false terug.    
    private static function UsernameExists($username)
    {
        $userExist = DbHandler::Query("SELECT username FROM user WHERE username = :username", array("username"=> $username));
        
        //Check of gebruiker wel bestaat
        if($userExist !=null && $userExist[0]["username"] == $username){
            return true;
        }
        else
        {
        return false; 
        }
    }
    
    //Kan een nieuwe gebruiker aanmaken met wachtwoord.
    public static function createUser($username,$userPassword)
    {   
        $userPassword = sha1($userPassword . user::$SALT);
        $check = user::UserNameExists($username);
        if($check != true)
        {
            DbHandler::Query("INSERT INTO user(username,password) VALUES (:Name,:userPassword);", 
            array("Name" => $username, "userPassword" => $userPassword));
            echo "user aangemaakt";
        }
        else
        {
            return false;
        }                                
    }
    
    //Een gebruiker verwijderen.
    public static function deleteUser($userid)
    {        
            DbHandler::NonQuery("DELETE FROM user WHERE user_id =:ID;", array("ID" => $userid));
    }
    
    //Gebruikersnaam en/of wachtwoord veranderen.
    public static function changeUser($userid,$username,$userPassword)
    {
        $check = user::UsernameExists($username);
        $user =user::GetUser($userid);
        $userPassword = sha1($userPassword . user::$SALT);
        if($check == false|| $user->userName == $username )
        {
           DbHandler::NonQuery("UPDATE user SET username=:Name, password = :password WHERE user_id = :ID;", 
           array("Name" => $username, "ID" => $userid, "password" => $userPassword));
        }
        else
        {
            return false;
        }                                      
    }
    
    //Gebruiker ophalen aan de hand van de id die wordt opgegeven
    public static function GetUser($userid)
    {
        $results = DbHandler::Query("SELECT * FROM user WHERE user_id=:id;", array("id"=>$userid));
        if($results !=null)
        {
            $gebruiker = new user();
            
          $gebruiker->id  = $results[0]["user_id"];
           $gebruiker->userName  = $results[0]["username"];
           return $gebruiker;
        }
        else
        {
            return null;
        }                               
    }
    
    //Alle gebruikers die in de database staan terug geven afhankelijk van de zoekterm en de hoeveelste gebruikers
    public static function GetUsers($searchTerm)
    {
            $results = DbHandler::Query("SELECT * FROM user WHERE username LIKE :search ;", array(
            "search"=> '%'.$searchTerm.'%'));
            //als gebruikers leeg is null terug geven
        if($results !=null)
        {
      $gebruikers = array();
            for($i =0;$i<count($results);$i++)
            {
            $gebruiker = new user();
            $gebruiker->id  = $results[$i]["user_id"];
            $gebruiker->userName  = $results[$i]["username"];
            //Gebruiker toevoegen aan array
            $gebruikers[] = $gebruiker;
           }
           return $gebruikers;
        }
        else
        {
            return null;
        }
    }
              
    
    //fucntie om te checken of het een geldige login is. 
    //als dit het geval is dan wordt er een object van gebruiker terug gegeven.
    public static function login($username,$userPassword)
    {
      $results = DbHandler::Query("SELECT user_id, username FROM user WHERE username=:user AND password=:pass;", array(
            "user"=> $username, "pass"=>$userPassword));
            //als er geen gebruiker bestaat met deze combinatie wordt null terug gegeven
        if($results !=null)
        {
            
            $gebruiker = new user();
            
          $gebruiker->id  = $results[0]["user_id"];
           $gebruiker->userName  = $results[0]["username"];
           return $gebruiker;
        }
        else
        {
         //null terug geven   
            return null;
        }
    }
    
    //Check of gebruikersnaam en wachtwoord kloppen
    public static function checkUser($username, $userPassword)
    {
        $check;
        $results = DbHandler::Query("SELECT * FROM user WHERE username=:user AND password=:pass;", array(
            "user"=> $username, "pass"=>$userPassword));
            
        if($results != null)
        {
            $check = true;
        }
        else
        {
            $check = false;    
        }
        
        return $check;
    }
}
?>