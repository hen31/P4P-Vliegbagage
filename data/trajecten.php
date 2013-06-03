<?php

/**
 * @author Wim Dalof
 * @copyright 2013
 * @date 03-06-3013
 */

require ("includeAll.php");
class trajecten
{
    public $TrajectID;
    public $Airport1;
    public $Airport2;

    //Add a new traject.
    public static function add_traject($startAirport, $stopAirport)
    {
        $start = airports::GetAirportByName($startAirport);
        $startAirportId = $start->AirportID;

        $stop = airports::GetAirportByName($stopAirport);
        $stopAirportId = $stop->AirportID;

        DbHandler::NonQuery("INSERT INTO traject (airport_start_id, airport_stop_id) VALUES(:startAirportId, :stopAirportId);",
            array("startAirportId" => $startAirportId, "stopAirportId" => $stopAirportId));
    }

    //Remove an existing traject by airportId.
    public static function remove_traject_by_airportid($airportId)
    {
        DbHandler::NonQuery("DELETE FROM traject WHERE airport_start_id = :airportID OR airport_stop_id = :airportID;",
            array("airportId" => $airportId));
    }

    //Remove an existing traject by trajectId.
    public static function remove_traject_by_trajectid($trajectId)
    {
        DbHandler::NonQuery("DELETE FROM traject WHERE traject_id = :trajectId;", array
            ("trajectId" => $trajectId));
    }

    //Get the total amount of trajecten (for pagination).
    public static function get_traject_amount($filterStartingPoint, $filterEndingPoint)
    {
        if (empty($filterStartingPoint) && empty($filterEndingPoint)) {
            $result = DbHandler::QueryScalar("SELECT COUNT(*) FROM traject;", null);
        }
        if ($filterStartingPoint && $filterEndingPoint) {
            $result = DbHandler::QueryScalar("SELECT COUNT(*) FROM traject WHERE airport_start_id = :filterStartingPoint && airport_stop_id = :filterEndingPoint;",
                array("filterStartingPoint" => $filterStartingPoint, "filterEndingPoint" => $filterEndingPoint));
        }
        if ($filterStartingPoint && empty($filterEndingPoint)) {
            $result = DbHandler::QueryScalar("SELECT COUNT(*) FROM traject WHERE airport_start_id = :filterStartingPoint;",
                array("filterStartingPoint" => $filterStartingPoint));
        }
        if (empty($filterStartingPoint) && $filterEndingPoint) {
            $result = DbHandler::QueryScalar("SELECT COUNT(*) FROM traject WHERE airport_stop_id = :filterEndingPoint;",
                array("filterEndingPoint" => $filterEndingPoint));
        }
        return $result;
    }

    //Check if an traject exists.
    public static function check_traject_exist($startAirport, $stopAirport)
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

    //Get all trajecten.
    public static function get_all_trajecten($begin, $startAirport, $stopAirport)
    {
        // TODO: REPLACE WITH DBHANDLER QUERYLIMIT - Wim
        if (empty($startAirport) && empty($stopAirport)) {
           // $result = DbHandler::Query("SELECT * FROM traject LIMIT " . $begin . ", 5", null);
        $result = DbHandler::QueryLimit("SELECT * FROM traject",null,$begin, 5 );
        }
        if ($startAirport && $stopAirport) {
                    $result = DbHandler::QueryLimit("SELECT * FROM traject WHERE airport_start_id = :startAirportId && airport_stop_id = :stopAirportId",array("startAirportId" => $startAirport, "stopAirportId" => $stopAirport),$begin, 5 );
        /*    $result = DbHandler::Query("SELECT * FROM traject WHERE airport_start_id = :startAirportId && airport_stop_id = :stopAirportId LIMIT " .
                $begin . ", 5", array("startAirportId" => $startAirport, "stopAirportId" => $stopAirport));*/
        }
        if ($startAirport && empty($stopAirport)) {
            $result = DbHandler::QueryLimit("SELECT * FROM traject WHERE airport_start_id = :startAirportId", array("startAirportId" => $startAirport), $begin, 5);
        }
        if (empty($startAirport) && $stopAirport) {
           
           $result = DbHandler::QueryLimit("SELECT * FROM traject WHERE airport_stop_id = :stopAirportId", array("stopAirportId" => $stopAirport),$begin, 5);
          /*  $result = DbHandler::Query("SELECT * FROM traject WHERE airport_stop_id = :stopAirportId LIMIT " .
                $begin . ", 5", array("stopAirportId" => $stopAirport));*/
        }
        for ($i = 0; $i < count($result); $i++) {
            $result[$i]["airport_start_id"] = airports::GetAirportByID($result[$i]["airport_start_id"]);
            $result[$i]["airport_stop_id"] = airports::GetAirportByID($result[$i]["airport_stop_id"]);
        }
        if ($result == null) {
            return null;
        } else {
            return $result;
        }
    }

