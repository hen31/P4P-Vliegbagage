<?php

/**
* @Auteur Teake Otter
* @Datum 21-5-2013
*/

require("includeAll.php");

    class airports
    {
        public $AirportID;
        public $AirportName;
        public $AirportCity;    
        
        public function SetProperties($id, $Name, $AirportCity)
        {
            $this->AirportID = $id;
            $this->AirportName = $Name; 
            $this->AirportCity = $AirportCity;
        }
        
        public static function AddItem($Name, $AirportCity) 
        {    
            DbHandler::NonQuery("INSERT INTO airports(name, City) VALUES (:Name , :City); ", array("Name" => $Name, "City" => $AirportCity));
            $id =  DbHandler::Query("SELECT airport_ID FROM airports WHERE name = (:Name);", array("Name" => $Name));   
            
            $ClassObject = new airports();
            $ClassObject -> SetProperties($id, $Name, $AirportCity);
            
            Return $ClassObject;
        }
        
        public static function EditItem ($id, $Name, $AirportCity)
        {            
            DbHandler::Query("UPDATE airports SET name = (:Name), City = :City WHERE airport_id = (:ID)", array("Name" => $Name, "ID" => $id, "City" => $AirportCity));
            
            $ClassObject = new airports();
            $ClassObject -> SetProperties($id, $Name, $AirportCity);
            
            Return $ClassObject;
        }
        
        public static function RemoveItem ($id)
        {
            DbHandler::NonQuery("DELETE From airports WHERE airport_id = (:ID)", array("ID" => $id));
        }
        
        public static function GetAirportByID($id)
        {
            $Query =  DbHandler::Query("SELECT * FROM airports WHERE airport_id = (:ID)", array("ID" => $id));
           
            $ClassObject = new airports();
            $ClassObject -> SetProperties($id, $Query[0]["name"], $Query[0]["City"]);
            
            return $ClassObject;
        }
        
        public static function  GetAirportByName($Name)
        {    
             $Query =  DbHandler::Query("SELECT * FROM airports WHERE name = (:Name) lIMIT 1", array("Name" => $Name));
             
             if (count($Query) == 0)
             {
                return null;
             }
             $ClassObject = new airports();
             $ClassObject -> SetProperties($Query[0]["airport_id"], $Query[0]["name"], $Query[0]["City"]);
            
             return $ClassObject;
        }
    
        public static function GetAirports()
        {
            $Query =  DbHandler::Query("SELECT * FROM airports", null);
            $AirportCOllection = array();
               
               foreach($Query as $result)
               {
                    $AirportObject = new airports();
                    $AirportObject -> SetProperties($result["airport_id"], $result["name"], $result["City"]);
                    array_push($AirportCOllection, $AirportObject);
               }
               
            return $AirportCOllection;    
        }
          public static function GetAirportsTwoPerCity()
        {
            $Query =  DbHandler::Query("SELECT City FROM airports GROUP BY City HAVING COUNT(city) >1", null);
            $AirportCOllection = array();
               
               foreach($Query as $result)
               {
                    $AirportCOllection[] = $result["City"];
               }
               
            return $AirportCOllection;    
        }
        public static function SearchAirports($SearchQuery)
        {   
            $Query =  DbHandler::Query("SELECT * FROM airports WHERE name LIKE  :SearchQuery ", array("SearchQuery" => "%" . $SearchQuery . "%"));
            $AirportCOllection = array();
               
               foreach($Query as $result)
               {
                    $AirportObject = new airports();
                    $AirportObject -> SetProperties($result["airport_id"], $result["name"], $result["City"]);
                    array_push($AirportCOllection, $AirportObject);
               }
               
               
            return $AirportCOllection;    
            
        }
    }
?>