<?php

/**
 * @author 
 * @copyright 2013
 */

require("includeAll.php");
class trajecten
{
	public $TrajectID;//int
	public $Airport1;//airport
	public $Airport2;//airport
	
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
		else{
			$traject->Airport1 = airports::GetAirportByID($result[0]["airport_start_id"]);
			$traject->Airport2 = airports::GetAirportByID($result[0]["airport_end_id"]);
			return $traject;
		}
	}
	/*public function GetTrajectByCity($airport1ID, $airport2ID)//select... where startAirport=
	{
		$startAirportId = DbHandler::QueryScalar("SELECT airport_id FROM airports WHERE name = :startAirport", array("startAirport" => $startAirport));
		$endAirportId = DbHandler::QueryScalar("SELECT airport_id FROM airports WHERE name = :endAirport", array("endAirport" => $endAirport));
		
		$traject = new trajecten();
		$traject->TrajectID = 4;
		$traject->Airport1 = new airport();
		$traject->Airport1->id = airport::get();
		DbHandler::NonQuery("DELETE FROM traject WHERE traject_id = :trajectId", array("trajectId" => $trajectId));
		return $traject;
	}
	*/
}

?>