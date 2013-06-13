<?php
/**
 * @author  Robert de Jong
 * @copyright 21-5-2013
 */
 
//Alle data classes includen
require_once ("../data/includeAll.php");
$titel = "Luchtvaartmaatschappijen";
require_once ("bovenkant.php");

$succes_airline = false;
$succes_class = false;
$edit_succes = false;
$error = null;

/**
 * display_error()
 * Functie die kijkt of er een error is geset voor de gegeven index name.
 * @param array $error Array die alle errors bevat per veld
 * @param string $indexname Naam van de index in de errors array
 * @return Html error string
 */
function display_error($error, $indexname){
    if(isset($error[$indexname])){
        return '<span class="error">' .$error[$indexname] ."</span>";
    }
}
/**
 * add_value()
 * Functie die een value attribute geeft met een waarde uit een $_POST of $_GET array
 * @param array $post De $_GET of $_POST variabele
 * @param string $postindex Naam van de index in de post array
 * @param bool $succes_var Succes variabele
 * @return Html value attribute met de waarde uit de post array
 */
function add_value($post, $postindex, $succes_var){
    if(!$succes_var && isset($post[$postindex])){
        return 'value="' .htmlspecialchars($post[$postindex]) .'"';
    }
}

/**
 * add_existing_value()
 * Functie die een bestaande waarde als value attribute geeft, tenzij de post variabele bestaat dan wordt die terug gegeven
 * @param string $value Bestaande waarde
 * @param array $post $_GET of $_POST array
 * @param string $postindex De index van de post array
 * @param bool $succes_var Succes variabele
 * @return Html attribute met de waarde van $value tenzij de post waarde isset
 */
function add_existing_value($value, $post, $postindex, $succes_var){
    if(isset($post[$postindex]) && !$succes_var){
        return 'value="' .htmlspecialchars($post[$postindex]) .'"';
    }
    else{
        return 'value="' .htmlspecialchars($value) .'"';
    }
}

/**
 * set_selected()
 * Functie die wordt gebruikt om bij een select een waarde te selecteren
 * @param array $post De $_GET of $_POST variabele
 * @param string $postindex Naam van de index in de post array
 * @param string $value De waarde die de post array met index moet hebben om selected te zijn
 * @param bool $succes_var Succes variabele
 * @return Html attribute die een <option> selecteerd
 */
function set_selected($post, $postindex, $value, $succes_var){
    if(!$succes_var && isset($post[$postindex]) && $post[$postindex] == $value){
        return 'selected="true"';
    }
}

/**
 * set_selected_on_set()
 * Functie die wordt gebruikt om bij een select een waarde te selecteren, als de post waarde niet isset dan wordt de waarde uit het object geselecteerd
 * @param array $post De $_GET of $_POST variabele
 * @param string $postindex Naam van de index in de post array
 * @param string $value De waarde die de post array met index moet hebben om selected te zijn
 * @param bool $succes_var Succes variabele
 * @param array $obValues Waarden in het object die gechecked moeten worden
 * @param object $object Object die gecontroleerd wordt
 * @param array $not Waarden die NIET geset moeten zijn
 * @return Html attribute die een <option> selecteerd
 */
function set_selected_on_set($post, $postindex, $value, $succes_var, $obValues, $object, $not = null, $all = false){
    if(!$succes_var && isset($post[$postindex]) && $post[$postindex] == $value){
        return 'selected="true"';
    }
    else{
        if($not != null){
            foreach($not as $notvalue){
                if($object->{$notvalue} != 0){
                    return;
                }
            }
        }
        foreach($obValues as $obValue){
            if($all){
                if($object->{$obValue} != 0){
                    return 'selected="true"';
                }
            }
            else{
                if($object->{$obValue} == 0){
                    return;
                }
            }
        }

        return 'selected="true"';
    }
}

/**
 * set_selected_tf()
 * Functie die wordt gebruikt om bij een select een waarde te selecteren, als de post waarde niet isset dan wordt gekeken of een property uit een object voldoet aan de obvalue
 * @param array $post De $_GET of $_POST variabele
 * @param string $postindex Index uit de post array
 * @param string $value De waarde die de post array met index moet hebben om selected te zijn
 * @param bool $succes_var Succes variabele
 * @param object $object Object die gecontroleerd wordt
 * @param string $property Property uit het object die gechecked wordt
 * @param string $obvalue Value die de property uit het object moet hebben
 * @return Html attribute die een <option> selecteerd
 */
function set_selected_tf($post, $postindex, $value, $succes_var, $object, $property, $obvalue){
    if($succes_var){
        if($object->{$property} == $obvalue){
            return 'selected="true"';
        }
    }
    else{
        if(!isset($post[$postindex]) && $object->{$property} == $obvalue){
            return 'selected="true"';
        }
        elseif(isset($post[$postindex]) && $post[$postindex] == $value){
            return 'selected="true"';
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["act"]) && $_POST["act"] == "airline" && isset($_GET["action"]) && $_GET["action"] == "add") {
    $postvelden = array("naam", "iata", "OverweightChargeG", "OverweightChargeBag", "ChargeExtraBag", "OversizeCharge");
        
    $not_required = array("OverweightChargeG", "OverweightChargeBag", "ChargeExtraBag", "OversizeCharge");
        
        foreach($postvelden as $postveld){
            if(!isset($_POST[$postveld]) || (empty($_POST[$postveld]) && $_POST[$postveld] != "0") && !in_array($postveld, $not_required)){
                $error[$postveld] = 'Veld niet ingevuld.';
            }
            elseif($postveld == "ChargeExtraBag"){
                if(is_array($_POST[$postveld])){
                    foreach($_POST[$postveld] as $key => $koffer){
                        if(!validator::isInt($koffer) && !empty($koffer)){
                            $error[$postveld .$key] = "Vul een getal in";
                        }
                        else if(empty($koffer)){
                            $_POST["ChargeExtraBag"][$key] = null;
                        }
                    }
                }
            }
            elseif($postveld == "naam"){
                if(airline::airline_name_exists($_POST[$postveld])){
                    $error["naam"] = 'Luchtvaartmaatschappij bestaat al.';
                }
            }
            elseif($postveld == "iata"){
                if(!validator::stringLimit(1, 3, $_POST[$postveld])){
                    $error["iata"] = 'Een iata code mag maximaal 3 karakters bevatten.';
                }
            }

            else if($postveld != "notes"){
                if(!validator::isInt($_POST[$postveld]) && !empty($_POST[$postveld])){
                    $error[$postveld] = 'Vul een getal in.';
                }
            }
            if(empty($_POST[$postveld])){
                $_POST[$postveld] = null;
            }
        }
        
        if($_FILES["logo"]["error"] == 0){
            $permitted = array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/png', 'image/x-png', 'image/x-citrix-png', 'image/x-citrix-jpeg');
            if(in_array($_FILES["logo"]["type"], $permitted)){
                $type = explode("/", $_FILES["logo"]["type"]);
                $type = $type[1];
                switch($type){
                    case "pjpeg": $type = "jpeg";
                    break;
                    
                    case "x-png": $type = "png";
                    break;
                    
                    case "x-citrix-png": $type = "png";
                    break;
                    
                    case "x-citrix-jpeg": $type = "jpeg";
                    break;
                }
                
                $name = time() ."." .$type;
                move_uploaded_file($_FILES["logo"]["tmp_name"], "../images/airlines/" .$name);
            }
            else{
                $error["logo"] = 'Alleen afbeeldingen kunnen worden toegevoegd.';
            }
        }
        else{
            $name = null;
        }
        
        if($error == null){
            if(!isset($_POST["notes"]) || empty($_POST["notes"])){
                $_POST["notes"] = null;
            }
            airline::add_airline_without_class($_POST["naam"], $name, $_POST["OverweightChargeG"], $_POST["OverweightChargeBag"], $_POST["ChargeExtraBag"], $_POST["OversizeCharge"], $_POST["iata"], $_POST["notes"]);
            $succes_airline = true;
        }
}
elseif($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["act"]) && $_POST["act"] == "class" && isset($_GET["action"]) && $_GET["action"] == "add"){
    $required = array("airline_name", "classnumber", "pcs_weight","pcs_weightHL","LoyaltyProgramme","PetsAllowed","DeclarationOfValue","LaptopAllowedHL",
                        "Pooling","FreeWheelChair","FreeServiceDog", "petsAllowedHL", "lengte_totaal", "lengte_totaalI");
                        
    
    $extra_required = $required;
    
    if(isset($_POST["pcs_weight"]) && $_POST["pcs_weight"] == "pcs"){
        $required[] = "pcsLuggage";
        $required[] = "pcsLuggageInfant";
    }
    elseif(isset($_POST["pcs_weight"]) && $_POST["pcs_weight"] == "weight"){
        $required[] = "maxWeightLuggage";
        $required[] = "pcsLuggageInfantMaxWeight";
    }
    elseif(isset($_POST["pcs_weight"]) && $_POST["pcs_weight"] == "both"){
        $required[] = "pcsLuggage";
        $required[] = "pcsLuggageInfant";
        $required[] = "maxWeightLuggage";
        $required[] = "pcsLuggageInfantMaxWeight";
    }
    
    if(isset($_POST["pcs_weightHL"]) && $_POST["pcs_weightHL"] == "pcs"){
        $required[] = "pcsHL";
        $required[] = "pcsInfantHL";
    }
    elseif(isset($_POST["pcs_weightHL"]) && $_POST["pcs_weightHL"] == "weight"){
        $required[] = "MaxWeightHL";
        $required[] = "MaxWeightInfantHL";
    }
    elseif(isset($_POST["pcs_weightHL"]) && $_POST["pcs_weightHL"] == "both"){
        $required[] = "pcsHL";
        $required[] = "MaxWeightInfantHL";
        $required[] = "pcsInfantHL";
        $required[] = "MaxWeightHL";
    }
    
    if(isset($_POST["LoyaltyProgramme"]) && $_POST["LoyaltyProgramme"] == "true"){
        $required[] = "LPextraPcsLuggage";
        $required[] = "LPextraWeightLuggage";
        $required[] = "AbsoluteMaxPerItem";
    }
    
    if(isset($_POST["PetsAllowed"]) && $_POST["PetsAllowed"] == "true"){
        $required[] = "MaxWeightPet";
        $required[] = "lengte_totaalPets"; $extra_required[] = "lengte_totaalPets";
        $required[] = "CostsPet";
        
        if(isset($_POST["lengte_totaalPets"]) && $_POST["lengte_totaalPets"] == "l"){
            $required[] = "sizeLenghtPet";
            $required[] = "sizeHeightPet";
            $required[] = "sizeWidthPet";
        }
        elseif(isset($_POST["lengte_totaalPets"]) && $_POST["lengte_totaalPets"] == "l"){
            $required[] = "sizeTotalPet";
        }
    }
    
    if(isset($_POST["DeclarationOfValue"]) && $_POST["DeclarationOfValue"] == "true"){
        $required[] = "MaxDeclarationOfValue";
    }
    
    if(isset($_POST["lengte_totaal"]) && $_POST["lengte_totaal"] == "l"){
        $required[] = "sizeLenghtHL";
        $required[] = "sizeHeightHL";
        $required[] = "SizeWidthHL";
    }
    elseif(isset($_POST["lengte_totaal"]) && $_POST["lengte_totaal"] == "t"){
        $required[] = "sizeTotalHL";
    }
    
    if(isset($_POST["lengte_totaalI"]) && $_POST["lengte_totaalI"] == "l"){
        $required[] = "sizeLenghtPerItem";
        $required[] = "sizeHeightPerItem";
        $required[] = "sizeWidthPerItem";
    }
    elseif(isset($_POST["lengte_totaalI"]) && $_POST["lengte_totaalI"] == "t"){
        $required[] = "sizeTotalPerItem";
    }
    
    
    
    foreach($required as $requiredField){
        if(!isset($_POST[$requiredField]) || (empty($_POST[$requiredField]) && $_POST[$requiredField] != "0") && in_array($requiredField, $extra_required)){
            $error[$requiredField] = 'Veld niet ingevuld.';
        }
        elseif($requiredField == "classnumber" && isset($_POST[$requiredField]) && ($_POST[$requiredField] < 0 || $_POST[$requiredField] > 2)){
            $error[$requiredField] = "Kies uit Economy, Eerste klas of Business klas.";
        }
        elseif($requiredField == "pcs_weight" && isset($_POST[$requiredField]) && !in_array($_POST[$requiredField], array("pcs", "weight", "both"))){
            $error[$requiredField] = "Kies uit stukken, gewicht of beide.";
        }
        elseif($requiredField == "pcs_weightHL" && isset($_POST[$requiredField]) && !in_array($_POST[$requiredField], array("pcs", "weight", "both"))){
            $error[$requiredField] = "Kies uit stukken, gewicht of beide.";
        }
        elseif(!in_array($requiredField, array("lengte_totaalPets", "lengte_totaal", "lengte_totaalI", "petsAllowedHL", "airline_name", "pcs_weight", "pcs_weightHL", "FreeServiceDog", "FreeWheelChair", "Pooling", "strollerAllowedHL", "LaptopAllowedHL", "DeclarationOfValue", "PetsAllowed", "LoyaltyProgramme"))){
            if(!validator::isInt($_POST[$requiredField]) && !empty($_POST[$requiredField])){
                $error[$requiredField] = "Vul een getal in.";
            }
        }
        if(empty($_POST[$requiredField])){
            $_POST[$requiredField] = null;
        }
    }
    
    if(isset($_POST["airline_name"]) && !empty($_POST["airline_name"])){
        $name = explode("(", $_POST["airline_name"]);
        if(count($name) == 1){
            $name = $name[0];
        }
        else{
            unset($name[count($name) - 1]);
            $name = trim(implode("(", $name));
        }

        if(!airline::airline_name_exists($name)){
            $error["airline_name"] = "Luchtvaartmaatschappij bestaat nog niet.";
        }
    }
    
    if(!isset($error["classnumber"]) && !isset($error["airline_name"])){
        $airline = airline::get_airline_by_name($_POST["airline_name"]);
        $airline = airline::get_airline($airline->airline_id, $_POST["classnumber"]);
        if(count($airline->classes) > 0){
            $error["classnumber"] = "Deze luchtvaartmaatschappij heeft deze class al.";
        }
    }
    
    if($error == null){
        foreach($_POST as $key => $value){
            if($value == "true"){
                $_POST[$key] = 1;
            }
            elseif($value == "false"){
                $_POST[$key] = 0;
            }
        }
        $airline = airline::get_airline_by_name($name);
        if(count($airline) < 1){
            $error["airline_name"] = "Luchtvaartmaatschappij bestaat niet.";
        }
        else{
            airline::add_class($airline->airline_id, $_POST["classnumber"], $_POST["pcsHL"], $_POST["MaxWeightHL"], $_POST["sizeLenghtHL"], $_POST["sizeHeightHL"], $_POST["SizeWidthHL"], $_POST["sizeTotalHL"], $_POST["LaptopAllowedHL"], $_POST["pcsInfantHL"],
            $_POST["pcsLuggageInfant"], $_POST["pcsLuggageInfantMaxWeight"], $_POST["pcsLuggage"],
            $_POST["maxWeightLuggage"], $_POST["LoyaltyProgramme"], $_POST["LPextraPcsLuggage"], $_POST["LPextraWeightLuggage"],
            $_POST["AbsoluteMaxPerItem"], $_POST["sizeLenghtPerItem"], $_POST["sizeHeightPerItem"], $_POST["sizeWidthPerItem"],
            $_POST["sizeTotalPerItem"], $_POST["Pooling"], $_POST["FreeWheelChair"], $_POST["FreeServiceDog"], $_POST["PetsAllowed"], $_POST["MaxWeightPet"],
            $_POST["sizeLenghtPet"], $_POST["sizeHeightPet"], $_POST["sizeWidthPet"], $_POST["sizeTotalPet"], $_POST["DeclarationOfValue"],
            $_POST["MaxDeclarationOfValue"], $_POST["petsAllowedHL"], $_POST["MaxWeightInfantHL"], $_POST["CostsPet"]);
            $succes_class = true;
        }
    }
}

