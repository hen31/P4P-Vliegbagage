<?php
/**
 * @Auteur Ivar de Lange & Teake Otter & Niels Riemersma
 * @Datum 21-5-2013 - 30-5-2013
 */

require ("includeAll.php");

class SpecialLuggage
{
    //set public variables
    public $specialluggage_id;
    public $AirlineID;
    public $Name;
    public $Notes;

    //deze functie geeft waarden aan speciale bagage per airline
    public function SetProperties($specialluggage_id, $AirlineID, $Name, $Notes)
    {
        $this->specialluggage_id = $specialluggage_id;
        $this->AirlineID = $AirlineID;
        $this->Name = $Name;
        $this->Notes = $Notes;
    }
    //deze functie geeft waarden aan algemene speciale bagage
    public function SetPropertiestwo($specialluggage_id, $name)
    {
        $this->specialluggage_id = $specialluggage_id;
        $this->Name = $name;
    }

    //deze functie zorgt ervoor dat de beheerder specifieke airline informatie per spec bagage toe kan voegen
    public static function AddItem($AirlineID, $Name, $Notes)
    {
        $Name = htmlspecialchars($Name);
        $Notes = htmlspecialchars($Notes);
        
        $QueryResult = DbHandler::Query("SELECT specialluggage_id FROM specialluggage WHERE name = :Name ;",
            array("Name" => $Name));

        if ($QueryResult != null && count($QueryResult) > 0) {
            $SpecialLugageID = $QueryResult[0]["specialluggage_id"];
        } else {
            DbHandler::NonQuery("INSERT INTO specialluggage (name) VALUES (:Name); ", array
                ("Name" => $Name));
            $Result = DbHandler::Query("SELECT specialluggage_id FROM specialluggage WHERE name = :Name ;",
                array("Name" => $Name));
            $SpecialLugageID = $Result[0]["specialluggage_id"];
        }
        if ($AirlineID != null) {

			DbHandler::NonQuery("INSERT INTO airlinespecialluggage (airline_id, specialLuggage_id, notes) VALUES (:Airlineid, :specialid, :notes); ",
                array(
                "Airlineid" => $AirlineID,
                "specialid" => $SpecialLugageID,
                "notes" => $Notes));
            $ClassObject = new SpecialLuggage();
            $ClassObject->SetProperties($SpecialLugageID, $AirlineID, $Name, $Notes);
            return $ClassObject;
        }

    }
    //deze functie voegt de optie toe om bestaande notes te wijzigen
    public static function EditAirlineNotes($Specialluggage_id, $airlineID, $Notes)
    {
        $Notes = htmlspecialchars($Notes);
        
		if ($airlineID != 0 && $Notes != "") {
            DbHandler::NonQuery("UPDATE airlinespecialluggage SET airline_id = :airlineID, notes = :notes WHERE specialluggage_id = :ID",
                array(
				"airlineID" => $airlineID,
                "ID" => $Specialluggage_id,
                "notes" => $Notes));
            return SpecialLuggage::GetCombo($airlineID, $Specialluggage_id);
        }
    }

    //deze functie laat de beheerder bestaande speciale bagage informatie wijzigen
    public static function EditSpecialLuggage($Specialluggage_id, $Name)
    {
        $Name = htmlspecialchars($Name);
        
        DbHandler::NonQuery("UPDATE specialluggage SET name = :Name WHERE specialluggage_id = :ID",
            array("Name" => $Name, "ID" => $Specialluggage_id));
        return SpecialLuggage::GetCombo($airlineID, $Specialluggage_id);
    }

    //hiermee kan speciale bagage uit het systeem verwijderd worden
    public static function RemoveSpecialLuggage($Specialluggage_id)
    {
        DbHandler::NonQuery("DELETE FROM specialluggage WHERE specialluggage_id = :ID",
            array("ID" => $Specialluggage_id));
        DbHandler::NonQuery("DELETE FROM airlinespecialluggage WHERE specialluggage_id = :ID",
            array("ID" => $Specialluggage_id));
    }

    //met deze functie kunnen airline en speciale bagage losgekoppeld worden
    public static function RemoveAirLineSpecialLuggage($SpecialLuggage_id, $AirlineID)
    {
        DbHandler::Query("DELETE FROM airlinespecialluggage WHERE specialluggage_id = :SID AND airline_id = :AID ;",
            array("SID" => $SpecialLuggage_id, "AID" => $AirlineID));
    }

    //door gebruik van deze functie wordt de id van de speciale bagage opgehaald
    public static function GetSpecialLuggageID($ID)
    {
        $Result = DbHandler::Query("SELECT * FROM specialluggage WHERE specialluggage_id = :ID ;",
            array("ID" => $ID));
        $ClassObject = new SpecialLuggage();
        if (count($Result) == 0) {
            return null;
        } else {
            $ClassObject->SetProperties($ID, 0, $Result[0]["name"], "");
            return $ClassObject;
        }
    }

