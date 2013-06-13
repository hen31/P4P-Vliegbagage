<?php

/**
 * @Auteur Robert de Jong
 * @Datum 13-5-2013
 * @uses Wordt gebruikt om airline's te maken, bewerken en verwijderen
 */

require_once ("includeAll.php");

class airline
{
    //public properties
    public $airline_id;
    public $name;
    public $logo;
    public $OverweightChargeG;
    public $OverweightChargeBag;
    public $ChargeExtraBag;
    public $OversizeCharge;
    public $iata;
    public $notes;
    //klassen voor de vliegmaatschapij
    public $classes;

    //constructir om alles toe te voegen
    public function __construct($airline, $classes, $chargeExtraBag)
    {
        $this->airline_id = $airline["airline_id"];
        $this->name = $airline["name"];
        if($airline["logo"] == ""){
            $this->logo = "default.png";
        }
        else{
            $this->logo = $airline["logo"];
        }
        $this->OverweightChargeG = $airline["OverweightChargeG"];
        $this->OverweightChargeBag = $airline["OverweightChargeBag"];
        
        $this->OversizeCharge = $airline["OversizeCharge"];
        $this->iata = $airline["iata"];
        $this->notes = $airline["notes"];
        //classes toevoegen
        if (count($classes) > 0) {
            foreach ($classes as $class) {
                $this->classes[] = new airlineclass($class);
            }
        }
        else{
            //zorgen dat er een array is om het in te stoppen
            $this->classes = array();
        }
        //extra kosten voor koffers ophalen
        if(count($chargeExtraBag) > 0){
            foreach($chargeExtraBag as $charge){
                $this->ChargeExtraBag[] = new chargeExtraBag($charge["ChargeExtraBag_id"], $charge["airline"], $charge["number"], $charge["costs"]);
            }
        }
        else{
            //lege array aanmaken voor de propertie
            $this->ChargeExtraBag = array();
        }
    }
    //vliegtuigmaatschapij  ophalen aan de hand van airline_id en de klasse
    public static function get_airline($airline_id, $class_number)
    {
        //query uitvoeren
        $airline = DbHandler::Query("SELECT * FROM `airline` WHERE `airline_id` = :airline_id",
            array("airline_id" => $airline_id));
            //kijken of er resultaat is
        if (count($airline) == 0) {
            return null;
        }
        //classes ophalen
        if($class_number == "all"){
            $classes = DbHandler::Query("SELECT * FROM `airlineclass` WHERE `airline` = :airline", array("airline" => $airline_id));
        }
        else{
            $classes = DbHandler::Query("SELECT * FROM `airlineclass` WHERE `airline` = :airline AND `classnumber` = :class_number",
                array("airline" => $airline_id, "class_number" => $class_number));
        }
        $charge = DbHandler::Query("SELECT * FROM `chargeExtraBag` WHERE `airline` = :airline", array("airline" => $airline_id));
        //terug geven
        return new airline($airline[0], $classes, $charge);
    }
    //airline ophalen aan de hand van de naam
    public static function get_airline_by_name($name){
        $result = DbHandler::Query("SELECT * FROM `airline` WHERE `name` = :name", array("name" => $name));
        if(count($result) < 1 ){
            return array();
        }
        $result_classes = DbHandler::Query("SELECT * FROM `airlineclass` WHERE `airline` = :airlineid", array("airlineid" => $result[0]["airline_id"]));
        $charge = DbHandler::Query("SELECT * FROM `chargeExtraBag` WHERE `airline` = :airlineid", array("airlineid" => $result[0]["airline_id"]));

        return new airline($result[0], $result_classes, $charge);
    }
    //alle vliegmaatschapijen ophalen
    public static function get_airlines($searchTerm = "", $start = null, $count = null)
    {
        if (is_int($start) && is_int($count)) {
            $result_airlines = DbHandler::QueryLimit("SELECT * FROM `airline` WHERE `name` LIKE :searchterm ORDER BY airline.name ASC",
                array("searchterm" => "%" . $searchTerm . "%"), $start, $count);
        } else {
            $result_airlines = DbHandler::Query("SELECT * FROM `airline` WHERE `name` LIKE :searchterm ORDER BY airline.name ASC",
                array("searchterm" => "%" . $searchTerm . "%"));
        }
        if (count($result_airlines) == 0) {
            return array();
        }
        $airlines = array();
        foreach ($result_airlines as $airline) {
            $classes = DbHandler::Query("SELECT * FROM `airlineclass` WHERE (`airline` = :airline);",
                array("airline" => $airline["airline_id"]));
                
            $charge = DbHandler::Query("SELECT * FROM `chargeExtraBag` WHERE `airline` = :airline", array("airline" => $airline["airline_id"]));
            
            $airlines[] = new airline($airline, $classes, $charge);
        }
        return $airlines;
    }
    //vliegtuig  maatschapij bewerken
    public static function edit_airline($airline)
    {
        $airline_update = "";
        $airline_update_values["airline_id"] = $airline->airline_id;

        foreach ($airline as $property => $value) {
            if ($property != "airline_id" && $property != "classes" && $property != "ChargeExtraBag") {
                $airline_update .= "`" . $property . "` = :" . $property . ",";
                $airline_update_values[$property] = $value;
            }
        }
        $airline_update = rtrim($airline_update, ",");
        DbHandler::NonQuery("UPDATE `airline` SET " . $airline_update .
            " WHERE `airline_id` = :airline_id", $airline_update_values);
        
        if(count($airline->classes) > 0){        
            foreach ($airline->classes as $class) {
                airlineclass::edit_class($class);
            }
        }
        if(count($airline->ChargeExtraBag) > 0){
            foreach($airline->ChargeExtraBag as $charge){
                chargeExtraBag::edit($charge);
            }
        }
    }
    //checken of er een vliegmaatschapij bestaat met de zelfde naam
    public static function airline_name_exists($name)
    {
        $result = DbHandler::Query("SELECT `airline_id` FROM `airline` WHERE `name` = :name",
            array("name" => $name));
        if (count($result) > 0) {
            return true;
        }
        return false;
    }
    //vliegmaatschapij toevoegen zonder klasse
    public static function add_airline_without_class($name, $logo, $OverweightChargeG,
        $OverweightChargeBag, $ChargeExtraBag, $OversizeCharge, $iata, $notes) ///////////////////////////////////
    {
        if (airline::airline_name_exists($name)) {
            return false;
        }
        DbHandler::NonQuery("INSERT INTO `airline` (`name`, `logo`, `OverweightChargeG`, `OverweightChargeBag`, `OversizeCharge`, `iata`, `notes`) VALUES(:name, :logo, :OverweightChargeG, :OverweightChargeBag, :OversizeCharge, :iata, :notes)",
            array(
            "name" => $name,
            "logo" => $logo,
            "OverweightChargeG" => $OverweightChargeG,
            "OverweightChargeBag" => $OverweightChargeBag,
            "OversizeCharge" => $OversizeCharge,
            "iata" => $iata,
            "notes" => $notes));
        
        $airline_id = DbHandler::Query("SELECT `airline_id` FROM `airline` WHERE `name` = :name", array("name" => $name));
        if(count($airline_id) < 1){
            return false;
        }
        
        foreach($ChargeExtraBag as $number => $costs){
            chargeExtraBag::add($airline_id[0]["airline_id"], $number, $costs);
            //DbHandler::NonQuery("INSERT INTO `chargeExtraBag` (`airline`, `number`, `costs`) VALUES(:airline, :number, :costs)", array("airline" => $airline_id[0]["airline_id"], "number" => $number, "costs" => $costs));
        }
    }
// vliegmaatschapij toevoegen met een klasse
    public static function add_airline_with_class($name, $logo, $OverweightChargeG,
        $OverweightChargeBag, $ChargeExtraBag, $OversizeCharge, $iata, $notes, $classnumber, $pcsHL,///////////////////////////////////////////////////
        $MaxWeightHL, $sizeLenghtHL, $sizeHeightHL, $SizeWidthHL, $sizeTotalHL, $LaptopAllowedHL,
        $pcsInfantHL, $pcsLuggageInfant, $pcsLuggageInfantMaxWeight,
        $pcsLuggage, $maxWeightLuggage, $LoyaltyProgramme, $LPextraPcsLuggage, $LPextraWeightLuggage,
        $AbsoluteMaxPerItem, $sizeLenghtPerItem, $sizeHeightPerItem, $sizeWidthPerItem,
        $sizeTotalPerItem, $Pooling, $FreeWheelChair, $FreeServiceDog, $PetsAllowed, $MaxWeightPet,
        $sizeLenghtPet, $sizeHeightPet, $sizeWidthPet, $sizeTotalPet, $DeclarationOfValue,
        $MaxDeclarationOfValue, $petsAllowedHL, $MaxWeightInfantHL, $CostsPet)
    {
        if (airline::airline_name_exists($name)) {
            return false;
        }
        DbHandler::NonQuery("INSERT INTO `airline` (`name`, `logo`, `OverweightChargeG`, `OverweightChargeBag`, `OversizeCharge`, `iata`, `notes`) VALUES(:name, :logo, :OverweightChargeG, :OverweightChargeBag, :OversizeCharge, :iata, :notes)",
            array(
            "name" => $name,
            "logo" => $logo,
            "OverweightChargeG" => $OverweightChargeG,
            "OverweightChargeBag" => $OverweightChargeBag,
            "OversizeCharge" => $OversizeCharge,
            "iata" => $iata,
            "notes" => $notes));

        $id = DbHandler::Query("SELECT `airline_id` FROM `airline` WHERE `name` = :name",
            array("name" => $name));
            
        if(count($id) < 1){
            return false;
        }
        
        foreach($ChargeExtraBag as $number => $costs){
            chargeExtraBag::add($id[0]["airline_id"], $number, $costs);
            //DbHandler::NonQuery("INSERT INTO `chargeExtraBag` (`airline`, `number`, `costs`) VALUES(:airline, :number, :costs)", array("airline" => $id[0]["airline_id"], "number" => $number, "costs" => $costs));
        }

        DbHandler::NonQuery("INSERT INTO `airlineclass` (`airline`, `classnumber`, `pcsHL`, `MaxWeightHL`, `sizeLenghtHL`, `sizeHeightHL`, `SizeWidthHL`, `sizeTotalHL`, `LaptopAllowedHL`, `pcsInfantHL`, `pcsLuggageInfant`, `pcsLuggageInfantMaxWeight`, `pcsLuggage`, `maxWeightLuggage`, `LoyaltyProgramme`, `LPextraPcsLuggage`, `LPextraWeightLuggage`, `AbsoluteMaxPerItem`, `sizeLenghtPerItem`, `sizeHeightPerItem`, `sizeWidthPerItem`, `sizeTotalPerItem`, `Pooling`, `FreeWheelChair`, `FreeServiceDog`, `PetsAllowed`, `MaxWeightPet`, `sizeLenghtPet`, `sizeHeightPet`, `sizeWidthPet`, `sizeTotalPet`, `DeclarationOfValue`, `MaxDeclarationOfValue`, `petsAllowedHL`, `MaxWeightInfantHL`, `CostsPet`) VALUES(:airline, :classnumber, :pcsHL, :MaxWeightHL, :sizeLenghtHL, :sizeHeightHL, :SizeWidthHL, :sizeTotalHL, :LaptopAllowedHL, :pcsInfantHL, :pcsLuggageInfant, :pcsLuggageInfantMaxWeight, :pcsLuggage, :maxWeightLuggage, :LoyaltyProgramme, :LPextraPcsLuggage, :LPextraWeightLuggage, :AbsoluteMaxPerItem, :sizeLenghtPerItem, :sizeHeightPerItem, :sizeWidthPerItem, :sizeTotalPerItem, :Pooling, :FreeWheelChair, :FreeServiceDog, :PetsAllowed, :MaxWeightPet, :sizeLenghtPet, :sizeHeightPet, :sizeWidthPet, :sizeTotalPet, :DeclarationOfValue, :MaxDeclarationOfValue, :petsAllowedHL, :MaxWeightInfantHL, :CostsPet)",
            array(
            "airline" => $id[0]["airline_id"],
            "classnumber" => $classnumber,
            "pcsHL" => $pcsHL,
            "MaxWeightHL" => $MaxWeightHL,
            "sizeLenghtHL" => $sizeLenghtHL,
            "sizeHeightHL" => $sizeHeightHL,
            "SizeWidthHL" => $SizeWidthHL,
            "sizeTotalHL" => $sizeTotalHL,
            "LaptopAllowedHL" => $LaptopAllowedHL,
            "pcsInfantHL" => $pcsInfantHL,
            "pcsLuggageInfant" => $pcsLuggageInfant,
            "pcsLuggageInfantMaxWeight" => $pcsLuggageInfantMaxWeight,
            "pcsLuggage" => $pcsLuggage,
            "maxWeightLuggage" => $maxWeightLuggage,
            "LoyaltyProgramme" => $LoyaltyProgramme,
            "LPextraPcsLuggage" => $LPextraPcsLuggage,
            "LPextraWeightLuggage" => $LPextraWeightLuggage,
            "AbsoluteMaxPerItem" => $AbsoluteMaxPerItem,
            "sizeLenghtPerItem" => $sizeLenghtPerItem,
            "sizeHeightPerItem" => $sizeHeightPerItem,
            "sizeWidthPerItem" => $sizeWidthPerItem,
            "sizeTotalPerItem" => $sizeTotalPerItem,
            "Pooling" => $Pooling,
            "FreeWheelChair" => $FreeWheelChair,
            "FreeServiceDog" => $FreeServiceDog,
            "PetsAllowed" => $PetsAllowed,
            "MaxWeightPet" => $MaxWeightPet,
            "sizeLenghtPet" => $sizeLenghtPet,
            "sizeHeightPet" => $sizeHeightPet,
            "sizeWidthPet" => $sizeWidthPet,
            "sizeTotalPet" => $sizeTotalPet,
            "DeclarationOfValue" => $DeclarationOfValue,
            "MaxDeclarationOfValue" => $MaxDeclarationOfValue,
            "petsAllowedHL" => $petsAllowedHL,
            "MaxWeightInfantHL" => $MaxWeightInfantHL,
            "CostsPet" => $CostsPet));
    }
    //klasse toevoegen aan airline
    public static function add_class($airline_id, $classnumber, $pcsHL, $MaxWeightHL,
        $sizeLenghtHL, $sizeHeightHL, $SizeWidthHL, $sizeTotalHL, $LaptopAllowedHL, $pcsInfantHL,
        $pcsLuggageInfant, $pcsLuggageInfantMaxWeight, $pcsLuggage,
        $maxWeightLuggage, $LoyaltyProgramme, $LPextraPcsLuggage, $LPextraWeightLuggage,
        $AbsoluteMaxPerItem, $sizeLenghtPerItem, $sizeHeightPerItem, $sizeWidthPerItem,
        $sizeTotalPerItem, $Pooling, $FreeWheelChair, $FreeServiceDog, $PetsAllowed, $MaxWeightPet,
        $sizeLenghtPet, $sizeHeightPet, $sizeWidthPet, $sizeTotalPet, $DeclarationOfValue,
        $MaxDeclarationOfValue, $petsAllowedHL, $MaxWeightInfantHL, $CostsPet)
    {
        DbHandler::NonQuery("INSERT INTO `airlineclass` (`airline`, `classnumber`, `pcsHL`, `MaxWeightHL`, `sizeLenghtHL`, `sizeHeightHL`, `SizeWidthHL`, `sizeTotalHL`, `LaptopAllowedHL`, `pcsInfantHL`, `pcsLuggageInfant`, `pcsLuggageInfantMaxWeight`, `pcsLuggage`, `maxWeightLuggage`, `LoyaltyProgramme`, `LPextraPcsLuggage`, `LPextraWeightLuggage`, `AbsoluteMaxPerItem`, `sizeLenghtPerItem`, `sizeHeightPerItem`, `sizeWidthPerItem`, `sizeTotalPerItem`, `Pooling`, `FreeWheelChair`, `FreeServiceDog`, `PetsAllowed`, `MaxWeightPet`, `sizeLenghtPet`, `sizeHeightPet`, `sizeWidthPet`, `sizeTotalPet`, `DeclarationOfValue`, `MaxDeclarationOfValue`, `petsAllowedHL`, `MaxWeightInfantHL`, `CostsPet`) VALUES(:airline,:classnumber,:pcsHL,:MaxWeightHL,:sizeLenghtHL,:sizeHeightHL,:SizeWidthHL,:sizeTotalHL,:LaptopAllowedHL,:pcsInfantHL,:pcsLuggageInfant,:pcsLuggageInfantMaxWeight,:pcsLuggage,:maxWeightLuggage,:LoyaltyProgramme,:LPextraPcsLuggage,:LPextraWeightLuggage,:AbsoluteMaxPerItem,:sizeLenghtPerItem,:sizeHeightPerItem,:sizeWidthPerItem,:sizeTotalPerItem,:Pooling,:FreeWheelChair,:FreeServiceDog,:PetsAllowed,:MaxWeightPet,:sizeLenghtPet,:sizeHeightPet,:sizeWidthPet,:sizeTotalPet,:DeclarationOfValue,:MaxDeclarationOfValue,:petsAllowedHL, :MaxWeightInfantHL, :CostsPet)",
            array(
            "airline" => $airline_id,
            "classnumber" => $classnumber,
            "pcsHL" => $pcsHL,
            "MaxWeightHL" => $MaxWeightHL,
            "sizeLenghtHL" => $sizeLenghtHL,
            "sizeHeightHL" => $sizeHeightHL,
            "SizeWidthHL" => $SizeWidthHL,
            "sizeTotalHL" => $sizeTotalHL,
            "LaptopAllowedHL" => $LaptopAllowedHL,
            "pcsInfantHL" => $pcsInfantHL,
            "pcsLuggageInfant" => $pcsLuggageInfant,
            "pcsLuggageInfantMaxWeight" => $pcsLuggageInfantMaxWeight,
            "pcsLuggage" => $pcsLuggage,
            "maxWeightLuggage" => $maxWeightLuggage,
            "LoyaltyProgramme" => $LoyaltyProgramme,
            "LPextraPcsLuggage" => $LPextraPcsLuggage,
            "LPextraWeightLuggage" => $LPextraWeightLuggage,
            "AbsoluteMaxPerItem" => $AbsoluteMaxPerItem,
            "sizeLenghtPerItem" => $sizeLenghtPerItem,
            "sizeHeightPerItem" => $sizeHeightPerItem,
            "sizeWidthPerItem" => $sizeWidthPerItem,
            "sizeTotalPerItem" => $sizeTotalPerItem,
            "Pooling" => $Pooling,
            "FreeWheelChair" => $FreeWheelChair,
            "FreeServiceDog" => $FreeServiceDog,
            "PetsAllowed" => $PetsAllowed,
            "MaxWeightPet" => $MaxWeightPet,
            "sizeLenghtPet" => $sizeLenghtPet,
            "sizeHeightPet" => $sizeHeightPet,
            "sizeWidthPet" => $sizeWidthPet,
            "sizeTotalPet" => $sizeTotalPet,
            "DeclarationOfValue" => $DeclarationOfValue,
            "MaxDeclarationOfValue" => $MaxDeclarationOfValue,
            "petsAllowedHL" => $petsAllowedHL,
            "MaxWeightInfantHL" => $MaxWeightInfantHL,
            "CostsPet" => $CostsPet));
    }
    //klasse verwijderen van airline
    public static function remove_class($airline_id, $class_number)
    {
        DbHandler::NonQuery("DELETE FROM `airlineclass` WHERE `classnumber` = :id AND `airline` = :airline_id", array("id" =>
                $class_number, "airline_id" => $airline_id));
    }
    //hele vliegmaatschapij verwijderen
    public static function remove_airline($airline_id)
    {
        DbHandler::NonQuery("DELETE FROM `airline` WHERE `airline_id` = :id", array("id" =>
                $airline_id));
        DbHandler::NonQuery("DELETE FROM `airlineclass` WHERE `airline` = :id", array("id" =>
                $airline_id));
        DbHandler::NonQuery("DELETE FROM `chargeextrabag` WHERE `airline` = :id", array("id" =>
                $airline_id));
        DbHandler::NonQuery("DELETE FROM `trajectairline` WHERE `airline_id` = :id", array("id" =>
                $airline_id));
        DbHandler::NonQuery("DELETE FROM `airlinespecialluggage` WHERE `airline_id` = :id", array("id" =>
                $airline_id));
    }
}
?>