elseif($_SERVER["REQUEST_METHOD"] == "POST" && isset($_GET["action"]) && $_GET["action"] == "edit" && isset($_POST["act"]) && $_POST["act"] == "airline"){
    $postvelden = array("naam", "iata", "OverweightChargeG", "OverweightChargeBag", "ChargeExtraBag", "OversizeCharge");
        
        $not_required = array("OverweightChargeG", "OverweightChargeBag", "ChargeExtraBag", "OversizeCharge");
        
        if(isset($_POST["airline_id"]) && $_POST["airline_id"] != ""){
            $current_airline = airline::get_airline($_POST["airline_id"], 0);
        }
        
        
        foreach($postvelden as $postveld){
            if(!isset($_POST[$postveld]) || (empty($_POST[$postveld]) && $_POST[$postveld] != "0") && !in_array($postveld, $not_required)){
                $error[$postveld] = 'Veld niet ingevuld.';
            }
            elseif($postveld == "ChargeExtraBag"){
                if(is_array($_POST[$postveld])){
                    foreach($_POST[$postveld] as $key => $koffer){
                        if(!validator::isInt($koffer) && !empty($koffer)){
                            $error[$postveld .$key] = "Vul een getal in";
                        }
                        else if(empty($koffer)){
                            $_POST["ChargeExtraBag"][$key] = null;
                        }
                    }
                }
            }
            elseif($postveld == "naam" && $_POST[$postveld] != $current_airline->name){
                if(airline::airline_name_exists($_POST[$postveld])){
                    $error["naam"] = 'Luchtvaartmaatschappij bestaat al.';
                }
            }
            elseif($postveld == "iata"){
                if(!validator::stringLimit(1, 3, $_POST[$postveld])){
                    $error["iata"] = 'Een iata code mag maximaal 3 karakters bevatten.';
                }
            }

            else if($postveld != "notes" && $postveld != "naam"){
                if(!validator::isInt($_POST[$postveld]) && !empty($_POST[$postveld])){
                    $error[$postveld] = 'Vul een getal in.';
                }
            }
            if(empty($_POST[$postveld])){
                $_POST[$postveld] = null;
            }
        }
        
        if($_FILES["logo"]["error"] == 0){
            $permitted = array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/png', 'image/x-png', 'image/x-citrix-png', 'image/x-citrix-jpeg');
            if(in_array($_FILES["logo"]["type"], $permitted)){
                $type = explode("/", $_FILES["logo"]["type"]);
                $type = $type[1];
                switch($type){
                    case "pjpeg": $type = "jpeg";
                    break;
                    
                    case "x-png": $type = "png";
                    break;
                    
                    case "x-citrix-png": $type = "png";
                    break;
                    
                    case "x-citrix-jpeg": $type = "jpeg";
                    break;
                }
                $name = time() ."." .$type;
                move_uploaded_file($_FILES["logo"]["tmp_name"], "../images/airlines/" .$name);
            }
            else{
                $error["logo"] = 'Alleen afbeeldingen kunnen worden toegevoegd.';
            }
        }
        else{
            $name = "";
        }
        
        if($error == null){
            if(!isset($_POST["notes"])){
                $_POST["notes"] = "";
            }
            $current_airline->name = $_POST["naam"];
            if($name != ""){
                $current_airline->logo = $name;
            }
            $current_airline->OverweightChargeG = $_POST["OverweightChargeG"];
            $current_airline->OverweightChargeBag = $_POST["OverweightChargeBag"];
            $current_airline->OversizeCharge = $_POST["OversizeCharge"];
            $current_airline->iata = $_POST["iata"];
            $current_airline->notes = $_POST["notes"];
            
            chargeExtraBag::remove_all($current_airline->airline_id);
            
            foreach($_POST["ChargeExtraBag"] as $number => $costs){
                chargeExtraBag::add($current_airline->airline_id, $number, $costs);
            }
            airline::edit_airline($current_airline);
            $succes_airline = true;
        }
}

