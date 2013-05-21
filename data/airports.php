<?php

require("includeAll.php");

    class airports
    {
        public $AirportID;
        public $AirportName;    
        
        public function SetProperties($id, $Name)
        {
            $this->AirportID = $id;
            $this->AirportName = $Name; 
        }
        
        public static function AddItem($Name) 
        {    
            DbHandler::NonQuery("INSERT INTO airports(name) VALUES (:Name); ", array("Name" => $Name));
            $id =  DbHandler::Query("SELECT airport_ID FROM airports WHERE name = (:Name);", array("Name" => $Name));   
            
            $ClassObject = new airports();
            $ClassObject -> SetProperties($id, $Name);
            
            Return $ClassObject;
        }
        
        public static function EditItem ($id, $Name)
        {            
            DbHandler::Query("UPDATE airports SET name = (:Name) WHERE airport_id = (:ID)", array("Name" => $Name, "ID" => $ID));
            
            $ClassObject = new airports();
            $ClassObject -> SetProperties($id, $Name);
            
            Return $ClassObject;
        }
        
        public static function RemoveItem ($id)
        {
            DbHandler::NonQuery("DELETE From airports WHERE airport_id = (:ID)", array("ID" => $id)); 
        }
        
        public static function GetAirportByID($id)
        {
            $Query =  DbHandler::Query("SELECT * FROM airports WHERE airport_id = (:ID) lIMIT 1", array("ID" => $id));
           
            $ClassObject = new airports();
            $ClassObject -> SetProperties($id, $Query[0]["name"]);
            
            return $ClassObject;
        }
        
        public static function  GetAirportByName($Name)
        {    
             $Query =  DbHandler::Query("SELECT * FROM airports WHERE name = (:Name) lIMIT 1", array("Name" => $Name));
             
             $ClassObject = new airports();
             $ClassObject -> SetProperties($Query[0]["airport_id"], $Query[0]["name"]);
            
             return $ClassObject;
        }
    
        public static function GetAirports()
        {
            $Query =  DbHandler::Query("SELECT * FROM airports", null);
            $AirportCOllection = array();
               
               foreach($Query as $result)
               {
                    $AirportObject = new airports();
                    $AirportObject -> SetProperties($result["airport_id"], $result["name"]);
                    array_push($AirportCOllection, $AirportObject);
               }
               
            return $AirportCOllection;    
        }
    }
?>