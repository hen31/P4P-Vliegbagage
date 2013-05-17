<?php

/**
 * @Auteur Robert de Jong
 * @Datum 13-5-2013
 */

require_once("includeAll.php");

class airline{
    public $airline_id;
    public $name;
    public $logo;
    public $OverweightChargeG;
    public $OverweightChargeBag;
    public $ChargeExtraBag;
    public $OversizeCharge;
    
    public $classes;
    
    public function __construct($airline, $classes){
        $this->airline_id = $airline["airline_id"];
        $this->name = $airline["name"];
        $this->logo = $airline["logo"];
        $this->OverweightChargeG = $airline["OverweightChargeG"];
        $this->OverweightChargeBag = $airline["OverweightChargeBag"];
        $this->ChargeExtraBag = $airline["ChargeExtraBag"];
        $this->OversizeCharge = $airline["OversizeCharge"];
        
        if(count($classes) > 0){
            foreach($classes as $class){
                $this->classes[] = new airlineclass($class);
            }
        }
    }
    
    public static function get_airline($airline_id, $class_number){
        $airline = DbHandler::Query("SELECT * FROM `airline` WHERE `airline_id` = :airline_id", array("airline_id" => $airline_id));
        $classes = DbHandler::Query("SELECT * FROM `airlineclass` WHERE `airline` = :airline", array("airline" => $airline_id));
        if(count($airline) == 0){
            return null;
        }
        
        return new airline($airline[0], $classes);
    }
    
    public static function get_airlines(){
        $result_airlines = DbHandler::Query("SELECT * FROM `airline`");
        if(count($result_airlines) == 0){
            return null;
        }
        $airlines = array();
        foreach($result_airlines as $airline){
            $classes = DbHandler::Query("SELECT * FROM `airlineclass` WHERE `airline` = :airline", array("airline" => $airline["airline_id"]));
            $airlines[] = new airline($airline, $classes);
        }
        return $airlines;
    }
    
    public static function edit_airline($airline){
        $airline_update = "";
        $airline_update_values["airline_id"] = $airline->airline_id;
        
        foreach($airline as $property => $value){
            if($property != "airline_id" && $property != "classes"){
                $airline_update .= "`" .$property ."` = :" .$property .",";
                $airline_update_values[$property] = $value;    
            }
        }
        $airline_update = rtrim($airline_update, ",");
        DbHandler::NonQuery("UPDATE `airline` SET " .$airline_update ." WHERE `airline_id` = :airline_id", $airline_update_values);
        foreach($airline->classes as $class){
            airlineclass::edit_class($class);
        }
        
    }
    
    public static function add_airline_without_class($name,$logo,$OverweightChargeG,$OverweightChargeBag,$ChargeExtraBag,$OversizeCharge){
        DbHandler::NonQuery("INSERT INTO `airline` (`name`, `logo`, `OverweightChargeG`, `OverweightChargeBag` `ChargeExtraBag`, `OversizeCharge`) VALUES(:name, :logo, :OverweightChargeG, :OverweightChargeBag, :ChargeExtraBag, :OversizeCharge)", array("name" => $name, "logo" => $logo, "OverweightChargeG" => $OverweightChargeG, "OverweightChargeBag" => $OverweightChargeBag, "OversizeCharge" => $OversizeCharge));
    }
    
    public static function add_airline_with_class($name,$logo,$OverweightChargeG,$OverweightChargeBag,$ChargeExtraBag,$OversizeCharge,$classnumber,$pcsHL,$MaxWeightHL,$sizeLenghtHL,$sizeHeightHL,$SizeWidthHL,$sizeTotalHL,$LaptopAllowedHL,$pcsInfantHL,$strollerAllowedHL,$pcsLuggageInfant,$pcsLuggageInfantMaxWeight,$pcsLuggage,$maxWeightLuggage,$LoyaltyProgramme,$LPextraPcsLuggage,$LPextraWeightLuggage,$AbsoluteMaxPerItem,$sizeLenghtPerItem,$sizeHeightPerItem,$sizeWidthPerItem,$sizeTotalPerItem,$Pooling,$FreeWheelChair,$FreeServiceDog,$PetsAllowed,$MaxWeightPet,$sizeLenghtPet,$sizeHeightPet,$sizeWidthPet,$sizeTotalPet,$DeclarationOfValue,$MaxDeclarationOfValue){
        DbHandler::NonQuery("INSERT INTO `airline` (`name`, `logo`, `OverweightChargeG`, `OverweightChargeBag` `ChargeExtraBag`, `OversizeCharge`) VALUES(:name, :logo, :OverweightChargeG, :OverweightChargeBag, :ChargeExtraBag, :OversizeCharge)", array("name" => $name, "logo" => $logo, "OverweightChargeG" => $OverweightChargeG, "OverweightChargeBag" => $OverweightChargeBag, "OversizeCharge" => $OversizeCharge));
        $id = DbHandler::Query("SELECT LAST_INSERT_ID() FROM `airline`");
        DbHandler::NonQuery("INSERT INTO `airlineclass` (`airline`, `classnumber`, `pcsHL`, `MaxWeightHL`, `sizeLenghtHL`, `sizeHeightHL`, `SizeWidthHL`, `sizeTotalHL`, `LaptopAllowedHL`, `pcsInfantHL`, `strollerAllowedHL`, `pcsLuggageInfant`, `pcsLuggageInfantMaxWeight`, `pcsLuggage`, `maxWeightLuggage`, `LoyaltyProgramme`, `LPextraPcsLuggage`, `LPextraWeightLuggage`, `AbsoluteMaxPerItem`, `sizeLenghtPerItem`, `sizeHeightPerItem`, `sizeWidthPerItem`, `sizeTotalPerItem`, `Pooling`, `FreeWheelChair`, `FreeServiceDog`, `PetsAllowed`, `MaxWeightPet`, `sizeLenghtPet`, `sizeHeightPet`, `sizeWidthPet`, `sizeTotalPet`, `DeclarationOfValue`, `MaxDeclarationOfValue`) VALUES()",
            array("airline" => $id,
            "classnumber" => $classnumber,
            "pcsHL" => $pcsHL,
            "MaxWeightHL" => $MaxWeightHL,
            "sizeLenghtHL" => $sizeLenghtHL,
            "sizeHeightHL" => $sizeHeightHL,
            "SizeWidthHL" => $SizeWidthHL,
            "sizeTotalHL" => $sizeTotalHL,
            "LaptopAllowedHL" => $LaptopAllowedHL,
            "pcsInfantHL" => $pcsInfantHL,
            "strollerAllowedHL" => $strollerAllowedHL, 
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
            "MaxDeclarationOfValue" => $MaxDeclarationOfValue));
    }
    
