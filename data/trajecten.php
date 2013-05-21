<?php

/**
 * @author Wim Dalof
 * @copyright 2013
 */

require("includeAll.php");
class trajecten
{
	public $TrajectID;
	public $StartAirport;
	public $StopAirport;
	
	//Add a new traject.
	public function AddItem($startAirport, $stopAirport)
	{
		$start = airports::GetAirportByName($startAirport);
		$startAirportId = $start->AirportID;
		
		$stop = airports::GetAirportByName($stopAirport);
		$stopAirportId = $stop->AirportID;
		
		DbHandler::NonQuery("INSERT INTO traject (airport_start_id, airport_end_id) VALUES(:startAirportId, :stopAirportId);", array("startAirportId" => $startAirportId, "stopAirportId" => $stopAirportId));
	}
	
	//Remove an existing traject.
	public function RemoveItem($trajectId)
	{
		DbHandler::NonQuery("DELETE FROM traject WHERE traject_id = :trajectId;", array("trajectId" => $trajectId));
	}
	
	//Get traject by Id.
	public function GetTraject($trajectId)
	{
		$traject = new trajecten();
		$traject->TrajectID = $trajectId;
		
		$result = DbHandler::Query("SELECT * FROM traject WHERE traject_id = :trajectId;", array("trajectId" => $trajectId));
		if($result == null)
		{
			return null;
		}
		else
		{
			$traject->Airport1 = airports::GetAirportByID($result[0]["airport_start_id"]);
			$traject->Airport2 = airports::GetAirportByID($result[0]["airport_end_id"]);
			return $traject;
		}
	}
	
	//Get traject by airports.
	public function GetTrajectByCity($startAirport, $stopAirport)
	{
		$result = DbHandler::QueryScalar("SELECT * FROM trajecten WHERE trajecten.airport_start_id = (SELECT airport_id FROM airports WHERE name = :startAirport) AND airport_stop_id = (SELECT airport_id FROM airports WHERE name = :stopAirport);", array("startAirport" => $startAirport,"stopAirport" => $stopAirport));
		
		if($result == null)
		{
			return null;	
		}
		else
		{
			$traject = new trajecten();
			$traject->TrajectID = $result[0]["traject_id"];
			return $traject;
		}
	}
}

?>