<?php

/**
 * @author 
 * @copyright 2013
 */

require("includeAll.php");
class trajecten
{
	public function AddItem($startAirport, $stopAirport)
	{
		$startAirportId = DbHandler::QueryScalar("SELECT airport_id FROM airports WHERE name = :startAirport", array("startAirport" => $startAirport));
		$endAirportId = DbHandler::QueryScalar("SELECT airport_id FROM airports WHERE name = :endAirport", array("endAirport" => $endAirport));
		
		DbHandler::NonQuery("INSERT INTO traject (airport_start_id, airport_end_id) VALUES(:startAirportId, :endAirportId)", array("startAirportId" => $startAirportId, "endAirportId" => $endAirportId));
	}
	
	public function RemoveItem($trajectId)
	{
		DbHandler::NonQuery("DELETE FROM traject WHERE traject_id = :trajectId", array("trajectId" => $trajectId));
	}
	
	public function GetTraject($trajectId)
	{
		$traject = new trajecten();
		$traject->TrajectID = $trajectId;
		
		$result = DbHandler::Query("SELECT * FROM traject WHERE traject_id = :trajectId", array("trajectId" => $trajectId));
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
	public function GetTrajectByCity($airport1Name, $airport2Name)
	{
		$result = DbHandler::QueryScalar("SELECT * FROM trajecten WHERE trajecten.airport_start_id = (SELECT airport_id FROM airports WHERE name = :airport2Name) AND airport_stop_id = (SELECT airport_id FROM airports WHERE name = :airport2Name);", array("airport1Name" => $airport1Name,"airport2Name" => $airport2Name));
		
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