elseif($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["act"]) && $_POST["act"] == "class" && isset($_GET["action"]) && $_GET["action"] == "edit"){
    $required = array("pcs_weight","pcs_weightHL","LoyaltyProgramme","PetsAllowed","DeclarationOfValue","LaptopAllowedHL",
                        "Pooling","FreeWheelChair","FreeServiceDog", "petsAllowedHL", "lengte_totaal", "lengte_totaalI");
                        
    $extra_required = $required;
    
    if(isset($_POST["pcs_weight"]) && $_POST["pcs_weight"] == "pcs"){
        $required[] = "pcsLuggage";
        $required[] = "pcsLuggageInfant";
    }
    elseif(isset($_POST["pcs_weight"]) && $_POST["pcs_weight"] == "weight"){
        $required[] = "maxWeightLuggage";
        $required[] = "pcsLuggageInfantMaxWeight";
    }
    elseif(isset($_POST["pcs_weight"]) && $_POST["pcs_weight"] == "both"){
        $required[] = "pcsLuggage";
        $required[] = "pcsLuggageInfant";
        $required[] = "maxWeightLuggage";
        $required[] = "pcsLuggageInfantMaxWeight";
    }
    
    if(isset($_POST["pcs_weightHL"]) && $_POST["pcs_weightHL"] == "pcs"){
        $required[] = "pcsHL";
        $required[] = "pcsInfantHL";
    }
    elseif(isset($_POST["pcs_weightHL"]) && $_POST["pcs_weightHL"] == "weight"){
        $required[] = "MaxWeightHL";
        $required[] = "MaxWeightInfantHL";
    }
    elseif(isset($_POST["pcs_weightHL"]) && $_POST["pcs_weightHL"] == "both"){
        $required[] = "pcsHL";
        $required[] = "MaxWeightInfantHL";
        $required[] = "pcsInfantHL";
        $required[] = "MaxWeightHL";
    }
    
    if(isset($_POST["LoyaltyProgramme"]) && $_POST["LoyaltyProgramme"] == "true"){
        $required[] = "LPextraPcsLuggage";
        $required[] = "LPextraWeightLuggage";
        $required[] = "AbsoluteMaxPerItem";
    }
    
    if(isset($_POST["PetsAllowed"]) && $_POST["PetsAllowed"] == "true"){
        $required[] = "MaxWeightPet";
        $required[] = "lengte_totaalPets"; $extra_required[] = "lengte_totaalPets";
        $required[] = "CostsPet";
        
        if(isset($_POST["lengte_totaalPets"]) && $_POST["lengte_totaalPets"] == "l"){
            $required[] = "sizeLenghtPet";
            $required[] = "sizeHeightPet";
            $required[] = "sizeWidthPet";
        }
        elseif(isset($_POST["lengte_totaalPets"]) && $_POST["lengte_totaalPets"] == "l"){
            $required[] = "sizeTotalPet";
        }
    }
    
    if(isset($_POST["DeclarationOfValue"]) && $_POST["DeclarationOfValue"] == "true"){
        $required[] = "MaxDeclarationOfValue";
    }
    
    if(isset($_POST["lengte_totaal"]) && $_POST["lengte_totaal"] == "l"){
        $required[] = "sizeLenghtHL";
        $required[] = "sizeHeightHL";
        $required[] = "SizeWidthHL";
    }
    elseif(isset($_POST["lengte_totaal"]) && $_POST["lengte_totaal"] == "t"){
        $required[] = "sizeTotalHL";
    }
    
    if(isset($_POST["lengte_totaalI"]) && $_POST["lengte_totaalI"] == "l"){
        $required[] = "sizeLenghtPerItem";
        $required[] = "sizeHeightPerItem";
        $required[] = "sizeWidthPerItem";
    }
    elseif(isset($_POST["lengte_totaalI"]) && $_POST["lengte_totaalI"] == "t"){
        $required[] = "sizeTotalPerItem";
    }
    
    
    
    foreach($required as $requiredField){
        if(!isset($_POST[$requiredField]) || (empty($_POST[$requiredField]) && $_POST[$requiredField] != "0") && in_array($requiredField, $extra_required)){
            $error[$requiredField] = 'Veld niet ingevuld.';
        }
        elseif($requiredField == "pcs_weight" && isset($_POST[$requiredField]) && !in_array($_POST[$requiredField], array("pcs", "weight", "both"))){
            $error[$requiredField] = "Kies uit stukken, gewicht of beide.";
        }
        elseif($requiredField == "pcs_weightHL" && isset($_POST[$requiredField]) && !in_array($_POST[$requiredField], array("pcs", "weight", "both"))){
            $error[$requiredField] = "Kies uit stukken, gewicht of beide.";
        }
        elseif(!in_array($requiredField, array("lengte_totaalPets", "lengte_totaal", "lengte_totaalI", "petsAllowedHL", "airline_name", "pcs_weight", "pcs_weightHL", "FreeServiceDog", "FreeWheelChair", "Pooling", "strollerAllowedHL", "LaptopAllowedHL", "DeclarationOfValue", "PetsAllowed", "LoyaltyProgramme"))){
            if(!validator::isInt($_POST[$requiredField]) && !empty($_POST[$requiredField])){
                $error[$requiredField] = "Vul een getal in.";
            }
        }
        if(empty($_POST[$requiredField])){
            $_POST[$requiredField] = null;
        }
    }
    
    if(isset($_GET["airline_id"])){
        $airline = airline::get_airline($_GET["airline_id"], 0);
        if(count($airline) == 0){
            $error = true;
        }
    }
    elseif(isset($_GET["airline_name"])){
        $airline = airline::get_airline_by_name($_GET["airline_name"]);
        if(count($airline) == 0){
            $error = true;
        }
    }
    elseif(!isset($_GET["class"])){
        $error = true;
    }
    else{
        $error = true;
    }
    
    if($error == null){
        foreach($_POST as $key => $value){
            if($value == "true"){
                $_POST[$key] = 1;
            }
            elseif($value == "false"){
                $_POST[$key] = 0;
            }
        }
        
        if(isset($_GET["airline_id"]) && $_GET["class"]){
            $airline = airline::get_airline($_GET["airline_id"], $_GET["class"]);
        }
        if(isset($_GET["airline_name"]) && isset($_GET["class"])){
            $airline = airline::get_airline_by_name($_GET["airline_name"]);
            $airline = airline::get_airline($airine->airline_id, $_GET["class"]);
        }
        $class = $airline->classes[0];
        foreach($required as $requiredField){
            if(!in_array($requiredField, array("pcs_weight", "pcs_weightHL", "lengte_totaal", "lengte_totaalI", "lengte_totaalPets"))){
                $class->{$requiredField} = $_POST[$requiredField];
            }
            
        }
        foreach($class as $property => $value){
            if(!in_array($property, $required) && !in_array($property, array("class_id", "airline", "classnumber"))){
                $class->{$property} = "0";
            }
        }
        airlineclass::edit_class($class);
        
        $succes_class = true;
        
        }
}

?>

<div id="menu">
    <ul>
        <li>
            <a<?php echo (isset($_GET["action"]) && $_GET["action"] == "add" ?' class="active"' : "") ?> href="airline.php?action=add">Toevoegen</a>
        </li>
        <li>
            <a<?php echo ((isset($_GET["action"]) && $_GET["action"] == "edit") || (isset($_GET["action"]) && $_GET["action"] == "del") ? ' class="active"' : "") ?> href="airline.php?action=edit">Beheren</a>
        </li>
    </ul>
</div>

<?php
if(!isset($_GET["action"])){
?>
<br /><br />
<h1 style="margin-left: 20px;">Administratie Luchtvaartmaatschappijen</h1>    
<p>Gebruik het menu om Luchtvaartmaatschappijen toe te voegen of te bewerken.</p>
<?php
}
?>

<div style="clear: both;"></div><br />

