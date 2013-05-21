<?php
/**
* @Auteur Ivar de Lange
* @Datum 13-5-2013
*/

require ("includeAll.php");

class SpecialLuggage
{
    public $specialluggage_id;
    public $AirlineID;
    public $Name;
    public $Notes;
    
    public function SetProperties($specialluggage_id, $AirlineID, $Name, $Notes)
    {
        $this->specialluggage_id = $specialluggage_id;
        $this->AirlineID = $AirlineID;
        $this->Name = $Name;
        $this->Notes = $Notes;
    }
    
    public static function AddItem($AirlineID, $Name, $Notes)
    {  
        $QueryResult = $Result = DbHandler::Query("SELECT specialluggage_id FROM specialluggage WHERE name = :Name ;", array("Name" => $Name));
        
        if ($QueryResult != null && count($QueryResult) > 0)
        {
            $SpecialLugageID = $QueryResult[0]["specialluggage_id"];
            
        }
        else
        {
            DbHandler::NonQuery("INSERT INTO specialluggage (name) VALUES (:Name); ", array("Name" => $Name));
            $Result = DbHandler::Query("SELECT specialluggage_id FROM specialluggage WHERE name = :Name ;", array("Name" => $Name));
            $SpecialLugageID = $Result[0]["specialluggage_id"];
        }
        
        DbHandler::NonQuery("INSERT INTO airlinespecialluggage (airline_id, specialluggage_id, notes) VALUES (:Airlineid, :specialid, :notes); ", array("Airlineid" => $AirlineID, "specialid" => $SpecialLugageID, "notes" => $Notes));
        $ClassObject = new SpecialLuggage();
        $ClassObject->SetProperties($SpecialLugageID, $AirlineID, $Name, $Notes);
        return $ClassObject;
        
    }
    
    //seleceerd nog niet op airlineID
    public static function EditItem($Specialluggage_id, $airlineID, $Name, $Notes)
    {
        if ($airlineID != 0 && $notes != "")
        {
            DbHandler::NonQuery("UPDATE specialluggage SET name = :Name WHERE specialluggage_id = :ID", array("Name" => $Name, "ID" => $Specialluggage_id));
            DbHandler::NonQuery("UPDATE airlinespecialluggage SET airline_id = :airlineID, notes = :notes WHERE specialluggage_id = :ID", array("airline_id" => $airlineID, "ID" => $Specialluggage_id, "notes" => $Notes));
        
            $ClassObject = new SpecialLuggage();
            $ClassObject->SetProperties($Specialluggage_id, $airlineID, $Name, $Notes);
            return $ClassObject;
        }
        else
        {
            DbHandler::NonQuery("UPDATE specialluggage SET name = :Name WHERE specialluggage_id = :ID", array("Name" => $Name, "ID" => $Specialluggage_id));
        }
    }
    
    public static function RemoveItem($Specialluggage_id)
    {
        DbHandler::NonQuery("DELETE From specialluggage WHERE specialluggage_id = :ID", array("ID" => $Specialluggage_id));
        DbHandler::NonQuery("DELETE From airlinespecialluggage WHERE specialluggage_id = :ID", array("ID" => $Specialluggage_id));
    }
    
    public static function GetSpecialLuggageID($ID)
    {
        $Result = DbHandler::Query("SELECT * FROM specialluggage WHERE specialluggage_id = :ID ;", array("ID" => $ID));   
        $ClassObject = new SpecialLuggage();
        $ClassObject->SetProperties($ID, 0,$Result[0]["name"],"");
        return $ClassObject;
    }
    
    public static function GetSpecialLuggageName($Name)
    {
        $Result = DbHandler::Query("Select * FROM specialluggage WHERE  NAME = :Name;", array("Name" => $Name));
        $ClassObject = new SpecialLuggage();
        $ClassObject->SetProperties($Result[0]["speciallugggage_id"], 0,$Result[0]["name"],"");
        return $ClassObject;
    }
      
    public static function GetCombo($airlineID, $specialLuggageID)
    {
        $Result = DbHandler::Query("SELECT * FROM specialluggage WHERE specialluggage_id = :ID ;", array("ID" => $specialLuggageID));
        $Result2 = DbHandler::Query("SELECT * FROM airlinespecialluggage WHERE airline_id = :AirlineID ;", array("AirlineID" => $airlineID));   
        $ClassObject = new SpecialLuggage();
        $ClassObject->SetProperties($specialLuggageID, $airlineID,$Result[0]["name"], $Result2[0]["notes"]);
        return $ClassObject;
    }
    
    
}


?>