<?php
class airlineclass
{

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

    public function __construct($class)
    {
        $this->class_id = $class["class_id"];
        $this->airline = $class["airline"];
        $this->classnumber = $class["classnumber"];
        $this->pcsHL = $class["pcsHL"];
        $this->MaxWeightHL = $class["MaxWeightHL"];
        $this->sizeLenghtHL = $class["sizeLenghtHL"];
        $this->sizeHeightHL = $class["sizeHeightHL"];
        $this->SizeWidthHL = $class["SizeWidthHL"];
        $this->sizeTotalHL = $class["sizeTotalHL"];
        $this->LaptopAllowedHL = $class["LaptopAllowedHL"];
        $this->pcsInfantHL = $class["pcsInfantHL"];
        $this->strollerAllowedHL = $class["strollerAllowedHL"];
        $this->pcsLuggageInfant = $class["pcsLuggageInfant"];
        $this->pcsLuggageInfantMaxWeight = $class["pcsLuggageInfantMaxWeight"];
        $this->pcsLuggage = $class["pcsLuggage"];
        $this->maxWeightLuggage = $class["maxWeightLuggage"];
        $this->LoyaltyProgramme = $class["LoyaltyProgramme"];
        $this->LPextraPcsLuggage = $class["LPextraPcsLuggage"];
        $this->LPextraWeightLuggage = $class["LPextraWeightLuggage"];
        $this->AbsoluteMaxPerItem = $class["AbsoluteMaxPerItem"];
        $this->sizeLenghtPerItem = $class["sizeLenghtPerItem"];
        $this->sizeHeightPerItem = $class["sizeHeightPerItem"];
        $this->sizeWidthPerItem = $class["sizeWidthPerItem"];
        $this->sizeTotalPerItem = $class["sizeTotalPerItem"];
        $this->Pooling = $class["Pooling"];
        $this->FreeWheelChair = $class["FreeWheelChair"];
        $this->FreeServiceDog = $class["FreeServiceDog"];
        $this->PetsAllowed = $class["PetsAllowed"];
        $this->MaxWeightPet = $class["MaxWeightPet"];
        $this->sizeLenghtPet = $class["sizeLenghtPet"];
        $this->sizeHeightPet = $class["sizeHeightPet"];
        $this->sizeWidthPet = $class["sizeWidthPet"];
        $this->sizeTotalPet = $class["sizeTotalPet"];
        $this->DeclarationOfValue = $class["DeclarationOfValue"];
        $this->MaxDeclarationOfValue = $class["MaxDeclarationOfValue"];
    }

    public static function edit_class($class)
    {
        $class_update = "";
        $class_update_values["class_id"] = $class->class_id;

        foreach ($class as $property => $value) {
            if ($property != "class_id") {
                $class_update .= "`" . $property . "` = :" . $property . ",";
                $class_update_values[$property] = $value;
            }
        }

        $class_update = rtrim($class_update, ",");
        DbHandler::NonQuery("UPDATE `airlineclass` SET " . $class_update .
            " WHERE `class_id` = :class_id", $class_update_values);

    }
}
?>