<script src="../js/jquery-1.9.0.min.js"></script>
<script src="../js/jquery-ui.js"></script>
<script src="../js/grid.locale-nl.js" type="text/javascript"></script>
<script src="../js/jquery.jqGrid.min.js" type="text/javascript"></script>
<script src="../js/javascript.js"></script>
  <script type="text/javascript">
  
  $(document).ready(function(){
    $(".pcs").hide();
    $(".weight").hide();
    $(".pcsHL").hide();
    $(".weightHL").hide();
    $(".LP").hide();
    $(".pets").hide();
    $(".DeclarationOfValue").hide();
    $(".t").hide();
    $(".l").hide();
    $(".tI").hide();
    $(".lI").hide();
    
    $(".pets_lengte").hide();
    $(".pets_totaal").hide();
    
    $("#ruimbagage").ready(function(){
        
        if($("#ruimbagage option:selected").val() == "pcs"){
            $(".weight").slideUp("fast", function(){
                $(".pcs").slideDown("fast");
            });
        }
        else if($("#ruimbagage option:selected").val() == "weight"){
            $(".pcs").slideUp("fast", function(){
                $(".weight").slideDown("fast");
            });
        }
        else if($("#ruimbagage option:selected").val() == "both"){
            $(".pcs").slideDown("fast");
            $(".weight").slideDown("fast");
        }
        else{
            $(".pcs").slideUp("fast");
            $(".weight").slideUp("fast");
        }
    });
    
    $("#ruimbagage").change(function (){
        if($("#ruimbagage option:selected").val() == "pcs"){
            $(".weight").slideUp("fast", function(){
                $(".pcs").slideDown("fast");
            });
        }
        else if($("#ruimbagage option:selected").val() == "weight"){
            $(".pcs").slideUp("fast", function(){
                $(".weight").slideDown("fast");
            });
        }
        else if($("#ruimbagage option:selected").val() == "both"){
            $(".pcs").slideDown("fast");
            $(".weight").slideDown("fast");
        }
        else{
            $(".pcs").slideUp("fast");
            $(".weight").slideUp("fast");
        }
    });
    
    $("#handbagage").ready(function(){
        if($("#handbagage option:selected").val() == "pcs"){
            $(".weightHL").slideUp("fast", function(){
                $(".pcsHL").slideDown("fast");
            });
        }
        else if($("#handbagage option:selected").val() == "weight"){
            $(".pcsHL").slideUp("fast", function(){
                $(".weightHL").slideDown("fast");
            });
        }
        else if($("#handbagage option:selected").val() == "both"){
            $(".pcsHL").slideDown("fast");
            $(".weightHL").slideDown("fast");
        }
        else{
            $(".pcsHL").slideUp("fast");
            $(".weightHL").slideUp("fast");
        }
    });
    
    $("#handbagage").change(function(){
        if($("#handbagage option:selected").val() == "pcs"){
            $(".weightHL").slideUp("fast", function(){
                $(".pcsHL").slideDown("fast");
            });
        }
        else if($("#handbagage option:selected").val() == "weight"){
            $(".pcsHL").slideUp("fast", function(){
                $(".weightHL").slideDown("fast");
            });
        }
        else if($("#handbagage option:selected").val() == "both"){
            $(".pcsHL").slideDown("fast");
            $(".weightHL").slideDown("fast");
        }
        else{
            $(".pcsHL").slideUp("fast");
            $(".weightHL").slideUp("fast");
        }
    });
    
    $("#LP_select").ready(function(){
        if($("#LP_select option:selected").val() == "true"){
            $(".LP").slideDown("fast");
        }
        else if($("#LP_select option:selected").val() == "false"){
            $(".LP").slideUp("fast");
        }
        else{
            $(".LP").slideUp("fast");
        }
    });
    
    $("#LP_select").change(function(){
        if($("#LP_select option:selected").val() == "true"){
            $(".LP").slideDown("fast");
        }
        else if($("#LP_select option:selected").val() == "false"){
            $(".LP").slideUp("fast");
        }
        else{
            $(".LP").slideUp("fast");
        }
    });
    
    $("#pets").ready(function(){
        if($("#pets option:selected").val() == "true"){
            $(".pets").slideDown("fast");
        }
        else if($("#pets option:selected").val() == "false"){
            $(".pets").slideUp("fast");
        }
        else{
            $(".pets").slideUp("fast");
        }
    });
    
    $("#pets").change(function(){
        if($("#pets option:selected").val() == "true"){
            $(".pets").slideDown("fast");
        }
        else if($("#pets option:selected").val() == "false"){
            $(".pets").slideUp("fast");
        }
        else{
            $(".pets").slideUp("fast");
        }
    });
    
    $("#DOV_select").change(function(){
        if($("#DOV_select option:selected").val() == "true"){
            $(".DeclarationOfValue").slideDown("fast");
        }
        else if($("#DOV_select option:selected").val() == "false"){
            $(".DeclarationOfValue").slideUp("fast");
        }
        else{
            $(".DeclarationOfValue").slideUp("fast");
        }
    });
    
    $("#DOV_select").ready(function(){
        if($("#DOV_select option:selected").val() == "true"){
            $(".DeclarationOfValue").slideDown("fast");
        }
        else if($("#DOV_select option:selected").val() == "false"){
            $(".DeclarationOfValue").slideUp("fast");
        }
        else{
            $(".DeclarationOfValue").slideUp("fast");
        }
    });
    
    $("#lengte_select").change(function(){
        if($("#lengte_select option:selected").val() == "l"){
            $(".t").slideUp("fast", function(){
                $(".l").slideDown("fast");
            });
        }
        else if($("#lengte_select option:selected").val() == "t"){
            $(".l").slideUp("fast", function(){
                $(".t").slideDown("fast");
            });
        }
        else{
            $(".l").slideUp("fast");
            $(".t").slideUp("fast");
        }
    });
    
    $("#lengte_select").ready(function(){
        if($("#lengte_select option:selected").val() == "l"){
            $(".t").slideUp("fast", function(){
                $(".l").slideDown("fast");
            });
        }
        else if($("#lengte_select option:selected").val() == "t"){
            $(".l").slideUp("fast", function(){
                $(".t").slideDown("fast");
            });
        }
        else{
            $(".l").slideUp("fast");
            $(".t").slideUp("fast");
        }
    });
    
    $("#lengte_selectI").change(function(){
        if($("#lengte_selectI option:selected").val() == "l"){
            $(".tI").slideUp("fast", function(){
                $(".lI").slideDown("fast");
            })
        }
        else if($("#lengte_selectI option:selected").val() == "t"){
            $(".lI").slideUp("fast", function(){
                $(".tI").slideDown("fast");
            })
        }
        else{
            $(".tI").slideUp("fast");
            $(".lI").slideUp("fast");
        }
    });
    
    $("#lengte_selectI").ready(function(){
        if($("#lengte_selectI option:selected").val() == "l"){
            $(".tI").slideUp("fast", function(){
                $(".lI").slideDown("fast");
            })
        }
        else if($("#lengte_selectI option:selected").val() == "t"){
            $(".lI").slideUp("fast", function(){
                $(".tI").slideDown("fast");
            })
        }
        else{
            $(".tI").slideUp("fast");
            $(".lI").slideUp("fast");
        }
    });
    
    $(".class_edit").change(function(){
        $("#class_form").submit();
    });
    
    $("#airline_id_select").change(function(){
        var option = $("#airline_id_select option:selected").val();
        if(typeof option != 'undefined'){
            $("#airline_select_box").submit();
        }
    });
   
   $("#lengte_selectPets").change(function(){
        var selected = $("#lengte_selectPets option:selected").val();
        if(selected == "l"){
            $(".pets_totaal").slideUp("fast", function(){
                $(".pets_lengte").slideDown("fast");
            })
        }
        else if(selected == "t"){
            $(".pets_lengte").slideUp("fast", function(){
                $(".pets_totaal").slideDown("fast");
            })
        }
        else{
            $(".pets_lengte").slideUp("fast");
            $(".pets_totaal").slideUp("fast");
        }
   });
   
      $("#lengte_selectPets").ready(function(){
        var selected = $("#lengte_selectPets option:selected").val();
        if(selected == "l"){
            $(".pets_totaal").slideUp("fast", function(){
                $(".pets_lengte").slideDown("fast");
            })
        }
        else if(selected == "t"){
            $(".pets_lengte").slideUp("fast", function(){
                $(".pets_totaal").slideDown("fast");
            })
        }
        else{
            $(".pets_lengte").slideUp("fast");
            $(".pets_totaal").slideUp("fast");
        }
   });
    
  });
  
  $(function() {
    var availableTags = [
    <?php
    $airlines = airline::get_airlines(); //alle airlines ophalen
    for ($i = 0; $i < count($airlines); $i++) {
        if ($i == count($airlines) - 1) {
            echo '"' . $airlines[$i]->name . ' (' . $airlines[$i]->iata . ')"';
        } else {
            echo '"' . $airlines[$i]->name . ' (' . $airlines[$i]->iata . ')"' . ",";
        }
    }  ?>
    ];
    $( "#airline_name" ).autocomplete({
      source: availableTags
    });
  });
  
  function add_koffer(){
    koffer_num = $("#koffers input:last").attr("name");
    koffer_num = koffer_num.split("ChargeExtraBag[");
    koffer_num = koffer_num[1].split("]");
    koffer_num = parseInt(koffer_num[0]);
    $("#koffers").append('<label>Kosten extra koffer ' +(koffer_num+2) +' &euro;</label><input type="text" name="ChargeExtraBag[' +(koffer_num+1) +']" />');
    return false;
  }
  
  </script>

