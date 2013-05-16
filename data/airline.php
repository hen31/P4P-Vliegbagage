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
    
    public $class_id;
    public $airline;
    public $classnumber;
    public $pcsHL;
    public $MaxWeightHL;
    public $sizeLenghtHL;
    public $sizeHeightHL;
    public $SizeWidthHL;
    public $sizeTotalHL;
    public $LaptopAllowedHL;
    public $pcsInfantHL;
    public $strollerAllowedHL;
    public $pcsLuggageInfant;
    public $pcsLuggageInfantMaxWeight;
    public $pcsLuggage;
    public $maxWeightLuggage;
    public $LoyaltyProgramme;
    public $LPextraPcsLuggage;
    public $LPextraWeightLuggage;
    public $AbsoluteMaxPerItem;
    public $sizeLenghtPerItem;
    public $sizeHeightPerItem;
    public $sizeWidthPerItem;
    public $sizeTotalPerItem;
    public $Pooling;
    public $FreeWheelChair;
    public $FreeServiceDog;
    public $PetsAllowed;
    public $MaxWeightPet;
    public $sizeLenghtPet;
    public $sizeHeightPet;
    public $sizeWidthPet;
    public $sizeTotalPet;
    public $DeclarationOfValue;
    public $MaxDeclarationOfValue;
    
    public function __construct($airline){
        $this->airline_id = $airline["airline_id"];
        $this->name = $airline["name"];
        $this->logo = $airline["logo"];
        $this->OverweightChargeG = $airline["OverweightChargeG"];
        $this->OverweightChargeBag = $airline["OverweightChargeBag"];
        $this->ChargeExtraBag = $airline["ChargeExtraBag"];
        $this->OversizeCharge = $airline["OversizeCharge"];
        $this->class_id = $airline["class_id"];
        $this->airline = $airline["airline"];
        $this->classnumber = $airline["classnumber"];
        $this->pcsHL = $airline["pcsHL"];
        $this->MaxWeightHL = $airline["MaxWeightHL"];
        $this->sizeLenghtHL = $airline["sizeLenghtHL"];
        $this->sizeHeightHL = $airline["sizeHeightHL"];
        $this->SizeWidthHL = $airline["SizeWidthHL"];
        $this->sizeTotalHL = $airline["sizeTotalHL"];
        $this->LaptopAllowedHL = $airline["LaptopAllowedHL"];
        $this->pcsInfantHL = $airline["pcsInfantHL"];
        $this->strollerAllowedHL = $airline["strollerAllowedHL"];
        $this->pcsLuggageInfant = $airline["pcsLuggageInfant"];
        $this->pcsLuggageInfantMaxWeight = $airline["pcsLuggageInfantMaxWeight"];
        $this->pcsLuggage = $airline["pcsLuggage"];
        $this->maxWeightLuggage = $airline["maxWeightLuggage"];
        $this->LoyaltyProgramme = $airline["LoyaltyProgramme"];
        $this->LPextraPcsLuggage = $airline["LPextraPcsLuggage"];
        $this->LPextraWeightLuggage = $airline["LPextraWeightLuggage"];
        $this->AbsoluteMaxPerItem = $airline["AbsoluteMaxPerItem"];
        $this->sizeLenghtPerItem = $airline["sizeLenghtPerItem"];
        $this->sizeHeightPerItem = $airline["sizeHeightPerItem"];
        $this->sizeWidthPerItem = $airline["sizeWidthPerItem"];
        $this->sizeTotalPerItem = $airline["sizeTotalPerItem"];
        $this->Pooling = $airline["Pooling"];
        $this->FreeWheelChair = $airline["FreeWheelChair"];
        $this->FreeServiceDog = $airline["FreeServiceDog"];
        $this->PetsAllowed = $airline["PetsAllowed"];
        $this->MaxWeightPet = $airline["MaxWeightPet"];
        $this->sizeLenghtPet = $airline["sizeLenghtPet"];
        $this->sizeHeightPet = $airline["sizeHeightPet"];
        $this->sizeWidthPet = $airline["sizeWidthPet"];
        $this->sizeTotalPet = $airline["sizeTotalPet"];
        $this->DeclarationOfValue = $airline["DeclarationOfValue"];
        $this->MaxDeclarationOfValue = $airline["MaxDeclarationOfValue"];
    }
    
    public static function get_airline($airline_id, $class_number){
        $airline = DbHandler::Query("SELECT * FROM `airline`,`airlineclass` WHERE `airline`.`airline_id` = :airline_id AND `airlineclass`.`airline` = :airline_id AND `airlineclass`.`classnumber` = :class_number;", array("airline_id" => $airline_id, "class_number" => $class_number));
  
        if(count($airline) == 0){
            return null;
        }
        
        return new airline($airline[0]);
    }
    
    public static function get_airlines(){
        $result_airlines = DbHandler::Query("SELECT * FROM `airline`,`airlineclass` WHERE `airline`.`airline_id` = `airlineclass`.`airline`");
        if(count($result_airlines) == 0){
            return null;
        }
        $airlines = array();
        foreach($result_airlines as $airline){
            $airlines[] = new airline($airline);
        }
        return $airlines;
    }
    
    public static function edit_airline($airline){
        $airline_update = "";
        $airlineclass_update = "";
        $airline_array;
        $airlineclass_array;
        $count = 1;
        foreach($airline as $property => $value){
            if($count != 1 && $count != 8){
                if($count < 8){
                    $airline_update .= " `" .$property ."` = :" .$property .",";
                    $airline_array[$property] = $value;
                }
                else{
                    $airlineclass_update .= " `" .$property ."` = :" .$property. ",";
                    $airlineclass_array[$property] = $value;
                }
            }            
            $count++;
        }
        $airline_update = rtrim($airline_update, ",");
        $airlineclass_update = rtrim($airlineclass_update, ",");
        
        $airline_array["airline_id"] = $airline->airline_id;
        $airlineclass_array["class_id"] = $airline->class_id;

        DbHandler::NonQuery("UPDATE `airline` SET" .$airline_update ." WHERE `airline_id` = :airline_id", $airline_array);
        DbHandler::NonQuery("UPDATE `airlineclass` SET" .$airlineclass_update ." WHERE `class_id` = :class_id", $airlineclass_array);
    }
    
    public static function add_airline($name,$logo,$OverweightChargeG,$OverweightChargeBag,$ChargeExtraBag,$OversizeCharge,$classnumber,$pcsHL,$MaxWeightHL,$sizeLenghtHL,$sizeHeightHL,$SizeWidthHL,$sizeTotalHL,$LaptopAllowedHL,$pcsInfantHL,$strollerAllowedHL,$pcsLuggageInfant,$pcsLuggageInfantMaxWeight,$pcsLuggage,$maxWeightLuggage,$LoyaltyProgramme,$LPextraPcsLuggage,$LPextraWeightLuggage,$AbsoluteMaxPerItem,$sizeLenghtPerItem,$sizeHeightPerItem,$sizeWidthPerItem,$sizeTotalPerItem,$Pooling,$FreeWheelChair,$FreeServiceDog,$PetsAllowed,$MaxWeightPet,$sizeLenghtPet,$sizeHeightPet,$sizeWidthPet,$sizeTotalPet,$DeclarationOfValue,$MaxDeclarationOfValue){
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