<?php
require_once("includeAll.php");
class FrontEnd{
    
    static public function Search($beginAir, $endAir,$classSelected,$specialLuggage)
    {
        
        return null;
    }
    static public function GetAirports()
    {
        $ids = DbHandler::Query("SELECT airport_start_id, airport_stop_id FROM traject GROUP BY airport_start_id,airport_stop_id;");
      $numbers =  array();
      for( $i =0;$i< count($ids);$i++)
      {
        $numbers[] = $ids[$i]["airport_start_id"];
        $numbers[] = $ids[$i]["airport_stop_id"];
      }
      
  $numbers =     array_unique($numbers); 
  $airports =  array();   
  for($c = 0;$c< count($numbers);$c++)
  {
    $airports[] = airports::GetAirportByID($numbers[$c]);
    
    }
        return $airports;
    }
    
}

?>