<?php
if (isset($_GET["action"]) && $_GET["action"] == "add") {
?>

<!--Add-->
<div id="left">
    <h1 style="margin-left: 20px;">Luchtvaartmaatschappij toevoegen</h1><br />
    <?php if(isset($succes_airline) && $succes_airline){ ?>
    <strong>Luchtvaartmaatschappij <?php echo htmlspecialchars($_POST["naam"]); ?> succesvol toegevoegd.</strong>
    <?php } ?>
    <form action="airline.php?action=add" method="post" class="form" enctype="multipart/form-data">
        <input type="hidden" name="act" value="airline" />
        <?php echo display_error($error, "naam"); ?><label>Naam</label><input type="text" name="naam" <?php echo add_value($_POST, "naam", $succes_airline); ?> />
        <?php echo display_error($error, "logo"); ?><label>Logo</label><input type="file" name="logo" />
        <?php echo display_error($error, "iata"); ?><label>Iata code</label><input type="text" name="iata" <?php echo add_value($_POST, "iata", $succes_airline); ?> />
        <?php echo display_error($error, "OverweightChargeG"); ?><label>Kosten per extra kilogram &euro;</label><input type="text" name="OverweightChargeG" <?php echo add_value($_POST, "OverweightChargeG", $succes_airline) ?> />
        <?php echo display_error($error, "OverweightChargeBag"); ?><label>Kosten overgewicht koffer &euro;</label><input type="text" name="OverweightChargeBag" <?php echo add_value($_POST, "OverweightChargeBag", $succes_airline) ?> /> 
        
        <div id="koffers">
            <?php
            if(!$succes_airline && (isset($_POST["ChargeExtraBag"]) && is_array($_POST["ChargeExtraBag"]))){
                foreach($_POST["ChargeExtraBag"] as $value => $koffer){
                    echo display_error($error, "ChargeExtraBag". $value) .'<label>Kosten extra koffer ' .($value +1) .' &euro;</label><input type="text" name="ChargeExtraBag[' .$value .']" value="' .htmlspecialchars($koffer) .'" />';
                }
            }
            else{        
            ?>
            <?php echo display_error($error, "ChargeExtraBag0") ?><label>Kosten extra koffer 1 &euro;</label><input type="text" name="ChargeExtraBag[0]" <?php echo add_value($_POST, "ChargeExtraBag0", $succes_airline) ?> />
            <?php
            }
            ?>
        </div>
        <label>&nbsp;</label><button class="input" onclick="return add_koffer();">Koffer toevoegen</button>
        
        <?php echo display_error($error, "OversizeCharge"); ?><label>Kosten te grote koffer &euro;</label><input type="text" name="OversizeCharge" <?php echo add_value($_POST, "OversizeCharge", $succes_airline) ?> /> 
        <?php echo display_error($error, "notes"); ?><label>Opmerkingen</label><textarea rows="10" cols="25" class="input" name="notes"><?php echo htmlspecialchars(!$succes_airline && isset($_POST["notes"]) ? $_POST["notes"] : ""); ?></textarea>
        
        <label>&nbsp;</label><input type="submit" value="Opslaan" />
    
    </form>
</div>
<div id="right">
    <h1 style="margin-right: 20px; padding-right: 200px;">Klas toevoegen</h1><br />
    
    <?php if(isset($succes_class) && $succes_class){ ?>
    <strong>Klas toegevoegd aan <?php echo htmlspecialchars($_POST["airline_name"]); ?></strong>
    <?php } ?>
    
    <form action="airline.php?action=add" method="post" class="form">
        <input type="hidden" name="act" value="class" />
        <?php echo display_error($error, "airline_name"); ?><label>Luchtvaartmaatschappij:</label><select class="input" id="airline_name" name="airline_name"><option value=""></option>
            <?php 
            $airlines = airline::get_airlines();
            if(count($airlines) > 0){
                foreach($airlines as $airline){
                    echo '<option value="' .htmlspecialchars($airline->name) .'"' .set_selected($_POST, "airline_name", $airline->name, $succes_class) .'>' .htmlspecialchars($airline->name) .'</option>';
                }
            }
            ?>
        </select>
        <?php echo display_error($error, "classnumber"); ?><label>Klas:</label><select class="input" name="classnumber">
                                <option></option>
                                <option value="0" <?php echo set_selected($_POST, "classnumber", "0", $succes_class); ?>>Economy</option>
                                <option value="1" <?php echo set_selected($_POST, "classnumber", "1", $succes_class); ?>>Eerste klas</option>
                                <option value="2" <?php echo set_selected($_POST, "classnumber", "2", $succes_class); ?>>Business klas</option>
                            </select><br />
                            
        
        <!--Ruimbagage-->
        <label class="title">Ruimbagage</label><br />
        <?php echo display_error($error, "pcs_weight"); ?><label>Stukken of gewicht</label><select id="ruimbagage" class="input" name="pcs_weight"><option></option><option value="pcs" <?php echo set_selected($_POST, "pcs_weight", "pcs", $succes_class); ?>>Stukken</option><option value="weight" <?php echo set_selected($_POST, "pcs_weight", "weight", $succes_class); ?>>Gewicht</option><option value="both" <?php echo set_selected($_POST, "pcs_weight", "both", $succes_class); ?>>Beide</option></select>
        <div class="pcs"><?php echo display_error($error, "pcsLuggage"); ?><label>Stukken bagage</label><input type="text" name="pcsLuggage" <?php echo add_value($_POST, "pcsLuggage", $succes_class); ?> />
        <?php echo display_error($error, "pcsLuggageInfant"); ?><label>Stukken bagage baby</label><input type="text" name="pcsLuggageInfant" <?php echo add_value($_POST, "pcsLuggageInfant", $succes_class); ?> /></div>
        <div class="weight"><?php echo display_error($error, "maxWeightLuggage"); ?><label>Max. gewicht bagage kg</label><input type="text" name="maxWeightLuggage" <?php echo add_value($_POST, "maxWeightLuggage", $succes_class); ?> />
        <?php echo display_error($error, "pcsLuggageInfantMaxWeight"); ?><label>Max. gewicht bagage baby kg</label><input type="text" name="pcsLuggageInfantMaxWeight" <?php echo add_value($_POST, "pcsLuggageInfantMaxWeight", $succes_class); ?> /></div><br />
        
        <!--Items-->
        <label class="title">Koffers</label><br />
        <?php echo display_error($error, "lengte_totaalI"); ?><label>LxHxB of totaal</label><select class="input" id="lengte_selectI" name="lengte_totaalI"><option></option><option value="l" <?php echo set_selected($_POST, "lengte_totaalI", "l", $succes_class); ?>>LxHxB</option><option value="t" <?php echo set_selected($_POST, "lengte_totaalI", "t", $succes_class); ?>>Totaal</option></select>
        <div class="lI"><?php echo display_error($error, "sizeLenghtPerItem"); ?><label>Lengte per koffer cm</label><input type="text" name="sizeLenghtPerItem"  <?php echo add_value($_POST, "sizeLenghtPerItem", $succes_class); ?> />
        <?php echo display_error($error, "sizeHeightPerItem"); ?><label>Hoogte per koffer cm</label><input type="text" name="sizeHeightPerItem" <?php echo add_value($_POST, "sizeHeightPerItem", $succes_class); ?> />
        <?php echo display_error($error, "sizeWidthPerItem"); ?><label>Breedte per koffer cm</label><input type="text" name="sizeWidthPerItem" <?php echo add_value($_POST, "sizeWidthPerItem", $succes_class); ?> /></div>
        <div class="tI"><?php echo display_error($error, "sizeTotalPerItem"); ?><label>Totale omtrek per koffer cm</label><input type="text" name="sizeTotalPerItem" <?php echo add_value($_POST, "sizeTotalPerItem", $succes_class); ?> /></div><br />
        
        <!--Handbagage-->
        <label class="title">Handbagage</label><br />
        <?php echo display_error($error, "pcs_weightHL"); ?><label>Stukken of gewicht</label><select id="handbagage" class="input" name="pcs_weightHL"><option></option><option value="pcs" <?php echo set_selected($_POST, "pcs_weightHL", "pcs", $succes_class) ?>>Stukken</option><option value="weight" <?php echo set_selected($_POST, "pcs_weightHL", "weight", $succes_class) ?>>Gewicht</option><option value="both" <?php echo set_selected($_POST, "pcs_weightHL", "both", $succes_class) ?>>Beide</option></select>
        <div class="pcsHL"><?php echo display_error($error, "pcsHL"); ?><label>Stukken handbagage</label><input type="text" name="pcsHL" <?php echo add_value($_POST, "pcsHL", $succes_class); ?> />
        <?php echo display_error($error, "pcsInfantHL"); ?><label>Stukken handbagage baby</label><input type="text" name="pcsInfantHL" <?php echo add_value($_POST, "pcsInfantHL", $succes_class); ?> /></div>
        <div class="weightHL"><?php echo display_error($error, "MaxWeightHL"); ?><label>Max. gewicht handbagage kg</label><input type="text" name="MaxWeightHL" <?php echo add_value($_POST, "MaxWeightHL", $succes_class); ?> />
        <?php echo display_error($error, "MaxWeightInfantHL"); ?><label>Max gewicht handbagage baby kg</label><input type="text" name="MaxWeightInfantHL" <?php echo add_value($_POST, "MaxWeightInfantHL", $succes_class); ?> />
        </div>
        
        <?php echo display_error($error, "lengte_totaal"); ?><label>LxHxB of totaal</label><select class="input" id="lengte_select" name="lengte_totaal"><option></option><option value="l" <?php echo set_selected($_POST, "lengte_totaal", "l", $succes_class); ?>>LxHxB</option><option value="t" <?php echo set_selected($_POST, "lengte_totaal", "t", $succes_class); ?>>Totaal</option></select>
        <div class="l"><?php echo display_error($error, "sizeLenghtHL"); ?><label>Lengte handbagage cm</label><input type="text" name="sizeLenghtHL" <?php echo add_value($_POST, "sizeLenghtHL", $succes_class); ?> />
        <?php echo display_error($error, "sizeHeightHL"); ?><label>Hoogte handbagage cm</label><input type="text" name="sizeHeightHL" <?php echo add_value($_POST, "sizeHeightHL", $succes_class); ?> />
        <?php echo display_error($error, "SizeWidthHL"); ?><label>Breedte handbagage cm</label><input type="text" name="SizeWidthHL" <?php echo add_value($_POST, "SizeWidthHL", $succes_class); ?> /></div>
        <div class="t"><?php echo display_error($error, "sizeTotalHL"); ?><label>Totale omtrek handbagage cm</label><input type="text" name="sizeTotalHL" <?php echo add_value($_POST, "sizeTotalHL", $succes_class); ?> /></div>
        <?php echo display_error($error, "petsAllowedHL"); ?><label>Huisdieren toegestaan</label><select class="input" name="petsAllowedHL"><option></option><option value="true" <?php echo set_selected($_POST, "petsAllowedHL", "true", $succes_class) ?>>Ja</option><option value="false" <?php echo set_selected($_POST, "petsAllowedHL", "false", $succes_class) ?>>Nee</option></select><br />
        
        <!--LP-->
        <label class="title">Loyalty programma (LP)</label><br />
        <?php echo display_error($error, "LoyaltyProgramme"); ?><label>Loyalty programma</label><select id="LP_select" class="input" name="LoyaltyProgramme"><option></option><option value="true" <?php echo set_selected($_POST, "LoyaltyProgramme", "true", $succes_class) ?>>Ja</option><option value="false" <?php echo set_selected($_POST, "LoyaltyProgramme", "false", $succes_class) ?>>Nee</option></select>
        <div class="LP"><?php echo display_error($error, "LPextraPcsLuggage"); ?><label>Extra stukken bagage LP</label><input type="text" name="LPextraPcsLuggage" <?php echo add_value($_POST, "LPextraPcsLuggage", $succes_class); ?> />
        <?php echo display_error($error, "LPextraWeightLuggage"); ?><label>Extra gewicht bagage LP kg</label><input type="text" name="LPextraWeightLuggage" <?php echo add_value($_POST, "LPextraWeightLuggage", $succes_class); ?> />
        <?php echo display_error($error, "AbsoluteMaxPerItem"); ?><label>Abs. max. gewicht bagage kg</label><input type="text" name="AbsoluteMaxPerItem" <?php echo add_value($_POST, "AbsoluteMaxPerItem", $succes_class); ?> /></div><br />
        
        <!--Huisdieren-->
        <label class="title">Huisdieren inclusief kooi (Vrachtruim)</label><br />
        <?php echo display_error($error, "PetsAllowed"); ?><label>Huisdieren toegestaan</label><select id="pets" name="PetsAllowed" class="input"><option></option><option value="true" <?php echo set_selected($_POST, "PetsAllowed", "true", $succes_class); ?>>Ja</option><option value="false" <?php echo set_selected($_POST, "PetsAllowed", "false", $succes_class); ?>>Nee</option></select>
        <div class="pets"><?php echo display_error($error, "CostsPet"); ?><label>Kosten huisdier &euro;</label><input type="text" name="CostsPet" <?php echo add_value($_POST, "CostsPet", $succes_class); ?> />
        <?php echo display_error($error, "MaxWeightPet"); ?><label>Max. gewicht huisdier kg</label><input type="text" name="MaxWeightPet" <?php echo add_value($_POST, "MaxWeightPet", $succes_class); ?> />
        
        <?php echo display_error($error, "lengte_totaalPets"); ?><label>LxHxB of totaal</label><select class="input" id="lengte_selectPets" name="lengte_totaalPets"><option></option><option value="l" <?php echo set_selected($_POST, "lengte_totaalPets", "l", $succes_class); ?>>LxHxB</option><option value="t" <?php echo set_selected($_POST, "lengte_totaalPets", "t", $succes_class); ?>>Totaal</option></select>
        
        <div class="pets_lengte"><?php echo display_error($error, "sizeLenghtPet"); ?><label>Lengte huisdier cm</label><input type="text" name="sizeLenghtPet" <?php echo add_value($_POST, "sizeLenghtPet", $succes_class); ?> />
        <?php echo display_error($error, "sizeHeightPet"); ?><label>Hoogte kooi huisdier cm</label><input type="text" name="sizeHeightPet" <?php echo add_value($_POST, "sizeHeightPet", $succes_class); ?> />
        <?php echo display_error($error, "sizeWidthPet"); ?><label>Breedte kooi huisdier cm</label><input type="text" name="sizeWidthPet" <?php echo add_value($_POST, "sizeWidthPet", $succes_class); ?> /></div>
        <div class="pets_totaal"><?php echo display_error($error, "sizeTotalPet"); ?><label>Omtrek kooi huisdier cm</label><input type="text" name="sizeTotalPet" <?php echo add_value($_POST, "sizeTotalPet", $succes_class); ?> /></div></div><br />
        
        <!--Waardeaangifte-->
        <label class="title">Waardeaangifte</label><br />
        <?php echo display_error($error, "DeclarationOfValue"); ?><label>Waardeaangifte</label><select class="input" id="DOV_select" name="DeclarationOfValue"><option></option><option value="true" <?php echo set_selected($_POST, "DeclarationOfValue", "true", $succes_class); ?>>Ja</option><option value="false" <?php echo set_selected($_POST, "DeclarationOfValue", "false", $succes_class); ?>>Nee</option></select>
        <div class="DeclarationOfValue"><?php echo display_error($error, "MaxDeclarationOfValue"); ?><label>Max. waardeaangifte &euro;</label><input type="text" name="MaxDeclarationOfValue" <?php echo add_value($_POST, "MaxDeclarationOfValue", $succes_class); ?> /></div><br />
        
        <!--Ja/Nee-->
        <br />
        <?php echo display_error($error, "LaptopAllowedHL"); ?><label>Laptop toegestaan</label><select class="input" name="LaptopAllowedHL"><option></option><option value="true" <?php echo set_selected($_POST, "LaptopAllowedHL", "true", $succes_class) ?>>Ja</option><option value="false" <?php echo set_selected($_POST, "LaptopAllowedHL", "false", $succes_class) ?>>Nee</option></select>
        <?php echo display_error($error, "Pooling"); ?><label>Pooling</label><select name="Pooling" class="input"><option></option><option value="true" <?php echo set_selected($_POST, "Pooling", "true", $succes_class) ?>>Ja</option><option value="false" <?php echo set_selected($_POST, "Pooling", "false", $succes_class) ?>>Nee</option></select>
        <?php echo display_error($error, "FreeWheelChair"); ?><label>Gratis rolstoel</label><select name="FreeWheelChair" class="input"><option></option><option value="true" <?php echo set_selected($_POST, "FreeWheelChair", "true", $succes_class) ?> >Ja</option><option value="false" <?php echo set_selected($_POST, "FreeWheelChair", "false", $succes_class) ?>>Nee</option></select>
        <?php echo display_error($error, "FreeServiceDog"); ?><label>Gratis Hulphond</label><select name="FreeServiceDog" class="input"><option></option><option value="true" <?php echo set_selected($_POST, "FreeServiceDog", "true", $succes_class) ?> >Ja</option><option value="false" <?php echo set_selected($_POST, "FreeServiceDog", "false", $succes_class) ?> >Nee</option></select><br />
    
        <label>&nbsp;</label><input type="submit" value="Opslaan" />
    </form>
</div>
<div style="clear: both;"></div>
<?php
}
elseif (isset($_GET["action"]) && $_GET["action"] == "edit") {
?>

<!--Edit-->

<h1 style="margin-left: 20px;">Luchtvaartmaatschappijen beheren</h1><br /><br />

<form action="airline.php" method="get" style="text-align: center;">
    Luchtvaartmaatschappij: <input type="text" name="airline_name" id="airline_name" <?php if(isset($_GET["airline_name"]) && $_GET["airline_name"] != ""){
                                                                                            echo 'value="' .htmlspecialchars($_GET["airline_name"]) .'"';
                                                                                        } else if(isset($_GET["airline_id"]) && $_GET["airline_id"] != ""){
                                                                                            $airline = airline::get_airline($_GET["airline_id"], 0);
                                                                                            if(count($airline) > 0){
                                                                                                echo 'value="' .htmlspecialchars($airline->name) .' (' .$airline->iata .')"';
                                                                                            }
                                                                                        } ?> />
    <input type="hidden" name="action" value="edit" />
    <input type="submit" value="Beheren" />
</form>

<form action="airline.php" method="get" style="text-align: center;" id="airline_select_box">
    <input type="hidden" name="action" value="edit" />
    <select name="airline_id" id="airline_id_select" multiple="true" style="width: 300px;">
    <?php
    $airlines = airline::get_airlines();
    if(count($airlines) > 0){
        foreach($airlines as $airline){
            echo '<option value="' .$airline->airline_id .'">' .htmlspecialchars($airline->name) .'</option>';
        }
    }
    ?>

    </select>
</form>

<?php
if(isset($_GET["airline_name"]) || isset($_GET["airline_id"])){
    if(isset($_GET["airline_name"])){
        $name = explode("(", $_GET["airline_name"]);
        if(count($name) == 1){
            $name = $name[0];
        }
        else{
            unset($name[count($name) - 1]);
            $name = trim(implode("(", $name));
        }
        $edit_airline = airline::get_airline_by_name($name);
    }
    else if(isset($_GET["airline_id"])){
        $edit_airline = airline::get_airline($_GET["airline_id"], "all");
    }
    if(count($edit_airline) == 0){
        echo "<br /><p>De gekozen luchtvaartmaatschappij bestaat niet.</p>";
    }  
    else{ ?>
<br />
<div id="left">
    <h1 style="margin-left: 20px;">Luchtvaartmaatschappij beheren</h1><br />
    <?php 
    if(isset($succes_airline) && $succes_airline){
        echo '<strong style="margin-left: 20px;">Luchtvaartmaatschappij succesvol bijgewerkt.</strong>';
    }
    ?>
    <form action="airline.php?action=edit&airline_id=<?php echo htmlspecialchars($edit_airline->airline_id); ?>" method="post" class="form" enctype="multipart/form-data">
        <input type="hidden" name="act" value="airline" />
        <input type="hidden" name="airline_id" value="<?php echo $edit_airline->airline_id; ?>" />
        <?php echo display_error($error, "naam"); ?><label>Naam</label><input type="text" name="naam" <?php echo add_existing_value($edit_airline->name,$_POST, "naam", $succes_airline); ?> />
        <img class="input" style="margin-left: 15px;" src="../images/airlines/<?php echo $edit_airline->logo ?>" alt="Logo luchtvaartmaatschappij" height="100" width="100" />
        <?php echo display_error($error, "logo"); ?><label>Logo</label><input type="file" name="logo" />
        <?php echo display_error($error, "iata"); ?><label>Iata code</label><input type="text" name="iata" <?php echo add_existing_value($edit_airline->iata, $_POST, "iata", $succes_airline); ?> />
        <?php echo display_error($error, "OverweightChargeG"); ?><label>Kosten per extra kilogram &euro;</label><input type="text" name="OverweightChargeG" <?php echo add_existing_value($edit_airline->OverweightChargeG, $_POST, "OverweightChargeG", $succes_airline) ?> />
        <?php echo display_error($error, "OverweightChargeBag"); ?><label>Kosten overgewicht koffer &euro;</label><input type="text" name="OverweightChargeBag" <?php echo add_existing_value($edit_airline->OverweightChargeBag, $_POST, "OverweightChargeBag", $succes_airline) ?> /> 
        
        <div id="koffers">
            <?php
            if(!$succes_airline && (isset($_POST["ChargeExtraBag"]) && is_array($_POST["ChargeExtraBag"]))){
                foreach($_POST["ChargeExtraBag"] as $value => $koffer){
                    echo display_error($error, "ChargeExtraBag". $value) .'<label>Kosten extra koffer ' .($value +1) .' &euro;</label><input type="text" name="ChargeExtraBag[' .$value .']" value="' .htmlspecialchars($koffer) .'" />';
                }
            }
            elseif(count($edit_airline->ChargeExtraBag) == 0){
            echo display_error($error, "ChargeExtraBag0") ?><label>Kosten extra koffer 1 &euro;</label><input type="text" name="ChargeExtraBag[0]" <?php echo add_value($_POST, "ChargeExtraBag0", $succes_airline) ?> />
            <?php
            }
            else{
                foreach($edit_airline->ChargeExtraBag as $charge){
                    echo '<label>Kosten extra koffer ' .($charge->number +1) .' &euro;</label><input type="text" name="ChargeExtraBag[' .$charge->number .']" value="' .$charge->costs .'" />';
                }
            }
            ?>
        </div>
        <label>&nbsp;</label><button class="input" onclick="return add_koffer();">Koffer toevoegen</button>
        
        <?php echo display_error($error, "OversizeCharge"); ?><label>Kosten te grote koffer &euro;</label><input type="text" name="OversizeCharge" <?php echo add_existing_value($edit_airline->OversizeCharge, $_POST, "OversizeCharge", $succes_airline) ?> /> 
        <?php echo display_error($error, "notes"); ?><label>Opmerkingen</label><textarea rows="10" cols="25" class="input" name="notes"><?php echo htmlspecialchars(!$succes_airline && isset($_POST["notes"]) ? $_POST["notes"] : $edit_airline->notes); ?></textarea>
        
        <label>&nbsp;</label><input type="submit" value="Opslaan" />
    </form>
</div>

<div id="right">
    <h1 style="margin-right: 20px;">Klas luchtvaartmaatschappij beheren</h1><br />
    
        <?php if(isset($succes_class) && $succes_class){ ?>
    <strong>Klas succesvol bewerkt</strong>
    <?php } ?>
    
    <?php
    if(count($edit_airline->classes) == 0){
        echo "<p>Deze luchtvaartmaatschappij heeft nog geen classes.<br />Klik op 'toevoegen' bovenin om ze toe te voegen.</p>";
    }
    else
    {
    ?>
    <form action="airline.php" class="form" id="class_form">
        <input type="hidden" name="airline_id" value="<?php echo $edit_airline->airline_id; ?>" />
        <input type="hidden" name="action" value="edit" />
        <label>Klas:</label><select class="input class_edit" name="class"><option></option>
        <?php
        foreach($edit_airline->classes as $class){
            switch($class->classnumber){
                case 0: $klasnaam = "Economy";
                break;
                
                case 1: $klasnaam = "Eerste klas";
                break;
                
                case 2: $klasnaam = "Busines klas";
                break;
            }
            if(set_selected($_GET, "class", $class->classnumber, false) != null){
                $edit_class = $class;
            }
            echo '<option value=' .$class->classnumber .' ' .set_selected($_GET, "class", $class->classnumber, false) .'>' .$klasnaam .'</option>';
        }
        ?>
        </select>
    </form>
    <?php 
    }
    if(isset($edit_class)){
        
    ?>
    
    <form action="airline.php?action=edit&airline_id=<?php echo $edit_airline->airline_id; ?>&class=<?php echo $edit_class->classnumber; ?>" method="post" class="form">
        <input type="hidden" name="act" value="class" />

        <!--Ruimbagage-->
        <label class="title">Ruimbagage</label><br />
        <?php echo display_error($error, "pcs_weight"); ?><label>Stukken of gewicht</label><select id="ruimbagage" class="input" name="pcs_weight"><option></option><option value="pcs" <?php $a[0] = set_selected_on_set($_POST, "pcs_weight", "pcs", $succes_class, array("pcsLuggage", "pcsLuggageInfant"), $edit_class, array("maxWeightLuggage", "pcsLuggageInfantMaxWeight"), true); echo $a[0] ?>>Stukken</option><option value="weight" <?php $a[1] = set_selected_on_set($_POST, "pcs_weight", "weight", $succes_class, array("maxWeightLuggage", "pcsLuggageInfantMaxWeight"), $edit_class, array("pcsLuggage", "pcsLuggageInfant"), true); echo $a[1] ?>>Gewicht</option><option value="both" <?php if($a[0] == null && $a[1] == null){ echo set_selected_on_set($_POST, "pcs_weight", "both", $succes_class, array("pcsLuggage", "pcsLuggageInfant", "maxWeightLuggage", "pcsLuggageInfantMaxWeight"), $edit_class, null, true); } ?>>Beide</option></select>
        <div class="pcs"><?php echo display_error($error, "pcsLuggage"); ?><label>Stukken bagage</label><input type="text" name="pcsLuggage" <?php echo add_existing_value($edit_class->pcsLuggage, $_POST, "pcsLuggage", $succes_class); ?> />
        <?php echo display_error($error, "pcsLuggageInfant"); ?><label>Stukken bagage baby</label><input type="text" name="pcsLuggageInfant" <?php echo add_existing_value($edit_class->pcsLuggageInfant, $_POST, "pcsLuggageInfant", $succes_class); ?> /></div>
        <div class="weight"><?php echo display_error($error, "maxWeightLuggage"); ?><label>Max. gewicht bagage kg</label><input type="text" name="maxWeightLuggage" <?php echo add_existing_value($edit_class->maxWeightLuggage, $_POST, "maxWeightLuggage", $succes_class); ?> />
        <?php echo display_error($error, "pcsLuggageInfantMaxWeight"); ?><label>Max. gewicht bagage baby kg</label><input type="text" name="pcsLuggageInfantMaxWeight" <?php echo add_existing_value($edit_class->pcsLuggageInfantMaxWeight, $_POST, "pcsLuggageInfantMaxWeight", $succes_class); ?> /></div><br />
        
        <!--Items-->
        <label class="title">Koffers</label><br />
        <?php echo display_error($error, "lengte_totaalI"); ?><label>LxHxB of totaal</label><select class="input" id="lengte_selectI" name="lengte_totaalI"><option></option><option value="l" <?php echo set_selected_on_set($_POST, "lengte_totaalI", "l", $succes_class, array("sizeLenghtPerItem", "sizeHeightPerItem", "sizeWidthPerItem"), $edit_class); ?>>LxHxB</option><option value="t" <?php echo set_selected_on_set($_POST, "lengte_totaalI", "t", $succes_class, array("sizeTotalPerItem"), $edit_class); ?>>Totaal</option></select>
        <div class="lI"><?php echo display_error($error, "sizeLenghtPerItem"); ?><label>Lengte per koffer cm</label><input type="text" name="sizeLenghtPerItem"  <?php echo add_existing_value($edit_class->sizeLenghtPerItem, $_POST, "sizeLenghtPerItem", $succes_class); ?> />
        <?php echo display_error($error, "sizeHeightPerItem"); ?><label>Hoogte per koffer cm</label><input type="text" name="sizeHeightPerItem" <?php echo add_existing_value($edit_class->sizeHeightPerItem, $_POST, "sizeHeightPerItem", $succes_class); ?> />
        <?php echo display_error($error, "sizeWidthPerItem"); ?><label>Breedte per koffer cm</label><input type="text" name="sizeWidthPerItem" <?php echo add_existing_value($edit_class->sizeWidthPerItem, $_POST, "sizeWidthPerItem", $succes_class); ?> /></div>
        <div class="tI"><?php echo display_error($error, "sizeTotalPerItem"); ?><label>Totale omtrek per koffer cm</label><input type="text" name="sizeTotalPerItem" <?php echo add_existing_value($edit_class->sizeTotalPerItem, $_POST, "sizeTotalPerItem", $succes_class); ?> /></div><br />
        
        <!--Handbagage-->
        <label class="title">Handbagage</label><br />
        <?php echo display_error($error, "pcs_weightHL"); ?><label>Stukken of gewicht</label><select id="handbagage" class="input" name="pcs_weightHL"><option></option><option value="pcs" <?php $b[0] = set_selected_on_set($_POST, "pcs_weightHL", "pcs", $succes_class, array("pcsHL", "pcsInfantHL"), $edit_class, array("MaxWeightHL"), true); echo $b[0]; ?>>Stukken</option><option value="weight" <?php $b[1] = set_selected_on_set($_POST, "pcs_weightHL", "weight", $succes_class, array("MaxWeightHL"), $edit_class, array("pcsHL", "pcsInfantHL"), true); echo $b[1]?>>Gewicht</option><option value="both" <?php if($b[0] == null && $b[1] == null) { echo set_selected_on_set($_POST, "pcs_weightHL", "both", $succes_class, array("MaxWeightHL", "pcsHL", "pcsInfantHL"), $edit_class, null, true); } ?>>Beide</option></select>
        <div class="pcsHL"><?php echo display_error($error, "pcsHL"); ?><label>Stukken handbagage</label><input type="text" name="pcsHL" <?php echo add_existing_value($edit_class->pcsHL, $_POST, "pcsHL", $succes_class); ?> />
        <?php echo display_error($error, "pcsInfantHL"); ?><label>Stukken handbagage baby</label><input type="text" name="pcsInfantHL" <?php echo add_existing_value($edit_class->pcsInfantHL, $_POST, "pcsInfantHL", $succes_class); ?> /></div>
        <div class="weightHL"><?php echo display_error($error, "MaxWeightHL"); ?><label>Max. gewicht handbagage kg</label><input type="text" name="MaxWeightHL" <?php echo add_existing_value($edit_class->MaxWeightHL, $_POST, "MaxWeightHL", $succes_class); ?> />
        <?php echo display_error($error, "MaxWeightInfantHL"); ?><label>Max gewicht handbagage baby kg</label><input type="text" name="MaxWeightInfantHL" <?php echo add_existing_value($edit_class->MaxWeightInfantHL, $_POST, "MaxWeightInfantHL", $succes_class); ?> />
        </div>
        
        <?php echo display_error($error, "lengte_totaal"); ?><label>LxHxB of totaal</label><select class="input" id="lengte_select" name="lengte_totaal"><option></option><option value="l" <?php echo set_selected_on_set($_POST, "lengte_totaal", "l", $succes_class, array("sizeLenghtHL", "sizeHeightHL", "SizeWidthHL"), $edit_class); ?>>LxHxB</option><option value="t" <?php echo set_selected_on_set($_POST, "lengte_totaal", "t", $succes_class, array("sizeTotalHL"), $edit_class); ?>>Totaal</option></select>
        <div class="l"><?php echo display_error($error, "sizeLenghtHL"); ?><label>Lengte handbagage cm</label><input type="text" name="sizeLenghtHL" <?php echo add_existing_value($edit_class->sizeLenghtHL, $_POST, "sizeLenghtHL", $succes_class); ?> />
        <?php echo display_error($error, "sizeHeightHL"); ?><label>Hoogte handbagage cm</label><input type="text" name="sizeHeightHL" <?php echo add_existing_value($edit_class->sizeHeightHL, $_POST, "sizeHeightHL", $succes_class); ?> />
        <?php echo display_error($error, "SizeWidthHL"); ?><label>Breedte handbagage cm</label><input type="text" name="SizeWidthHL" <?php echo add_existing_value($edit_class->SizeWidthHL, $_POST, "SizeWidthHL", $succes_class); ?> /></div>
        <div class="t"><?php echo display_error($error, "sizeTotalHL"); ?><label>Totale omtrek handbagage cm</label><input type="text" name="sizeTotalHL" <?php echo add_existing_value($edit_class->sizeTotalHL, $_POST, "sizeTotalHL", $succes_class); ?> /></div>
        <?php echo display_error($error, "petsAllowedHL"); ?><label>Huisdieren toegestaan</label><select class="input" name="petsAllowedHL"><option></option><option value="true" <?php echo set_selected_tf($_POST, "petsAllowedHL", "true", $succes_class, $edit_class, "petsAllowedHL", 1); ?>>Ja</option><option value="false" <?php echo set_selected_tf($_POST, "petsAllowedHL", "false", $succes_class, $edit_class, "petsAllowedHL", 0); ?>>Nee</option></select><br />
        
        <!--LP-->
        <label class="title">Loyalty programma (LP)</label><br />
        <?php echo display_error($error, "LoyaltyProgramme"); ?><label>Loyalty programma</label><select id="LP_select" class="input" name="LoyaltyProgramme"><option></option><option value="true" <?php echo set_selected_tf($_POST, "LoyaltyProgramme", "true", $succes_class, $edit_class, "LoyaltyProgramme", 1) ?>>Ja</option><option value="false" <?php echo set_selected_tf($_POST, "LoyaltyProgramme", "false", $succes_class, $edit_class, "LoyaltyProgramme", 0) ?>>Nee</option></select>
        <div class="LP"><?php echo display_error($error, "LPextraPcsLuggage"); ?><label>Extra stukken bagage LP</label><input type="text" name="LPextraPcsLuggage" <?php echo add_existing_value($edit_class->LPextraPcsLuggage, $_POST, "LPextraPcsLuggage", $succes_class); ?> />
        <?php echo display_error($error, "LPextraWeightLuggage"); ?><label>Extra gewicht bagage LP kg</label><input type="text" name="LPextraWeightLuggage" <?php echo add_existing_value($edit_class->LPextraWeightLuggage, $_POST, "LPextraWeightLuggage", $succes_class); ?> />
        <?php echo display_error($error, "AbsoluteMaxPerItem"); ?><label>Abs. max. gewicht bagage kg</label><input type="text" name="AbsoluteMaxPerItem" <?php echo add_existing_value($edit_class->AbsoluteMaxPerItem, $_POST, "AbsoluteMaxPerItem", $succes_class); ?> /></div><br />
        
        <!--Huisdieren-->
        <label class="title">Huisdieren inclusief kooi (Vrachtruim)</label><br />
        <?php echo display_error($error, "PetsAllowed"); ?><label>Huisdieren toegestaan</label><select id="pets" name="PetsAllowed" class="input"><option></option><option value="true" <?php echo set_selected_tf($_POST, "PetsAllowed", "true", $succes_class, $edit_class, "PetsAllowed", 1); ?>>Ja</option><option value="false" <?php echo set_selected_tf($_POST, "PetsAllowed", "false", $succes_class, $edit_class, "PetsAllowed", 0); ?>>Nee</option></select>
        <div class="pets"><?php echo display_error($error, "CostsPet"); ?><label>Kosten huisdier &euro;</label><input type="text" name="CostsPet" <?php echo add_existing_value($edit_class->CostsPet, $_POST, "CostsPet", $succes_class); ?> />
        <?php echo display_error($error, "MaxWeightPet"); ?><label>Max. gewicht huisdier kg</label><input type="text" name="MaxWeightPet" <?php echo add_existing_value($edit_class->MaxWeightPet, $_POST, "MaxWeightPet", $succes_class); ?> />
        
        <?php echo display_error($error, "lengte_totaalPets"); ?><label>LxHxB of totaal</label><select class="input" id="lengte_selectPets" name="lengte_totaalPets"><option></option><option value="l" <?php echo set_selected_on_set($_POST, "lengte_totaalPets", "l", $succes_class, array("sizeLenghtPet", "sizeHeightPet", "sizeWidthPet"), $edit_class, array("sizeTotalPet")); ?>>LxHxB</option><option value="t" <?php echo set_selected_on_set($_POST, "lengte_totaalPets", "t", $succes_class, array("sizeTotalPet"), $edit_class, array("sizeLenghtPet", "sizeHeightPet", "sizeWidthPet")); ?>>Totaal</option></select>
        
        <div class="pets_lengte"><?php echo display_error($error, "sizeLenghtPet"); ?><label>Lengte kooi huisdier cm</label><input type="text" name="sizeLenghtPet" <?php echo add_existing_value($edit_class->sizeLenghtPet, $_POST, "sizeLenghtPet", $succes_class); ?> />
        <?php echo display_error($error, "sizeHeightPet"); ?><label>Hoogte kooi huisdier cm</label><input type="text" name="sizeHeightPet" <?php echo add_existing_value($edit_class->sizeHeightPet, $_POST, "sizeHeightPet", $succes_class); ?> />
        <?php echo display_error($error, "sizeWidthPet"); ?><label>Breedte kooi huisdier cm</label><input type="text" name="sizeWidthPet" <?php echo add_existing_value($edit_class->sizeWidthPet, $_POST, "sizeWidthPet", $succes_class); ?> /></div>
        <div class="pets_totaal"><?php echo display_error($error, "sizeTotalPet"); ?><label>Omtrek kooi huisdier cm</label><input type="text" name="sizeTotalPet" <?php echo add_existing_value($edit_class->sizeTotalPet, $_POST, "sizeTotalPet", $succes_class); ?> /></div></div><br />
        
        <!--Waardeaangifte-->
        <label class="title">Waardeaangifte</label><br />
        <?php echo display_error($error, "DeclarationOfValue"); ?><label>Waardeaangifte</label><select class="input" id="DOV_select" name="DeclarationOfValue"><option></option><option value="true" <?php echo set_selected_tf($_POST, "DeclarationOfValue", "true", $succes_class, $edit_class, "DeclarationOfValue", 1); ?>>Ja</option><option value="false" <?php echo set_selected_tf($_POST, "DeclarationOfValue", "false", $succes_class, $edit_class, "DeclarationOfValue", 0); ?>>Nee</option></select>
        <div class="DeclarationOfValue"><?php echo display_error($error, "MaxDeclarationOfValue"); ?><label>Max. waardeaangifte &euro;</label><input type="text" name="MaxDeclarationOfValue" <?php echo add_existing_value($edit_class->MaxDeclarationOfValue, $_POST, "MaxDeclarationOfValue", $succes_class); ?> /></div><br />
        
        <!--Ja/Nee-->
        <br />
        <?php echo display_error($error, "LaptopAllowedHL"); ?><label>Laptop toegestaan</label><select class="input" name="LaptopAllowedHL"><option></option><option value="true" <?php echo set_selected_tf($_POST, "LaptopAllowedHL", "true", $succes_class, $edit_class, "LaptopAllowedHL", 1) ?>>Ja</option><option value="false" <?php echo set_selected_tf($_POST, "LaptopAllowedHL", "false", $succes_class, $edit_class, "LaptopAllowedHL",0) ?>>Nee</option></select>
        <?php echo display_error($error, "Pooling"); ?><label>Pooling</label><select name="Pooling" class="input"><option></option><option value="true" <?php echo set_selected_tf($_POST, "Pooling", "true", $succes_class, $edit_class, "Pooling", 1) ?>>Ja</option><option value="false" <?php echo set_selected_tf($_POST, "Pooling", "false", $succes_class, $edit_class, "Pooling", 0) ?>>Nee</option></select>
        <?php echo display_error($error, "FreeWheelChair"); ?><label>Gratis rolstoel</label><select name="FreeWheelChair" class="input"><option></option><option value="true" <?php echo set_selected_tf($_POST, "FreeWheelChair", "true", $succes_class, $edit_class, "FreeWheelChair", 1) ?> >Ja</option><option value="false" <?php echo set_selected_tf($_POST, "FreeWheelChair", "false", $succes_class, $edit_class, "FreeWheelChair", 0) ?>>Nee</option></select>
        <?php echo display_error($error, "FreeServiceDog"); ?><label>Gratis Hulphond</label><select name="FreeServiceDog" class="input"><option></option><option value="true" <?php echo set_selected_tf($_POST, "FreeServiceDog", "true", $succes_class, $edit_class, "FreeServiceDog", 1) ?> >Ja</option><option value="false" <?php echo set_selected_tf($_POST, "FreeServiceDog", "false", $succes_class, $edit_class, "FreeServiceDog", 0) ?> >Nee</option></select><br />
    
        <label>&nbsp;</label><input type="submit" value="Opslaan" />
    </form>
    <?php 
    }
    else if(count($edit_airline->classes) > 0){
        echo "<p>Selecteer een class om deze te bewerken.</p>";
    }
    ?>
</div>

<div style="clear: both;"></div>
<br /><br />

<form action="airline.php" method="get" style="float: left;">
    <input type="hidden" name="action" value="del" />
    <input type="hidden" name="airline_id" value="<?php echo $edit_airline->airline_id; ?>" />
    <input type="submit" value="Luchtvaartmaatschappij verwijderen" />
</form>
<?php
if(isset($_GET["class"]) && validator::isInt($_GET["class"])){
    echo '<form action="airline.php" method="get" style=float: left;">
    <input type="hidden" name="action" value="del" />
    <input type="hidden" name="airline_id" value="' .$edit_airline->airline_id .'" />
    <input type="hidden" name="class_number" value="' .$_GET["class"] .'" />
    <input type="submit" value="Class verwijderen" />
</form>';
}
echo '<div style="clear:both;"></div>';
}
?>



<?php } ?>

<?php
}
elseif(isset($_GET["action"]) && $_GET["action"] == "del"){
    
    
    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["del"])){
        if(isset($_POST["airline_id"]) && !isset($_POST["class_number"])){
            if($_POST["del"] == "true" && validator::isInt($_POST["airline_id"])){
                airline::remove_airline($_POST["airline_id"]);
                echo "Luchtvaartmaatschappij verwijderd.";
            }
            elseif($_POST["del"] == "false"){
                echo "Luchtvaartmaatschappij niet verwijderd.";
            }
        }
        elseif(isset($_POST["airline_id"]) && isset($_POST["class_number"])){
            if($_POST["del"] == "true" && validator::isInt($_POST["airline_id"]) && validator::isInt($_POST["class_number"])){
                airline::remove_class($_POST["airline_id"], $_POST["class_number"]);
                echo "Class verwijderd.";
            }
            elseif($_POST["del"] == "false"){
                echo "Class niet verwijderd.";
            }
        }
    }
    elseif(isset($_GET["airline_id"]) && !isset($_GET["class_number"]))
    {
        $del_airline = airline::get_airline($_GET["airline_id"], "all");
        if(count($del_airline) == 0){
            echo "De opgegeven luchtvaartmaatschappij bestaat (niet) meer.";
        }
    else{
?>
<p style="color: red; font-size: 15pt; text-align: center;">Weet u zeker dat u <?php echo htmlspecialchars($del_airline->name); ?> met alle bijbehorende classes wilt verwijderen?</p>
<div id="left">
    <form action="airline.php?action=del" method="post">
        <input type="hidden" name="airline_id" value="<?php echo $del_airline->airline_id ?>" />
        <input type="hidden" name="del" value="true" />
        <input type="submit" value="Ja" />
    </form>
</div>
<div id="right">
    <form action="airline.php?action=del" method="post">
        <input type="hidden" name="del" value="false" />
        <input type="hidden" name="airline_id" value="<?php echo $del_airline->airline_id ?>" />
        <input type="submit" value="Nee" />
    </form>
</div>
<div style="clear: both;"></div>
<?php
}
}
elseif(isset($_GET["airline_id"]) && isset($_GET["class_number"])){
    $del_airline = airline::get_airline($_GET["airline_id"], $_GET["class_number"]);
    if(count($del_airline) == 0){
        echo "De opgegeven luchtvaartmaatschappij bestaat (niet) meer.";
    }
    else{
    ?>
    <p style="color: red; font-size: 15pt; text-align: center;">Weet u zeker dat u deze class wilt verwijderen?</p>
    <div id="left">
        <form action="airline.php?action=del" method="post">
            <input type="hidden" name="airline_id" value="<?php echo $del_airline->airline_id ?>" />
            <input type="hidden" name="class_number" value="<?php echo $_GET["class_number"]; ?>" />
            <input type="hidden" name="del" value="true" />
            <input type="submit" value="Ja" />
        </form>
    </div>
    <div id="right">
        <form action="airline.php?action=del" method="post">
            <input type="hidden" name="del" value="false" />
            <input type="hidden" name="class_number" value="<?php echo $_GET["class_number"]; ?>" />
            <input type="hidden" name="airline_id" value="<?php echo $del_airline->airline_id ?>" />
            <input type="submit" value="Nee" />
        </form>
    </div>
    <div style="clear: both;"></div>
    <?php
    }
}
}
require_once ("onderkant.php");
?>