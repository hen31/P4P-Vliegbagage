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
    public function AddItem($startAirport, $stopAirport)
    {
        $start = airports::GetAirportByName($startAirport);
        $startAirportId = $start->AirportID;

        $stop = airports::GetAirportByName($stopAirport);
        $stopAirportId = $stop->AirportID;

        DbHandler::NonQuery("INSERT INTO traject (airport_start_id, airport_stop_id) VALUES(:startAirportId, :stopAirportId);",
            array("startAirportId" => $startAirportId, "stopAirportId" => $stopAirportId));
    }

    //Remove an existing traject.
    public function RemoveItem($trajectId)
    {
        DbHandler::NonQuery("DELETE FROM traject WHERE traject_id = :trajectId;", array
            ("trajectId" => $trajectId));
    }
    
    public function GetTrajectAmount()
    {
		$result = DbHandler::QueryScalar("SELECT COUNT(*) FROM traject;",null);
        
        return $result;
    }
    
     public function GetAllTrajecten()
     {
         $result = DbHandler::Query("SELECT * FROM traject", null);
         
         for($i = 0; $i < count($result); $i++)
         {
            $result[$i]['airport_start_id'] = airports::GetAirportByID($result[$i]['airport_start_id']);
            $result[$i]['airport_stop_id'] = airports::GetAirportByID($result[$i]['airport_stop_id']);
         }
         
         if ($result == null) {
             return null;
         } else {
			return $result;
         }
     }
    
#     public function GetAllTrajecten()
#     {
#         $traject = new trajecten();
#         $result = DbHandler::Query("SELECT * FROM traject", null);
#         if ($result == null) {
#             return null;
#         } else {
#             $traject->Airport1 = airports::GetAirportByID($result[0]["airport_start_id"]);
#             $traject->Airport2 = airports::GetAirportByID($result[0]["airport_stop_id"]);
#             return $traject;
#         }
#     }
 

    //Get traject by Id.
    public function GetTraject($trajectId)
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
    public function GetTrajectByCity($startAirport, $stopAirport)
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