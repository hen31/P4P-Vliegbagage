<?php
/**
 * @Auteur Hendrik de Jonge
 * @Datum 21-5-2013
 * @uses Wordt gebruikt om alle gegevens voor index.php op te halen
 */
require_once ("includeAll.php");
class FrontEnd
{
    //eindpunten vanaf begin punt ophalen
    static public function GetAirportsEndByStart($begin)
    {
        $ids = DbHandler::Query("SELECT DISTINCT(airport_id) FROM airports WHERE ( airport_id IN ( SELECT airport_stop_id FROM traject WHERE airport_start_id=:id  GROUP BY airport_stop_id)) ORDER BY airports.name ASC ;",
            array("id" => $begin));

        $airports = array();

        for ($i = 0; $i < count($ids); $i++) {
            $airports[] = airports::GetAirportByID($ids[$i]["airport_id"]);
        }

        return $airports;
    }


    //de vliegtuigmaatschapijen terug krijgen.
    static public function Search($beginAir, $endAir, $classSelected, $specialLuggage)
    {
        //als twee specifieke  vliegvelden zijn geselecteerd specifieke query uitvoeren
        if (validator::isString($beginAir) == false && validator::isString($endAir) == false) {
            if ($beginAir->AirportID == $endAir->AirportID) {
                return null;
            }
            //Zoek query uitvoeren
            $results = DbHandler::Query("SELECT Airline_id FROM airline WHERE  Airline_id IN (SELECT airline_id FROM trajectairline WHERE Traject_id = (SELECT traject_id FROM traject WHERE airport_start_id=:start AND airport_stop_id=:stop));",
                array("start" => $beginAir->AirportID, "stop" => $endAir->AirportID));

            if (count($results) == 0) {
                return null;
            } else {
                $airlines = array();
                for ($c = 0; $c < count($results); $c++) {
                    $exists = false;
                    $airline = airline::get_airline($results[$c]["Airline_id"], $classSelected);
                    if ($airline->classes != null) {
                        if (count($specialLuggage) > 0) {
                            for ($i = 0; $i < count($specialLuggage); $i++) {
                                if (SpecialLuggage::GetCombo($airline->airline_id, $specialLuggage[$i]->
                                    specialluggage_id) == null) {
                                    $exists = true;
                                    break;
                                }


                            }

                        }
                        if ($exists == false) {
                            $airlines[] = $airline;

                        }
                    }
                }

                return $airlines;
            }
        } else {
            $sqlQuert = '';
            //twee keer alle vliegveleden van een stad geselecteerd
            if (validator::isString($beginAir) && validator::isString($endAir)) {
                if ($beginAir == $endAir) {
                    return null;
                }
                $sqlQuert = "SELECT DISTINCT (Airline_id) FROM airline WHERE  Airline_id IN (SELECT airline_id FROM trajectairline WHERE Traject_id IN (SELECT traject_id FROM traject WHERE airport_start_id IN (SELECT airport_id FROM airports WHERE City=:start) AND airport_stop_id IN (SELECT airport_id FROM airports WHERE City=:stop))";
                $results = DbHandler::Query($sqlQuert, array("start" => $beginAir, "stop" => $endAir));
            }

            //eind bestemming is alle vliegvelden van een stad
            else
                if (validator::isString($beginAir) && !validator::isString($endAir)) {
                    if ($endAir->AirportCity == $beginAir) {
                        return null;
                    }
                    $sqlQuert = "SELECT DISTINCT (Airline_id) FROM airline WHERE  Airline_id IN (SELECT airline_id FROM trajectairline WHERE Traject_id IN (SELECT traject_id FROM traject WHERE airport_start_id IN (SELECT airport_id FROM airports WHERE City=:start)  AND airport_stop_id =:stop))";
                    $results = DbHandler::Query($sqlQuert, array("start" => $beginAir, "stop" => $endAir->
                            AirportID));

                }


            //vertrekpunt bestemming is alle vliegvelden van een stad
                else
                    if (!validator::isString($beginAir) && validator::isString($endAir)) {
                        if ($beginAir->AirportCity == $endAir) {
                            return null;
                        }
                        $sqlQuert = "SELECT DISTINCT (Airline_id) FROM airline WHERE  Airline_id IN (SELECT airline_id FROM trajectairline WHERE Traject_id IN (SELECT traject_id FROM traject WHERE airport_stop_id IN (SELECT airport_id FROM airports WHERE City=:stop)  AND airport_start_id =:start))";
                        $results = DbHandler::Query($sqlQuert, array("start" => $beginAir->AirportID,
                                "stop" => $endAir));
                    }

            if (count($results) == 0) {
                return null;
            } else {
                $airlines = array();
                for ($c = 0; $c < count($results); $c++) {
                    $exists = false;
                    $airline = airline::get_airline($results[$c]["Airline_id"], $classSelected);
                    if ($airline->classes != null) {
                        if (count($specialLuggage) > 0) {
                            for ($i = 0; $i < count($specialLuggage); $i++) {
                                if (SpecialLuggage::GetCombo($airline->airline_id, $specialLuggage[$i]->
                                    specialluggage_id) == null) {
                                    $exists = true;
                                    break;
                                }


                            }

                        }
                        if ($exists == false) {
                            $airlines[] = $airline;

                        }
                    }

                }
                return $airlines;
            }
        }
    }
    static public function GetAirportsEnd()
    {
        $ids = DbHandler::Query("SELECT DISTINCT(airport_id) FROM airports WHERE ( airport_id IN ( SELECT airport_stop_id FROM traject GROUP BY airport_stop_id)) ORDER BY airports.name ASC ;");

        $airports = array();
        for ($i = 0; $i < count($ids); $i++) {
            $airports[] = airports::GetAirportByID($ids[$i]["airport_id"]);

        }


        return $airports;
    }
    static public function GetAirportsBegin()
    {
        $ids = DbHandler::Query("SELECT DISTINCT(airport_id) FROM airports WHERE (airport_id IN ( SELECT airport_start_id FROM traject GROUP BY airport_start_id)) ORDER BY airports.name ASC;");

        $airports = array();
        for ($i = 0; $i < count($ids); $i++) {
            $airports[] = airports::GetAirportByID($ids[$i]["airport_id"]);

        }


        return $airports;
    }
    //Alle gebruikte vliegvelden ophalen
    static public function GetAirports()
    {
        $ids = DbHandler::Query("SELECT DISTINCT(airport_id) FROM airports WHERE (airport_id IN ( SELECT airport_start_id FROM traject GROUP BY airport_start_id) OR airport_id IN ( SELECT airport_stop_id FROM traject GROUP BY airport_stop_id)) ;");

        $airports = array();
        for ($i = 0; $i < count($ids); $i++) {
            $airports[] = airports::GetAirportByID($ids[$i]["airport_id"]);

        }


        return $airports;
    }

}

?>