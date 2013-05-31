<?php

/**
 * @auteur  Robert de Jong
 * @datum 31-5-2013
 */

class chargeExtraBag{
    
    public $chargeExtraBag_id;
    public $airline;
    public $number;
    public $costs;
    
    public function __construct($id, $airline, $number, $costs){
        $this->chargeExtraBag_id = $id;
        $this->airline = $airline;
        $this->number = $number;
        $this->costs = $costs;
    }
    
    public static function edit($charge){        
        DbHandler::NonQuery("UPDATE `chargeExtraBag` SET `airline` = :airline, `number` = :number, `costs` = :costs WHERE `ChargeExtraBag_id` = :id", array("airline" => $charge->airline, "number" => $charge->number, "costs" => $charge->costs, "id" => $charge->chargeExtraBag_id));
    }
}

?>