    public static function add_class($airline_id, $name,$logo,$OverweightChargeG,$OverweightChargeBag,$ChargeExtraBag,$OversizeCharge,$classnumber,$pcsHL,$MaxWeightHL,$sizeLenghtHL,$sizeHeightHL,$SizeWidthHL,$sizeTotalHL,$LaptopAllowedHL,$pcsInfantHL,$strollerAllowedHL,$pcsLuggageInfant,$pcsLuggageInfantMaxWeight,$pcsLuggage,$maxWeightLuggage,$LoyaltyProgramme,$LPextraPcsLuggage,$LPextraWeightLuggage,$AbsoluteMaxPerItem,$sizeLenghtPerItem,$sizeHeightPerItem,$sizeWidthPerItem,$sizeTotalPerItem,$Pooling,$FreeWheelChair,$FreeServiceDog,$PetsAllowed,$MaxWeightPet,$sizeLenghtPet,$sizeHeightPet,$sizeWidthPet,$sizeTotalPet,$DeclarationOfValue,$MaxDeclarationOfValue){
        DbHandler::NonQuery("INSERT INTO `airlineclass` (`airline`, `classnumber`, `pcsHL`, `MaxWeightHL`, `sizeLenghtHL`, `sizeHeightHL`, `SizeWidthHL`, `sizeTotalHL`, `LaptopAllowedHL`, `pcsInfantHL`, `strollerAllowedHL`, `pcsLuggageInfant`, `pcsLuggageInfantMaxWeight`, `pcsLuggage`, `maxWeightLuggage`, `LoyaltyProgramme`, `LPextraPcsLuggage`, `LPextraWeightLuggage`, `AbsoluteMaxPerItem`, `sizeLenghtPerItem`, `sizeHeightPerItem`, `sizeWidthPerItem`, `sizeTotalPerItem`, `Pooling`, `FreeWheelChair`, `FreeServiceDog`, `PetsAllowed`, `MaxWeightPet`, `sizeLenghtPet`, `sizeHeightPet`, `sizeWidthPet`, `sizeTotalPet`, `DeclarationOfValue`, `MaxDeclarationOfValue`) VALUES()",
            array("airline" => $airline_id,
            "classnumber" => $classnumber,
            "pcsHL" => $pcsHL,
            "MaxWeightHL" => $MaxWeightHL,
            "sizeLenghtHL" => $sizeLenghtHL,
            "sizeHeightHL" => $sizeHeightHL,
            "SizeWidthHL" => $SizeWidthHL,
            "sizeTotalHL" => $sizeTotalHL,
            "LaptopAllowedHL" => $LaptopAllowedHL,
            "pcsInfantHL" => $pcsInfantHL,
            "strollerAllowedHL" => $strollerAllowedHL, 
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
            "MaxDeclarationOfValue" => $MaxDeclarationOfValue));
    }
    
    public static function remove_class($class_id){
        DbHandler::NonQuery("DELETE FROM `airlineclass` WHERE `class_id` = :id", array("id" => $class_id));
    }
    
    public static function remove_airline($airline_id){
        DbHandler::NonQuery("DELETE FROM `airline` WHERE `airline_id` = :id", array("id" => $airline_id));
        DbHandler::NonQuery("DELETE FROM `airlineclass` WHERE `airline` = :id", array("id" => $airline_id));
    }
}
?>