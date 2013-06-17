<?php

/**
 * @Auteur Teake Otter
 * @Datum 21-5-2013
 */

require ("includeAll.php");

class airports
{
    //public properties
    public $AirportID;
    public $AirportName;
    public $AirportCity;
    //een functie om de properties te zetten
    public function SetProperties($id, $Name, $AirportCity)
    {
        $this->AirportID = $id;
        $this->AirportName = $Name;
        $this->AirportCity = $AirportCity;
    }
    //Nieuwe airport toevoegen
    public static function AddItem($Name, $AirportCity)
    {
        $Name = htmlspecialchars($Name);
        $AirportCity = htmlspecialchars($AirportCity);

        DbHandler::NonQuery("INSERT INTO airports(name, City) VALUES (:Name , :City); ",
            array("Name" => $Name, "City" => $AirportCity));
        $id = DbHandler::Query("SELECT airport_ID FROM airports WHERE name = (:Name);",
            array("Name" => $Name));

        $ClassObject = new airports();
        $ClassObject->SetProperties($id, $Name, $AirportCity);

        return $ClassObject;
    }
    //Airport wijzigen aan de hand van de id, die wordt meegegeven
    public static function EditItem($id, $Name, $AirportCity)
    {
        $Name = htmlspecialchars($Name);
        $AirportCity = htmlspecialchars($AirportCity);

        DbHandler::Query("UPDATE airports SET name = (:Name), City = :City WHERE airport_id = (:ID)",
            array(
            "Name" => $Name,
            "ID" => $id,
            "City" => $AirportCity));
        //gewijzigde object weer teruggeven
        $ClassObject = new airports();
        $ClassObject->SetProperties($id, $Name, $AirportCity);

        return $ClassObject;
    }
    //Vliegveld verwijderen
    public static function RemoveItem($id)
    {
        DbHandler::NonQuery("DELETE FROM airports WHERE airport_id = (:ID)", array("ID" =>
                $id));
        $results = DbHandler::Query("SELECT traject_id FROM traject WHERE airport_start_id = (:ID)  OR airport_stop_id = (:ID)",
            array("ID" => $id));
        DbHandler::NonQuery("DELETE FROM traject WHERE airport_start_id = (:ID)  OR airport_stop_id = (:ID)",
            array("ID" => $id));
        foreach ($results as $row) {
            DbHandler::NonQuery("DELETE FROM trajectairline WHERE traject_id = (:ID);",
                array("ID" => $row["traject_id"]));
        }
    }
    //Vliegveld ophalen aan de hand van de ID
    public static function GetAirportByID($id)
    {
        $Query = DbHandler::Query("SELECT * FROM airports WHERE airport_id = (:ID)",
            array("ID" => $id));

        $ClassObject = new airports();
        $ClassObject->SetProperties($id, $Query[0]["name"], $Query[0]["City"]);

        return $ClassObject;
    }
    //Airport ophalen aan de hand van de naam
    public static function GetAirportByName($Name)
    {
        $Query = DbHandler::Query("SELECT * FROM airports WHERE name = (:Name) lIMIT 1",
            array("Name" => $Name));

        if (count($Query) == 0) {
            return null;
        }
        $ClassObject = new airports();
        $ClassObject->SetProperties($Query[0]["airport_id"], $Query[0]["name"], $Query[0]["City"]);

        return $ClassObject;
    }
    //Alle vliegvelden ophalen
    public static function GetAirports()
    {
        $Query = DbHandler::Query("SELECT * FROM airports ORDER BY name ASC", null);
        $AirportCOllection = array();

        foreach ($Query as $result) {
            $AirportObject = new airports();
            $AirportObject->SetProperties($result["airport_id"], $result["name"], $result["City"]);
            array_push($AirportCOllection, $AirportObject);
        }

        return $AirportCOllection;
    }
    //alle steden ophalen die meer dan een vliegveld hebben
    public static function GetAirportsTwoPerCity()
    {
        $Query = DbHandler::Query("SELECT City FROM airports GROUP BY City HAVING COUNT(city) >1", null);
        $AirportCOllection = array();

        foreach ($Query as $result) {
            $AirportCOllection[] = $result["City"];
        }

        return $AirportCOllection;
    }
    //Vliegveld zoeken aan de hand van een string
    public static function SearchAirports($SearchQuery)
    {
        $SearchQuery = htmlspecialchars($SearchQuery);

        $Query = DbHandler::Query("SELECT * FROM airports WHERE name LIKE  :SearchQuery ORDER BY name ASC",
            array("SearchQuery" => "%" . $SearchQuery . "%"));
        $AirportCOllection = array();

        foreach ($Query as $result) {
            $AirportObject = new airports();
            $AirportObject->SetProperties($result["airport_id"], $result["name"], $result["City"]);
            array_push($AirportCOllection, $AirportObject);
        }


        return $AirportCOllection;

    }
}

?>