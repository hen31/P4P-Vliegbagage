<?php

/**
 * @auteur  Robert de Jong
 * @datum 31-5-2013
 */

class chargeExtraBag{
    //pulic properties
    public $chargeExtraBag_id;
    public $airline;
    public $number;
    public $costs;
    //constructor voor extra koffer prijs
    public function __construct($id, $airline, $number, $costs){
        $this->chargeExtraBag_id = $id;
        $this->airline = $airline;
        $this->number = $number;
        $this->costs = $costs;
    }
    //prijs aanpassen
    public static function edit($charge){        
        DbHandler::NonQuery("UPDATE `chargeextrabag` SET `airline` = :airline, `number` = :number, `costs` = :costs WHERE `ChargeExtraBag_id` = :id", array("airline" => $charge->airline, "number" => $charge->number, "costs" => $charge->costs, "id" => $charge->chargeExtraBag_id));
    }
    //een toevoegen
    public static function add($airline_id, $number, $costs){
        DbHandler::NonQuery("INSERT INTO `chargeextrabag` (`airline`, `number`, `costs`) VALUES (:airline, :number, :costs)", array("airline" => $airline_id, "number" => $number, "costs" => $costs));
    }
    //alle koffers van een vliegmaatschapij verwijderen
    public static function remove_all($airline_id){
        DbHandler::NonQuery("DELETE FROM `chargeextrabag` WHERE `airline` = :airline", array("airline" => $airline_id));
    }
}

?>