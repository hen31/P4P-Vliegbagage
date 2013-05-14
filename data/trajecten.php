<?php

/**
 * @author 
 * @copyright 2013
 */

require("includeAll.php");
class trajecten
{
    public function addTraject()
    {
        $startAirport = $_POST["startAirport"];
        $endAirport = $_POST("endAirport");
        
        $startAirportId = DbHandler::QueryScalar("SELECT airline_id FROM airline WHERE name = :startAirport", array("startAirport" => $startAirport));
        $endAirportId = DbHandler::QueryScalar("SELECT airline_id FROM airline WHERE name = :endAirport", array("endAirport" => $endAirport));
        
        DbHandler::NonQuery("INSERT INTO traject (airport_start_id, airport_end_id) VALUES(startAirportId, endAirportId)", array("startAirportId" => $startAirportId, "endAirportId" => $endAirportId));
    }
}

?>