    //Get traject by Id.
    public static function get_traject_by_trajectid($trajectId)
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

    //Get traject by airportname.
    public static function get_traject_by_airports($startAirport, $stopAirport)
    {
        $result = DbHandler::Query("SELECT * FROM trajecten WHERE trajecten.airport_start_id = (SELECT airport_id FROM airports WHERE name = :startAirport) AND airport_stop_id = (SELECT airport_id FROM airports WHERE name = :stopAirport);",
            array("startAirport" => $startAirport, "stopAirport" => $stopAirport));

        if ($result == null) {
            return null;
        } else {
            $traject = new trajecten();
            $traject->TrajectID = $result[0]["traject_id"];
            $traject->TrajectID = $result[0]["traject_id"];
            $traject->Airport1 = airports::GetAirportByID($result[0]["airport_start_id"]);
            $traject->Airport2 = airports::GetAirportByID($result[0]["airport_stop_id"]);
            return $traject;
        }
    }

    //Get traject by airportId.
    public static function get_traject_by_airportsid($startAirport, $stopAirport)
    {
        $result = DbHandler::Query("SELECT * FROM traject WHERE airport_start_id = :startAirport AND airport_stop_id =  :stopAirport;",
            array("startAirport" => $startAirport, "stopAirport" => $stopAirport));

        if ($result == null) {
            return null;
        } else {
            $traject = new trajecten();
            $traject->TrajectID = $result[0]["traject_id"];
            $traject->Airport1 = airports::GetAirportByID($result[0]["airport_start_id"]);
            $traject->Airport2 = airports::GetAirportByID($result[0]["airport_stop_id"]);
            return $traject;
        }
    }

    //Link an existing traject to an airport.
    public static function link_airport_to_traject($airport, $traject, $zone)
    {
        if (trajecten::LinkAirportTrajectExists($airport, $traject)) {
            DbHandler::NonQuery("UPDATE trajectairline SET zone=:zon WHERE traject_id=:tid AND  airline_id=:aid;",
                array(
                "tid" => $traject->TrajectID,
                "aid" => $airport->airline_id,
                "zon" => $zone));
        } else {
            DbHandler::NonQuery("INSERT INTO trajectairline(traject_id, airline_id, zone) VALUES(:tid, :aid, :zon);",
                array(
                "tid" => $traject->TrajectID,
                "aid" => $airport->airline_id,
                "zon" => $zone));
        }
    }

    //Check if a linked traject already exists.
    public static function check_linked_airport_traject_exists($airport, $traject)
    {
        $result = DbHandler::QueryScalar("SELECT COUNT(*) FROM trajectairline WHERE traject_id =:id AND airline_id=:aid;",
            array("id" => $traject->TrajectID, "aid" => $airport->airline_id));
        if ($result > 0) {
            return true;
        }
    }

    //Get airlines from linked traject.
    public static function get_airlines_from_linked_traject($traject)
    {
        $result = DbHandler::Query("SELECT * FROM trajectairline WHERE traject_id =:id;",
            array("id" => $traject->TrajectID));
        if (count($result) == 0) {
            return null;
        } else {
            $airlines = array();
            for ($i = 0; $i < count($result); $i++) {
                $airlines[] = airline::get_airline($result[$i]["airline_id"], 0);
            }
            return $airlines;
        }
    }

    //Remove a linked traject.
    public static function remove_linked_airport_traject($airportId, $traject)
    {
        DbHandler::NonQuery("DELETE FROM trajectairline WHERE traject_id=:tid AND  airline_id=:aid;",
            array("tid" => $traject->TrajectID, "aid" => $airportId));
    }
}
?>