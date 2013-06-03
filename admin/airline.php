<?php
//Alle data classes includen
require_once ("../data/includeAll.php");
$titel = "Vliegmaatschappijen";
require_once ("bovenkant.php");

$succes_airline = false;
$succes_class = false;
$error = null;

function display_error($error, $indexname){
    if(isset($error[$indexname])){
        return '<span style="color:red;">' .$error[$indexname] ."</span>";
    }
}
function add_value($post, $postindex, $succes_var){
    if(!$succes_var && isset($post[$postindex])){
        return 'value="' .htmlspecialchars($post[$postindex]) .'"';
    }
}
function set_selected($post, $postindex, $value, $succes_var){
    if(!$succes_var && isset($post[$postindex]) && $post[$postindex] == $value){
        return 'selected="true"';
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["act"]) && $_POST["act"] == "airline") {
    $postvelden = array("naam", "iata", "OverweightChargeG", "OverweightChargeBag", "ChargeExtraBag", "OversizeCharge", "notes");
        
        foreach($postvelden as $postveld){
            if(!isset($_POST[$postveld]) || (empty($_POST[$postveld]) && $_POST[$postveld] != "0")){
                $error[$postveld] = 'Veld niet ingevuld.';
            }
            elseif($postveld == "ChargeExtraBag"){
                if(is_array($_POST[$postveld])){
                    foreach($_POST[$postveld] as $key => $koffer){
                        if(!validator::isInt($koffer)){
                            $error[$postveld .$key] = "Vul een getal in";
                        }
                    }
                }
            }
            elseif($postveld == "naam"){
                if(airline::airline_name_exists($_POST[$postveld])){
                    $error["naam"] = 'Vliegmaatschappij bestaat al.';
                }
            }
            elseif($postveld == "iata"){
                if(!validator::stringLimit(1, 3, $_POST[$postveld])){
                    $error["iata"] = 'Een iata code mag maximaal 3 karakters bevatten.';
                }
            }

            else if($postveld != "notes"){
                if(!validator::isInt($_POST[$postveld])){
                    $error[$postveld] = 'Vul een getal in.';
                }
            }
        }
        
        if($_FILES["logo"]["error"] == 0){
            $permitted = array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/png');
            if(in_array($_FILES["logo"]["type"], $permitted)){
                $type = explode("/", $_FILES["logo"]["type"]);
                $type = $type[1];
                if($type == "pjpeg"){
                    $type = "jpeg";
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
            airline::add_airline_without_class($_POST["naam"], $name, $_POST["OverweightChargeG"], $_POST["OverweightChargeBag"], $_POST["ChargeExtraBag"], $_POST["OversizeCharge"], $_POST["iata"], $_POST["notes"]);
            $succes_airline = true;
        }
}
elseif($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["act"]) && $_POST["act"] == "class"){
    $required = array("airline_name", "classnumber", "pcs_weight","pcs_weightHL","LoyaltyProgramme","PetsAllowed","DeclarationOfValue","LaptopAllowedHL",
                        "Pooling","FreeWheelChair","FreeServiceDog", "petsAllowedHL", "lengte_totaal", "lengte_totaalI");
                        
    
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
    }
    elseif(isset($_POST["pcs_weightHL"]) && $_POST["pcs_weightHL"] == "both"){
        $required[] = "pcsHL";
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
        $required[] = "sizeLenghtPet";
        $required[] = "sizeHeightPet";
        $required[] = "sizeWidthPet";
        $required[] = "sizeTotalPet";
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
        if(!isset($_POST[$requiredField]) || (empty($_POST[$requiredField]) && $_POST[$requiredField] != "0")){
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
        elseif(!in_array($requiredField, array("lengte_totaal", "lengte_totaalI", "petsAllowedHL", "airline_name", "pcs_weight", "pcs_weightHL", "FreeServiceDog", "FreeWheelChair", "Pooling", "strollerAllowedHL", "LaptopAllowedHL", "DeclarationOfValue", "PetsAllowed", "LoyaltyProgramme"))){
            if(!validator::isInt($_POST[$requiredField])){
                $error[$requiredField] = "Vul een getal in.";
            }
        }
    }
                       
    if(isset($_POST["ailine_name"]) && !airline::airline_name_exists($_POST["airline_name"])){ //todo explode op haken
        $error["airline_name"] = "Luchtvaartmaatschappij bestaat nog niet.";
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
        $name = explode("(", $_POST["airline_name"]);
        unset($name[count($name) - 1]);
        $name = trim(implode("(", $name));
        $airline = airline::get_airline_by_name($name); //todo explode fix op haken
        airline::add_class($airline->airline_id, $_POST["classnumber"], $_POST["pcsHL"], $_POST["MaxWeightHL"], $_POST["sizeLenghtHL"], $_POST["sizeHeightHL"], $_POST["SizeWidthHL"], $_POST["sizeTotalHL"], $_POST["LaptopAllowedHL"], $_POST["pcsInfantHL"],
        $_POST["pcsLuggageInfant"], $_POST["pcsLuggageInfantMaxWeight"], $_POST["pcsLuggage"],
        $_POST["maxWeightLuggage"], $_POST["LoyaltyProgramme"], $_POST["LPextraPcsLuggage"], $_POST["LPextraWeightLuggage"],
        $_POST["AbsoluteMaxPerItem"], $_POST["sizeLenghtPerItem"], $_POST["sizeHeightPerItem"], $_POST["sizeWidthPerItem"],
        $_POST["sizeTotalPerItem"], $_POST["Pooling"], $_POST["FreeWheelChair"], $_POST["FreeServiceDog"], $_POST["PetsAllowed"], $_POST["MaxWeightPet"],
        $_POST["sizeLenghtPet"], $_POST["sizeHeightPet"], $_POST["sizeWidthPet"], $_POST["sizeTotalPet"], $_POST["DeclarationOfValue"],
        $_POST["MaxDeclarationOfValue"], $_POST["petsAllowedHL"]);
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
            <a<?php echo (isset($_GET["action"]) && $_GET["action"] == "edit" ?' class="active"' : "") ?> href="airline.php?action=edit">Beheren</a>
        </li>
    </ul>
</div>
<div style="clear: both;"></div><br />

<?php
if (isset($_GET["action"]) && $_GET["action"] == "add") {
?>
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
    $("#koffers").append('<label>Kosten extra koffer ' +(koffer_num+2) +'</label><input type="text" name="ChargeExtraBag[' +(koffer_num+1) +']" />');
    return false;
  }
  </script>

<!--Add-->
<div id="left">
    <h1 style="margin-left: 20px;">Vliegmaatschappij toevoegen</h1><br />
    <?php if(isset($succes_airline) && $succes_airline){ ?>
    <strong>Vliegmaatschappij <?php echo htmlspecialchars($_POST["naam"]); ?> succesvol toegevoegd.</strong>
    <?php } ?>
    <form action="airline.php?action=add" method="post" class="form" enctype="multipart/form-data">
        <input type="hidden" name="act" value="airline" />
        <?php echo display_error($error, "naam"); ?><label title="Naam van de vliegmaatschappij">Naam</label><input type="text" name="naam" <?php echo add_value($_POST, "naam", $succes_airline); ?> />
        <?php echo display_error($error, "logo"); ?><label title="Logo van de vligmaatschappij">Logo</label><input type="file" name="logo" />
        <?php echo display_error($error, "iata"); ?><label title="Iata code van de vliegmaatschappij">Iata code</label><input type="text" name="iata" <?php echo add_value($_POST, "iata", $succes_airline); ?> />
        <?php echo display_error($error, "OverweightChargeG"); ?><label title="Kosten in euro's die extra worden gerekend per extra kilogram gewicht">Kosten per extra kilogram:</label><input type="text" name="OverweightChargeG" <?php echo add_value($_POST, "OverweightChargeG", $succes_airline) ?> />
        <?php echo display_error($error, "OverweightChargeBag"); ?><label title="Kosten die extra worden gerekend bij overgewicht van een koffer">Kosten overgewicht koffer:</label><input type="text" name="OverweightChargeBag" <?php echo add_value($_POST, "OverweightChargeBag", $succes_airline) ?> /> 
        
        <div id="koffers">
            <?php
            if(!$succes_airline && (isset($_POST["ChargeExtraBag"]) && is_array($_POST["ChargeExtraBag"]))){
                foreach($_POST["ChargeExtraBag"] as $value => $koffer){
                    echo display_error($error, "ChargeExtraBag". $value) .'<label>Kosten extra koffer ' .($value +1) .'</label><input type="text" name="ChargeExtraBag[' .$value .']" value="' .htmlspecialchars($koffer) .'" />';
                }
            }
            else{        
            ?>
            <?php echo display_error($error, "ChargeExtraBag0") ?><label>Kosten extra koffer 1</label><input type="text" name="ChargeExtraBag[0]" <?php echo add_value($_POST, "ChargeExtraBag0", $succes_airline) ?> />
            <?php
            }
            ?>
        </div>
        <label>&nbsp;</label><button class="input" onclick="return add_koffer();">Koffer toevoegen</button>
        
        <?php echo display_error($error, "OversizeCharge"); ?><label title="Kosten die worden gerekend als een koffer te groot is">Kosten te grote koffer:</label><input type="text" name="OversizeCharge" <?php echo add_value($_POST, "OversizeCharge", $succes_airline) ?> /> 
        <?php echo display_error($error, "notes"); ?><label>Opmerkingen</label><textarea rows="10" cols="25" class="input" name="notes"><?php echo htmlspecialchars(!$succes_airline && isset($_POST["notes"]) ? $_POST["notes"] : ""); ?></textarea>
        
        <label>&nbsp;</label><input type="submit" value="Opslaan" />
    
    </form>
</div>
<div id="right">
    <h1 style="margin-right: 20px;">Class toevoegen aan vliegmaatschappij</h1><br />
    
    <?php if(isset($succes_class) && $succes_class){ ?>
    <strong>Class toegevoegd aan <?php echo htmlspecialchars($_POST["airline_name"]); ?></strong>
    <?php } ?>
    
    <form action="airline.php?action=add" method="post" class="form">
        <input type="hidden" name="act" value="class" />
        <?php echo display_error($error, "airline_name"); ?><label>Vliegmaatschappij:</label><input type="text" id="airline_name" name="airline_name" <?php echo add_value($_POST, "airline_name", $succes_class); ?> /> 
        <?php echo display_error($error, "classnumber"); ?><label>Class:</label><select class="input" name="classnumber">
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
        <div class="weight"><?php echo display_error($error, "maxWeightLuggage"); ?><label>Max. gewicht bagage</label><input type="text" name="maxWeightLuggage" <?php echo add_value($_POST, "maxWeightLuggage", $succes_class); ?> />
        <?php echo display_error($error, "pcsLuggageInfantMaxWeight"); ?><label>Max. gewicht bagage baby</label><input type="text" name="pcsLuggageInfantMaxWeight" <?php echo add_value($_POST, "pcsLuggageInfantMaxWeight", $succes_class); ?> /></div><br />
        
        <!--Handbagage-->
        <label class="title">Handbagage</label><br />
        <?php echo display_error($error, "pcs_weightHL"); ?><label>Stukken of gewicht</label><select id="handbagage" class="input" name="pcs_weightHL"><option></option><option value="pcs" <?php echo set_selected($_POST, "pcs_weightHL", "pcs", $succes_class) ?>>Stukken</option><option value="weight" <?php echo set_selected($_POST, "pcs_weightHL", "weight", $succes_class) ?>>Gewicht</option><option value="both" <?php echo set_selected($_POST, "pcs_weightHL", "both", $succes_class) ?>>Beide</option></select>
        <div class="pcsHL"><?php echo display_error($error, "pcsHL"); ?><label>Stukken handbagage</label><input type="text" name="pcsHL" <?php echo add_value($_POST, "pcsHL", $succes_class); ?> />
        <?php echo display_error($error, "pcsInfantHL"); ?><label>Stukken handbagage baby</label><input type="text" name="pcsInfantHL" <?php echo add_value($_POST, "pcsInfantHL", $succes_class); ?> /></div>
        <div class="weightHL"><?php echo display_error($error, "MaxWeightHL"); ?><label>Max. gewicht handbagage</label><input type="text" name="MaxWeightHL" <?php echo add_value($_POST, "MaxWeightHL", $succes_class); ?> /></div>
        
        <?php echo display_error($error, "lengte_totaal"); ?><label>LxHxB of totaal</label><select class="input" id="lengte_select" name="lengte_totaal"><option></option><option value="l" <?php echo set_selected($_POST, "lengte_totaal", "l", $succes_class); ?>>LxHxB</option><option value="t" <?php echo set_selected($_POST, "lengte_totaal", "t", $succes_class); ?>>Totaal</option></select>
        <div class="l"><?php echo display_error($error, "sizeLenghtHL"); ?><label>Lengte handbagage</label><input type="text" name="sizeLenghtHL" <?php echo add_value($_POST, "sizeLenghtHL", $succes_class); ?> />
        <?php echo display_error($error, "sizeHeightHL"); ?><label>Hoogte handbagage</label><input type="text" name="sizeHeightHL" <?php echo add_value($_POST, "sizeHeightHL", $succes_class); ?> />
        <?php echo display_error($error, "SizeWidthHL"); ?><label>Breedte handbagage</label><input type="text" name="SizeWidthHL" <?php echo add_value($_POST, "SizeWidthHL", $succes_class); ?> /></div>
        <div class="t"><?php echo display_error($error, "sizeTotalHL"); ?><label>Totale grootte handbagage</label><input type="text" name="sizeTotalHL" <?php echo add_value($_POST, "sizeTotalHL", $succes_class); ?> /></div>
        <?php echo display_error($error, "petsAllowedHL"); ?><label>Huisdieren toegestaan</label><select class="input" name="petsAllowedHL"><option></option><option value="true" <?php echo set_selected($_POST, "petsAllowedHL", "true", $succes_class) ?>>Ja</option><option value="false" <?php echo set_selected($_POST, "petsAllowedHL", "false", $succes_class) ?>>Nee</option></select><br />
        
        <!--Items-->
        <label class="title">Items</label><br />
        <?php echo display_error($error, "lengte_totaalI"); ?><label>LxHxB of totaal</label><select class="input" id="lengte_selectI" name="lengte_totaalI"><option></option><option value="l" <?php echo set_selected($_POST, "lengte_totaalI", "l", $succes_class); ?>>LxHxB</option><option value="t" <?php echo set_selected($_POST, "lengte_totaalI", "t", $succes_class); ?>>Totaal</option></select>
        <div class="lI"><?php echo display_error($error, "sizeLenghtPerItem"); ?><label>Lengte per item</label><input type="text" name="sizeLenghtPerItem"  <?php echo add_value($_POST, "sizeLenghtPerItem", $succes_class); ?> />
        <?php echo display_error($error, "sizeHeightPerItem"); ?><label>Hoogte per item</label><input type="text" name="sizeHeightPerItem" <?php echo add_value($_POST, "sizeHeightPerItem", $succes_class); ?> />
        <?php echo display_error($error, "sizeWidthPerItem"); ?><label>Breedte per item</label><input type="text" name="sizeWidthPerItem" <?php echo add_value($_POST, "sizeWidthPerItem", $succes_class); ?> /></div>
        <div class="tI"><?php echo display_error($error, "sizeTotalPerItem"); ?><label>Totale grootte per item</label><input type="text" name="sizeTotalPerItem" <?php echo add_value($_POST, "sizeTotalPerItem", $succes_class); ?> /></div><br />
        
        <!--LP-->
        <label class="title">Loyalty programma (LP)</label><br />
        <?php echo display_error($error, "LoyaltyProgramme"); ?><label>Loyalty programma</label><select id="LP_select" class="input" name="LoyaltyProgramme"><option></option><option value="true" <?php echo set_selected($_POST, "LoyaltyProgramme", "true", $succes_class) ?>>Ja</option><option value="false" <?php echo set_selected($_POST, "LoyaltyProgramme", "false", $succes_class) ?>>Nee</option></select>
        <div class="LP"><?php echo display_error($error, "LPextraPcsLuggage"); ?><label>Extra stukken bagage LP</label><input type="text" name="LPextraPcsLuggage" <?php echo add_value($_POST, "LPextraPcsLuggage", $succes_class); ?> />
        <?php echo display_error($error, "LPextraWeightLuggage"); ?><label>Extra gewicht bagage LP</label><input type="text" name="LPextraWeightLuggage" <?php echo add_value($_POST, "LPextraWeightLuggage", $succes_class); ?> />
        <?php echo display_error($error, "AbsoluteMaxPerItem"); ?><label>Abs. max. gewicht bagage</label><input type="text" name="AbsoluteMaxPerItem" <?php echo add_value($_POST, "AbsoluteMaxPerItem", $succes_class); ?> /></div><br />
        
        <!--Huisdieren-->
        <label class="title">Huisdieren (Vrachtruim)</label><br />
        <?php echo display_error($error, "PetsAllowed"); ?><label>Huisdieren toegestaan</label><select id="pets" name="PetsAllowed" class="input"><option></option><option value="true" <?php echo set_selected($_POST, "PetsAllowed", "true", $succes_class); ?>>Ja</option><option value="false" <?php echo set_selected($_POST, "PetsAllowed", "false", $succes_class); ?>>Nee</option></select>
        <div class="pets"><?php echo display_error($error, "MaxWeightPet"); ?><label>Max. gewicht huisdier</label><input type="text" name="MaxWeightPet" <?php echo add_value($_POST, "MaxWeightPet", $succes_class); ?> />
        <?php echo display_error($error, "sizeLenghtPet"); ?><label>Lengte huisdier</label><input type="text" name="sizeLenghtPet" <?php echo add_value($_POST, "sizeLenghtPet", $succes_class); ?> />
        <?php echo display_error($error, "sizeHeightPet"); ?><label>Hoogte huisdier</label><input type="text" name="sizeHeightPet" <?php echo add_value($_POST, "sizeHeightPet", $succes_class); ?> />
        <?php echo display_error($error, "sizeWidthPet"); ?><label>Breedte huisdier</label><input type="text" name="sizeWidthPet" <?php echo add_value($_POST, "sizeWidthPet", $succes_class); ?> />
        <?php echo display_error($error, "sizeTotalPet"); ?><label>Grootte huisdier</label><input type="text" name="sizeTotalPet" <?php echo add_value($_POST, "sizeTotalPet", $succes_class); ?> /></div><br />
        
        <!--Waardeaangifte-->
        <label class="title">Waardeaangifte</label><br />
        <?php echo display_error($error, "DeclarationOfValue"); ?><label>Waardeaangifte</label><select class="input" id="DOV_select" name="DeclarationOfValue"><option></option><option value="true" <?php echo set_selected($_POST, "DeclarationOfValue", "true", $succes_class); ?>>Ja</option><option value="false" <?php echo set_selected($_POST, "DeclarationOfValue", "false", $succes_class); ?>>Nee</option></select>
        <div class="DeclarationOfValue"><?php echo display_error($error, "MaxDeclarationOfValue"); ?><label>Max. waardeaangifte</label><input type="text" name="MaxDeclarationOfValue" <?php echo add_value($_POST, "MaxDeclarationOfValue", $succes_class); ?> /></div><br />
        
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
?>

<?php
if (isset($_GET["action"]) && $_GET["action"] == "") {
?>

<!--Edit-->



<?php
}
?>
<?php
require_once ("onderkant.php");
?>