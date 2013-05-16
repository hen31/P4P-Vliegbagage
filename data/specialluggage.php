<?php
/**
* @Auteur Ivar de Lange
* @Datum 13-5-2013
*/

require ("includeAll.php");

class SpecialLuggage
{
    public $AirlineID;
    public $SpecialLuggage;
    public $Notes;
    
    public static function AddItem($AirlineID, $SpecialLuggageID, $Notes)
    {
        Dbhandler::NonQuery("INSERT INTO specialluggage (airline_id, special_id, note VALUES (AirlineId, LuggageId, Ntes)", 
                            array ("AirlineId" => $AirlineID, "LuggageId" => $SpecialLuggageID, "Ntes" => $Notes));

    }

}


?>