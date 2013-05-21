<?php

require("includeAll.php");

    class airports
    {
        public $AirportID;
        public $AirportName;    
        
        public function SetProperties($Name, $id)
        {
            $this->AirportID = $id;
            $this->AirportName = $Name; 
        }
        
        public static function AddItem($Name) 
        { 
            if (empty($Name) == false)
            {   
                DbHandler::NonQuery("INSERT INTO airports(name) VALUES (:Name); ", array("Name" => $Name));
                $id =  DbHandler::Query("SELECT airport_ID FROM airports WHERE name = (:Name);", array("Name" => $Name));
            }   
            
            $ClassObject = new airports();
            $ClassObject -> SetProperties($id, $Name);
            
            Return $ClassObject;
        }
        
        public static function EditItem ($id, $Name)
        {
            if (empty($id) == false)            
            {
                DbHandler::Query("UPDATE airports SET name = (:Name) WHERE airport_id = (:ID)", array("Name" => $Name, "ID" => $ID));
            }
            
            $ClassObject = new airports();
            $ClassObject -> SetProperties($id, $Name);
            
            Return $ClassObject;
        }
        
        public static function RemoveItem ($id)
        {
            if (empty($id) == false)            
            {
                DbHandler::NonQuery("DELETE From airports WHERE airport_id = (:ID)", array("ID" => $id));
            } 
        }
        
        public static function GetAirportByID($id)
        {
            if ( empty($id) == false)
            {
               $Query =  DbHandler::Query("SELECT * FROM airports WHERE airport_id = (:ID) lIMIT 1", array("ID" => $id));
            }
           
           //klopt niet 
            $ClassObject = new airports();
            $ClassObject -> SetProperties($id, $Query[1]["name"]);
            
            return $ClassObject;
        }
        
        public static function  GetAirportByName($Name)
        {
             if ( empty($Name) == false)
             {
                $Query =  DbHandler::Query("SELECT * FROM airports WHERE name = (:Name) lIMIT 1", array("Name" => $Name));
             }
             
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
                    $AirportObject -> SetProperties($result["name"], $result["airport_id"]);
                    array_push($AirportCOllection, $AirportObject);
               }
               
            return $AirportCOllection;    
        }
    }
?>