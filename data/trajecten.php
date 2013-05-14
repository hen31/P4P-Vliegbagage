<?php

/**
 * @author 
 * @copyright 2013
 */

require("includeAll.php");
class trajecten
{
    $TrajectID;
    $Airport1;
    $Airport2;
    
    public function AddItem($startAirport, $stopAirport)
    {
        $startAirportId = DbHandler::QueryScalar("SELECT airline_id FROM airline WHERE name = :startAirport", array("startAirport" => $startAirport));
        $endAirportId = DbHandler::QueryScalar("SELECT airline_id FROM airline WHERE name = :endAirport", array("endAirport" => $endAirport));
        
        DbHandler::NonQuery("INSERT INTO traject (airport_start_id, airport_end_id) VALUES(startAirportId, endAirportId)", array("startAirportId" => $startAirportId, "endAirportId" => $endAirportId));
    }
    
    public function RemoveItem($trajectId)
    {
        DbHandler::NonQuery("DELETE FROM traject WHERE traject_id = :trajectId", array("trajectId" => $trajectId);
    }
    
    public function GetTraject($trajectId)
    {
        DbHandler::QueryScalar()
    }
}

?>