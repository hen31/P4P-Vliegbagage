<?php

require_once ("includeAll.php");
class FrontEnd
{
    //de vliegtuigmaatschapijen terug krijgen.
    static public function Search($beginAir, $endAir, $classSelected, $specialLuggage)
    {
        $results = DbHandler::Query("SELECT Airline_id FROM airline WHERE  Airline_id IN (SELECT airline_id FROM trajectairline WHERE Traject_id = (SELECT traject_id FROM traject WHERE airport_start_id=:start AND airport_stop_id=:stop));",
            array("start" => $beginAir->AirportID, "stop" => $endAir->AirportID));

        if (count($results) == 0)
        {

            return null;
        } else
        {
            $airlines = array();
            for ($c = 0; $c < count($results); $c++)
            {
                $exists = false;
                $airline = airline::get_airline($results[$c]["Airline_id"], $classSelected);

                if (count($specialLuggage) > 0)
                {
                    for ($i = 0; $i < count($specialLuggage); $i++)
                    {
                        if (SpecialLuggage::GetCombo($airline->airline_id, $specialLuggage[$i]->id) == null)
                        {
                            $exists = false;
                            break;
                        }


                    }

                }
                if ($exists == false)
                {
                    $airlines[] = $airline;

                }
            }
         
            return $airlines;
        }
    }
    //Alle gebruikte vliegvelden ophalen
    static public function GetAirports()
    {
        $ids = DbHandler::Query("SELECT DISTINCT(airport_id) FROM airports WHERE (airport_id IN ( SELECT airport_start_id FROM traject GROUP BY airport_start_id) OR airport_id IN ( SELECT airport_stop_id FROM traject GROUP BY airport_stop_id)) ;");
        //$numbers =  array();
        $airports = array();
        for ($i = 0; $i < count($ids); $i++)
        {
            $airports[] = airports::GetAirportByID($ids[$i]["airport_id"]);
            //   $numbers[] = $ids[$i]["airport_start_id"];
            // $numbers[] = $ids[$i]["airport_stop_id"];
        }

        /* $numbers =     array_unique($numbers);
        $airports =  array();   
        for($c = 0;$c< count($numbers);$c++)
        {
        $airports[] = airports::GetAirportByID($numbers[$c]);
        
        }*/
        return $airports;
    }

}

?>