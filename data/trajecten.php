<?php

/**
 * @author Wim Dalof
 * @copyright 2013
 */

require ("includeAll.php");
class trajecten
{
    public $TrajectID;
    public $Airport1;
    public $Airport2;

    //Add a new traject.
    public static function AddTraject($startAirport, $stopAirport)
    {
        $start = airports::GetAirportByName($startAirport);
        $startAirportId = $start->AirportID;

        $stop = airports::GetAirportByName($stopAirport);
        $stopAirportId = $stop->AirportID;

        DbHandler::NonQuery("INSERT INTO traject (airport_start_id, airport_stop_id) VALUES(:startAirportId, :stopAirportId);",
            array("startAirportId" => $startAirportId, "stopAirportId" => $stopAirportId));
    }

    public static function RemoveTrajectenByAirport($airportId)
    {
        DbHandler::NonQuery("DELETE FROM traject WHERE airport_start_id = :airportID OR airport_stop_id = :airportID;",
            array("airportId" => $airportId));
    }

    //Remove an existing traject.
    public static function RemoveTraject($trajectId)
    {
        DbHandler::NonQuery("DELETE FROM traject WHERE traject_id = :trajectId;", array
            ("trajectId" => $trajectId));
    }

    public static function GetTrajectAmount($filterId, $filterBeginPunt, $filterEindPunt)
    {
        if ($filterId == 0) {
            $result = DbHandler::QueryScalar("SELECT COUNT(*) FROM traject;", null);
        }
        if ($filterId == 1) {
            $result = DbHandler::QueryScalar("SELECT COUNT(*) FROM traject WHERE airport_start_id = :filterBeginPunt && airport_stop_id = :filterEindPunt;",
                array("filterBeginPunt" => $filterBeginPunt, "filterEindPunt" => $filterEindPunt));
        }
        if ($filterId == 2) {
            $result = DbHandler::QueryScalar("SELECT COUNT(*) FROM traject WHERE airport_start_id = :filterBeginPunt;",
                array("filterBeginPunt" => $filterBeginPunt));
        }
        if ($filterId == 3) {
            $result = DbHandler::QueryScalar("SELECT COUNT(*) FROM traject WHERE airport_stop_id = :filterEindPunt;",
                array("filterEindPunt" => $filterEindPunt));
        }

        return $result;
    }

    public static function CheckTrajectExist($startAirport, $stopAirport)
    {
        $start = airports::GetAirportByName($startAirport);
        $startAirportId = $start->AirportID;

        $stop = airports::GetAirportByName($stopAirport);
        $stopAirportId = $stop->AirportID;

        $result = DbHandler::Query("SELECT * FROM traject WHERE airport_start_id = :startAirportId AND airport_stop_id = :stopAirportId;",
            array("startAirportId" => $startAirportId, "stopAirportId" => $stopAirportId));
        if (count($result) == 0) {
            return false;
        } else {
            return true;
        }

    }

    public static function GetAllTrajecten($begin, $startAirport, $stopAirport)
    {
        if (empty($startAirport) && empty($stopAirport)) {
            $result = DbHandler::Query("SELECT * FROM traject LIMIT " . $begin . ", 5", null);

        }
        if ($startAirport && $stopAirport) {
            $result = DbHandler::Query("SELECT * FROM traject WHERE airport_start_id = :startAirportId && airport_stop_id = :stopAirportId LIMIT " .
                $begin . ", 5", array("startAirportId" => $startAirport, "stopAirportId" => $stopAirport));
        }
        if ($startAirport && empty($stopAirport)) {
            $result = DbHandler::Query("SELECT * FROM traject WHERE airport_start_id = :startAirportId LIMIT " .
                $begin . ", 5", array("startAirportId" => $startAirport));
        }
        if (empty($startAirport) && $stopAirport) {
            $result = DbHandler::Query("SELECT * FROM traject WHERE airport_stop_id = :stopAirportId LIMIT " .
                $begin . ", 5", array("stopAirportId" => $stopAirport));
        }
        for ($i = 0; $i < count($result); $i++) {
            $result[$i]['airport_start_id'] = airports::GetAirportByID($result[$i]['airport_start_id']);
            $result[$i]['airport_stop_id'] = airports::GetAirportByID($result[$i]['airport_stop_id']);
        }

        if ($result == null) {
            return null;
        } else {
            return $result;
        }
    }

    //Get traject by Id.
    public static function GetTraject($trajectId)
    {
        $traject = new trajecten();
        $traject->TrajectID = $trajectId;

        $result = DbHandler::Query("SELECT * FROM traject WHERE traject_id = :trajectId;",
            array("trajectId" => $trajectId));
        if (count($result) == 0) {
            return null;
        } else {
            $traject->Airport1 = airports::GetAirportByID($result[0]["airport_start_id"]);
            $traject->Airport2 = airports::GetAirportByID($result[0]["airport_stop_id"]);
            return $traject;
        }
    }

    //Get traject by airports.
    public static function GetTrajectByAirports($startAirport, $stopAirport)
    {
        $result = DbHandler::QueryScalar("SELECT * FROM trajecten WHERE trajecten.airport_start_id = (SELECT airport_id FROM airports WHERE name = :startAirport) AND airport_stop_id = (SELECT airport_id FROM airports WHERE name = :stopAirport);",
            array("startAirport" => $startAirport, "stopAirport" => $stopAirport));

        if ($result == null) {
            return null;
        } else {
            $traject = new trajecten();
            $traject->TrajectID = $result[0]["traject_id"];
            return $traject;
        }
    }
}

?>