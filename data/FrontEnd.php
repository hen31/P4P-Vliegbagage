<?php
require_once("includeAll.php");
class FrontEnd{
    //de vliegtuigmaatschapijen terug krijgen.
    static public function Search($beginAir, $endAir,$classSelected,$specialLuggage)
    {
        
        return null;
    }
    //Alle gebruikte vliegvelden ophalen
    static public function GetAirports()
    {
           $ids = DbHandler::Query("SELECT DISTINCT(airport_id) FROM airports WHERE (airport_id IN ( SELECT airport_start_id FROM traject GROUP BY airport_start_id) OR airport_id IN ( SELECT airport_stop_id FROM traject GROUP BY airport_stop_id)) ;");
      //$numbers =  array();
        $airports =  array();   
      for( $i =0;$i< count($ids);$i++)
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