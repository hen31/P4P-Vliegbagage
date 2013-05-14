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
    public $OverweightExtraBag;
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
    
    public function __construct($airline_id,$name,$logo,$OverweightChargeG,$OverweightChargeBag,$OverweightExtraBag,$OversizeCharge,$class_id,$airline,$classnumber,$pcsHL,$MaxWeightHL,$sizeLenghtHL,$sizeHeightHL,$SizeWidthHL,$sizeTotalHL,$LaptopAllowedHL,$pcsInfantHL,$strollerAllowedHL,$pcsLuggageInfant,$pcsLuggageInfantMaxWeight,$pcsLuggage,$maxWeightLuggage,$LoyaltyProgramme,$LPextraPcsLuggage,$LPextraWeightLuggage,$AbsoluteMaxPerItem,$sizeLenghtPerItem,$sizeHeightPerItem,$sizeWidthPerItem,$sizeTotalPerItem,$Pooling,$FreeWheelChair,$FreeServiceDog,$PetsAllowed,$MaxWeightPet,$sizeLenghtPet,$sizeHeightPet,$sizeWidthPet,$sizeTotalPet,$DeclarationOfValue,$MaxDeclarationOfValue){
        $this->airline_id = $airline_id;
        $this->name = $name;
        $this->logo = $logo;
        $this->$OverweightChargeG = $OverweightChargeG;
        $this->OverweightChargeBag = $OverweightChargeBag;
        $this->OverweightExtraBag = $OverweightExtraBag;
        $this->OversizeCharge = $OversizeCharge;
        $this->class_id = $class_id;
        $this->airline = $airline;
        $this->classnumber = $classnumber;
        $this->pcsHL = $pcsHL;
        $this->MaxWeightHL = $MaxWeightHL;
        $this->sizeLenghtHL = $sizeLenghtHL;
        $this->sizeHeightHL = $sizeHeightHL;
        $this->SizeWidthHL = $SizeWidthHL;
        $this->sizeTotalHL = $sizeTotalHL;
        $this->LaptopAllowedHL = $LaptopAllowedHL;
        $this->pcsInfantHL = $pcsInfantHL;
        $this->strollerAllowedHL = $strollerAllowedHL;
        $this->pcsLuggageInfant = $pcsLuggageInfant;
        $this->pcsLuggageInfantMaxWeight = $pcsLuggageInfantMaxWeight;
        $this->pcsLuggage = $pcsLuggage;
        $this->maxWeightLuggage = $maxWeightLuggage;
        $this->LoyaltyProgramme = $LoyaltyProgramme;
        $this->LPextraPcsLuggage = $LPextraPcsLuggage;
        $this->LPextraWeightLuggage = $LPextraWeightLuggage;
        $this->AbsoluteMaxPerItem = $AbsoluteMaxPerItem;
        $this->sizeLenghtPerItem = $sizeLenghtPerItem;
        $this->sizeHeightPerItem = $sizeHeightPerItem;
        $this->sizeWidthPerItem = $sizeWidthPerItem;
        $this->sizeTotalPerItem = $sizeTotalPerItem;
        $this->Pooling = $Pooling;
        $this->FreeWheelChair = $FreeWheelChair;
        $this->FreeServiceDog = $FreeServiceDog;
        $this->PetsAllowed = $PetsAllowed;
        $this->MaxWeightPet = $MaxWeightPet;
        $this->sizeLenghtPet = $sizeLenghtPet;
        $this->sizeHeightPet = $sizeHeightPet;
        $this->sizeWidthPet = $sizeWidthPet;
        $this->sizeTotalPet = $sizeTotalPet;
        $this->DeclarationOfValue = $DeclarationOfValue;
        $this->MaxDeclarationOfValue = $MaxDeclarationOfValue;
    }
    
    public static function get_airline($airline_id, $classnumber){
        $airline = DbHandler::QueryScalar("SELECT * FROM `airline` WHERE `airline_id` = :id", array("id" => $airline_id));
        $airline_class = DbHandler::QueryScalar("SELECT * FROM `airlineclass` WHERE `classnumber` = :id AND `airline` = :airline_id", array("id" => $classnumber, "airline_id" => $airline_id));
        
        print_r($airline);
        print_r($airline_class);
        
        if(count($airline) == 0 || count($airline_class) == 0){
            return null;
        }
        
        return new airline($airline["airline_id"],$airline["name"],$airline["logo"],$airline["OverweightChargeG"],$airline["OverweightChargeBag"],$airline["OverweightExtraBag"],$airline["OversizeCharge"],$airline_class["class_id"],$airline_class["airline"],$airline_class["classnumber"],$airline_class["pcsHL"],$airline_class["MaxWeightHL"],$airline_class["sizeLenghtHL"],$airline_class["sizeHeightHL"],$airline_class["SizeWidthHL"],$airline_class["sizeTotalHL"],$airline_class["LaptopAllowedHL"],$airline_class["pcsInfantHL"],$airline_class["strollerAllowedHL"],$airline_class["pcsLuggageInfant"],$airline_class["pcsLuggageInfantMaxWeight"],$airline_class["pcsLuggage"],$airline_class["maxWeightLuggage"],$airline_class["LoyaltyProgramme"],$airline_class["LPextraPcsLuggage"],$airline_class["LPextraWeightLuggage"],$airline_class["AbsoluteMaxPerItem"],$airline_class["sizeLenghtPerItem"],$airline_class["sizeHeightPerItem"],$airline_class["sizeWidthPerItem"],$airline_class["sizeTotalPerItem"],$airline_class["Pooling"],$airline_class["FreeWheelChair"],$airline_class["FreeServiceDog"],$airline_class["PetsAllowed"],$airline_class["MaxWeightPet"],$airline_class["sizeLenghtPet"],$airline_class["sizeHeightPet"],$airline_class["sizeWidthPet"],$airline_class["sizeTotalPet"],$airline_class["DeclarationOfValue"],$airline_class["MaxDeclarationOfValue"]);
    }
}
?>