    //deze functie haalt de naam van de speciale bagage op
    public static function GetSpecialLuggageName($Name)
    {
        $Name = htmlspecialchars($Name);
        
        $Result = DbHandler::Query("Select * FROM specialluggage WHERE  NAME = :Name;",
            array("Name" => $Name));
        if (count($Result) < 1) {
            return null;
        }
        $ClassObject = new SpecialLuggage();
        $ClassObject->SetProperties($Result[0]["specialluggage_id"], 0, $Result[0]["name"],
            "");
        return $ClassObject;
    }

    //deze functie zorgt voor een koppeling tussen airline en bagage
    public static function GetCombo($airlineID, $specialLuggageID)
    {
        $Result = DbHandler::Query("SELECT * FROM specialluggage WHERE specialluggage_id = :ID ;",
            array("ID" => $specialLuggageID));
        $Result2 = DbHandler::Query("SELECT * FROM airlinespecialluggage WHERE airline_id = :AirlineID AND specialluggage_id = :ID  ;",
            array("AirlineID" => $airlineID,"ID" => $specialLuggageID));
        if (count($Result2) != 0) {
            $ClassObject = new SpecialLuggage();
            $ClassObject->SetProperties($specialLuggageID, $airlineID, $Result[0]["name"], $Result2[0]["notes"]);
        } else {
            return null;
        }
        return $ClassObject;
    }

    //deze functie laat de beheerder bestaande bagage wijzigen (naam)
    public static function EditItem($id, $Name)
    {
        $Name = htmlspecialchars($Name);
        
        DbHandler::Query("UPDATE specialluggage SET name = (:Name) WHERE specialluggage_id = (:ID)",
            array("Name" => $Name, "ID" => $id));

        $ClassObject = new SpecialLuggage();
        $ClassObject->SetPropertiestwo($id, $Name);

        return $ClassObject;
    }

    //hiermee haal je de bestaande informatie op om later te kunnen weergeven
    public static function GetSpecialLuggageList()
    {
        $Query = DbHandler::Query("SELECT * FROM specialluggage ORDER BY specialluggage.name ASC", null);
        $SpecialLuggageCollection = array();

        foreach ($Query as $result) {
            $SpecialLuggageObject = new SpecialLuggage();
            $SpecialLuggageObject->SetProperties($result["specialluggage_id"], 0, $result["name"],
                "");
            array_push($SpecialLuggageCollection, $SpecialLuggageObject);
        }
        return $SpecialLuggageCollection;
    }


    public static function GetLinkedSpecialLuggageList($airlineID)
    {
        $Query = DbHandler::Query("SELECT * FROM specialluggage WHERE specialluggage_id NOT IN (SELECT specialluggage_id FROM airlineSpecialluggage WHERE airline_id=:ID);",
            array("ID" => $airlineID));
        $SpecialLuggageCollection = array();

        foreach ($Query as $result) {
            $SpecialLuggageObject = new SpecialLuggage();
            $SpecialLuggageObject->SetProperties($result["specialluggage_id"], 0, $result["name"],
                "");
            array_push($SpecialLuggageCollection, $SpecialLuggageObject);
        }
        return $SpecialLuggageCollection;
    }

    public static function GetNotLinkedSpecialLuggageList($airlineID)
    {
        $Query = DbHandler::Query("SELECT * FROM specialluggage WHERE specialluggage_id  IN (SELECT specialluggage_id FROM airlineSpecialluggage WHERE airline_id=:ID);",
            array("ID" => $airlineID));
        $SpecialLuggageCollection = array();

        foreach ($Query as $result) {
            $SpecialLuggageObject = new SpecialLuggage();
            $SpecialLuggageObject->SetProperties($result["specialluggage_id"], 0, $result["name"],
                "");
            array_push($SpecialLuggageCollection, $SpecialLuggageObject);
        }
        return $SpecialLuggageCollection;
    }
    //dit is de zoekfunctie waarmee de beheerder later zijn informatie sneller voor zich kan krijgen
    public static function SearchSpecialLuggage($SearchQuery)
    {
        $SearchQuery = htmlspecialchars($SearchQuery);
        
        $Query = DbHandler::Query("SELECT * FROM specialluggage WHERE name LIKE  :SearchQuery ",
            array("SearchQuery" => "%" . $SearchQuery . "%"));
        $SpecialLuggageCollection = array();

        foreach ($Query as $result) {
            $SpecialLuggageObject = new SpecialLuggage();
            $SpecialLuggageObject->SetPropertiestwo($result["specialluggage_id"], $result["name"]);
            array_push($SpecialLuggageCollection, $SpecialLuggageObject);
        }


        return $SpecialLuggageCollection;

    }
}


?>