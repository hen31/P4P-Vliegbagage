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
        return $error[$indexname];
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
    $postvelden = array("naam", "iata", "OverweightChargeG", "OverweightChargeBag", "ChargeExtraBag", "OversizeCharge");
        
        foreach($postvelden as $postveld){
            if(empty($_POST[$postveld]) && $_POST[$postveld] != "0"){
                $error[$postveld] = '<span style="color:red;">Veld niet ingevuld.</span>';
            }
            elseif($postveld == "naam"){
                if(airline::airline_name_exists($_POST[$postveld])){
                    $error["naam"] = '<span style="color:red;">Vliegmaatschappij bestaat al.</span>';
                }
            }
            elseif($postveld == "iata"){
                if(!validator::stringLimit(1, 3, $_POST[$postveld])){
                    $error["iata"] = '<span style="color:red;">Een iata code mag maximaal 3 karakters bevatten.</span>';
                }
            }
            else{
                if(!validator::isInt($_POST[$postveld])){
                    $error[$postveld] = '<span style="color:red;">Onjuiste waarde ingevuld.</span>';
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
                $error["logo"] = '<span style="color:red;">Alleen afbeeldingen kunnen worden toegevoegd.</span>';
            }
        }
        else{
            $name = "";
        }
        
        if(!isset($error)){
            airline::add_airline_without_class($_POST["naam"], $name, $_POST["OverweightChargeG"], $_POST["OverweightChargeBag"], $_POST["ChargeExtraBag"], $_POST["OversizeCharge"], $_POST["iata"]);
            $succes_airline = true;
        }
}
elseif($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["act"]) && $_POST["act"] == "class"){
    $required = array("airline_name", "classnumber", "pcs_weight","pcs_weightHL","sizeLenghtHL","sizeHeightHL","SizeWidthHL","sizeTotalHL","sizeLenghtPerItem","sizeHeightPerItem",
                        "sizeWidthPerItem","sizeTotalPerItem","LoyaltyProgramme","PetsAllowed","DeclarationOfValue","MaxDeclarationOfValue","LaptopAllowedHL",
                        "strollerAllowedHL","Pooling","FreeWheelChair","FreeServiceDog");
    foreach($required as $requiredField){
        if(!isset($_POST[$requiredField]) || empty($_POST[$requiredField]) && $_POST[$requiredField] != "0"){
            $error[$requiredField] = '<span style="color:red;">Veld niet ingevuld.</span>';
        }
    }
                       
    if(isset($_POST["ailine_name"]) && airline::airline_name_exists($_POST["airline_name"])){
        
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
    } ?>
    ];
    $( "#airline_name" ).autocomplete({
      source: availableTags
    });
  });
  </script>

<!--Add-->
<div id="left">
    <h1 style="margin-left: 20px;">Vliegmaatschappij toevoegen</h1><br />
    
    <?php if(isset($succes_airline) && $succes_airline){ ?>
    <strong>Vliegmaatschappij <?php echo htmlspecialchars($_POST["naam"]); ?> succesvol toegevoegd.</strong>
    <?php } ?>
    <form action="airline.php?action=add" method="post" class="form" enctype="multipart/form-data">
        <input type="hidden" name="act" value="airline" />
        <?php echo display_error($error, "naam"); ?><label title="Naam van de vliegmaatschappij">Naam:</label><input type="text" name="naam" <?php echo add_value($_POST, "naam", $succes_airline); ?> />
        <?php echo display_error($error, "logo"); ?><label title="Logo van de vligmaatschappij">Logo:</label><input type="file" name="logo" />
        <?php echo display_error($error, "iata"); ?><label title="Iata code van de vliegmaatschappij">Iata code:</label><input type="text" name="iata" <?php echo add_value($_POST, "iata", $succes_airline); ?> />
        <?php echo display_error($error, "OverweightChargeG"); ?><label title="Kosten in euro's die extra worden gerekend per extra kilogram gewicht">Kosten per extra kilogram:</label><input type="text" name="OverweightChargeG" <?php echo add_value($_POST, "OverweightChargeG", $succes_airline) ?> />
        <?php echo display_error($error, "OverweightChargeBag"); ?><label title="Kosten die extra worden gerekend bij overgewicht van een koffer">Kosten overgewicht koffer:</label><input type="text" name="OverweightChargeBag" <?php echo add_value($_POST, "OverweightChargeBag", $succes_airline) ?> /> 
        <?php echo display_error($error, "ChargeExtraBag"); ?><label title="Kosten die worden gerekend per extra koffer">Kosten per extra koffer:</label><input type="text" name="ChargeExtraBag" <?php echo add_value($_POST, "ChargeExtraBag", $succes_airline) ?> />
        <?php echo display_error($error, "OversizeCharge"); ?><label title="Kosten die worden gerekend als een koffer te groot is">Kosten te grote koffer:</label><input type="text" name="OversizeCharge" <?php echo add_value($_POST, "OversizeCharge", $succes_airline) ?> /> 
        
        
        <label>&nbsp;</label><input type="submit" value="Opslaan" />
    
    </form>
</div>
<div id="right">
    <h1 style="margin-right: 20px;">Class toevoegen aan vliegmaatschappij</h1><br />
    
    <form action="airline.php?action=add" method="post" class="form">
        <input type="hidden" name="act" value="class" />
        <label>Vliegmaatschappij:</label><input type="text" id="airline_name" name="airline" <?php echo add_value($_POST, "airline", $succes_class); ?> /> 
        <?php echo display_error($error, "classnumber"); ?><label>Class:</label><select class="input" name="classnumber">
                                <option></option>
                                <option value="0" <?php echo set_selected($_POST, "classnumber", "0", $succes_class); ?>>Economy</option>
                                <option value="1" <?php echo set_selected($_POST, "classnumber", "1", $succes_class); ?>>Eerste klas</option>
                                <option value="2" <?php echo set_selected($_POST, "classnumber", "2", $succes_class); ?>>Business klas</option>
                            </select><br />
                            
        
        <!--Ruimbagage-->
        <label class="title">Ruimbagage</label><br />
        <?php echo display_error($error, "pcs_weight"); ?><label>Stukken of gewicht</label><select id="ruimbagage" class="input" name="pcs_weight"><option></option><option value="pcs" <?php echo set_selected($_POST, "pcs_weight", "pcs", $succes_class); ?>>Stukken</option><option value="weight" <?php echo set_selected($_POST, "pcs_weight", "weight", $succes_class); ?>>Gewicht</option></select>
        <?php echo display_error($error, "pcsLuggage"); ?><div class="pcs"><label>Stukken bagage</label><input type="text" name="pcsLuggage" <?php echo add_value($_POST, "pcsLuggage", $succes_class); ?> /></div>
        <?php echo display_error($error, "pcsLuggageInfant"); ?><div class="pcs"><label>Stukken bagage kind</label><input type="text" name="pcsLuggageInfant" <?php echo add_value($_POST, "pcsLuggageInfant", $succes_class); ?> /></div>
        <?php echo display_error($error, "maxWeightLuggage"); ?><div class="weight"><label>Max. gewicht bagage</label><input type="text" name="maxWeightLuggage" <?php echo add_value($_POST, "maxWeightLuggage", $succes_class); ?> /></div>
        <?php echo display_error($error, "pcsLuggageInfantMaxWeight"); ?><div class="weight"><label>Max. gewicht bagage kind</label><input type="text" name="pcsLuggageInfantMaxWeight" <?php echo add_value($_POST, "pcsLuggageInfantMaxWeight", $succes_class); ?> /></div><br />
        
        <!--Handbagage-->
        <label class="title">Handbagage</label><br />
        <?php echo display_error($error, "pcs_weightHL"); ?><label>Stukken of gewicht</label><select id="handbagage" class="input" name="pcs_weightHL"><option></option><option value="pcs" <?php echo set_selected($_POST, "pcs_weightHL", "pcs", $succes_class) ?>>Stukken</option><option value="weight" <?php echo set_selected($_POST, "pcs_weightHL", "weight", $succes_class) ?>>Gewicht</option></select>
        <?php echo display_error($error, "pcsHL"); ?><div class="pcsHL"><label>Stukken handbagage</label><input type="text" name="pcsHL" <?php echo add_value($_POST, "pcsHL", $succes_class); ?> /></div>
        <?php echo display_error($error, "pcsInfantHL"); ?><div class="pcsHL"><label>Stukken handbagage kind</label><input type="text" name="pcsInfantHL" <?php echo add_value($_POST, "pcsInfantHL", $succes_class); ?> /></div>
        <?php echo display_error($error, "MaxWeightHL"); ?><div class="weightHL"><label>Max. gewicht handbagage</label><input type="text" name="MaxWeightHL" <?php echo add_value($_POST, "MaxWeightHL", $succes_class); ?> /></div>
        <?php echo display_error($error, "sizeLenghtHL"); ?><label>Lengte handbagage</label><input type="text" name="sizeLenghtHL" <?php echo add_value($_POST, "sizeLenghtHL", $succes_class); ?> />
        <?php echo display_error($error, "sizeHeightHL"); ?><label>Hoogte handbagage</label><input type="text" name="sizeHeightHL" <?php echo add_value($_POST, "sizeHeightHL", $succes_class); ?> />
        <?php echo display_error($error, "SizeWidthHL"); ?><label>Breedte handbagage</label><input type="text" name="SizeWidthHL" <?php echo add_value($_POST, "SizeWidthHL", $succes_class); ?> />
        <?php echo display_error($error, "sizeTotalHL"); ?><label>Grootte handbagage</label><input type="text" name="sizeTotalHL" <?php echo add_value($_POST, "sizeTotalHL", $succes_class); ?> /><br />
        
        <!--Items-->
        <label class="title">Items</label><br />
        <?php echo display_error($error, "sizeLenghtPerItem"); ?><label>Lengte per item</label><input type="text" name="sizeLenghtPerItem"  <?php echo add_value($_POST, "sizeLenghtPerItem", $succes_class); ?> />
        <?php echo display_error($error, "sizeHeightPerItem"); ?><label>Hoogte per item</label><input type="text" name="sizeHeightPerItem" <?php echo add_value($_POST, "sizeHeightPerItem", $succes_class); ?> />
        <?php echo display_error($error, "sizeWidthPerItem"); ?><label>Breedte per item</label><input type="text" name="sizeWidthPerItem" <?php echo add_value($_POST, "sizeWidthPerItem", $succes_class); ?> />
        <?php echo display_error($error, "sizeTotalPerItem"); ?><label>Grootte per item</label><input type="text" name="sizeTotalPerItem" <?php echo add_value($_POST, "sizeTotalPerItem", $succes_class); ?> /><br />
        
        <!--LP-->
        <label class="title">Loyalty programma (LP)</label><br />
        <?php echo display_error($error, "LoyaltyProgramme"); ?><label>Loyalty programma</label><select id="LP_select" class="input" name="LoyaltyProgramme"><option></option><option value="true" <?php echo set_selected($_POST, "LoyaltyProgramme", "true", $succes_class) ?>>Ja</option><option value="false" <?php echo set_selected($_POST, "LoyaltyProgramme", "false", $succes_class) ?>>Nee</option></select>
        <?php echo display_error($error, "LPextraPcsLuggage"); ?><div class="LP"><label>Extra stukken bagage LP</label><input type="text" name="LPextraPcsLuggage" <?php echo add_value($_POST, "LPextraPcsLuggage", $succes_class); ?> />
        <?php echo display_error($error, "LPextraWeightLuggage"); ?><label>Extra gewicht bagage LP</label><input type="text" name="LPextraWeightLuggage" <?php echo add_value($_POST, "LPextraWeightLuggage", $succes_class); ?> />
        <?php echo display_error($error, "AbsoluteMaxPerItem"); ?><label>Abs. max. gewicht bagage</label><input type="text" name="AbsoluteMaxPerItem" <?php echo add_value($_POST, "AbsoluteMaxPerItem", $succes_class); ?> /></div><br />
        
        <!--Huisdieren-->
        <label class="title">Huisdieren</label><br />
        <?php echo display_error($error, "PetsAllowed"); ?><label>Huisdieren toegestaan</label><select id="pets" name="PetsAllowed" class="input"><option></option><option value="true" <?php echo set_selected($_POST, "PetsAllowed", "true", $succes_class); ?>>Ja</option><option value="false" <?php echo set_selected($_POST, "PetsAllowed", "false", $succes_class); ?>>Nee</option></select>
        <?php echo display_error($error, "MaxWeightPet"); ?><div class="pets"><label>Max. gewicht huisdier</label><input type="text" name="MaxWeightPet" <?php echo add_value($_POST, "MaxWeightPet", $succes_class); ?> />
        <?php echo display_error($error, "sizeLenghtPet"); ?><label>Lengte huisdier</label><input type="text" name="sizeLenghtPet" <?php echo add_value($_POST, "sizeLenghtPet", $succes_class); ?> />
        <?php echo display_error($error, "sizeHeightPet"); ?><label>Hoogte huisdier</label><input type="text" name="sizeHeightPet" <?php echo add_value($_POST, "sizeHeightPet", $succes_class); ?> />
        <?php echo display_error($error, "sizeWidthPet"); ?><label>Breedte huisdier</label><input type="text" name="sizeWidthPet" <?php echo add_value($_POST, "sizeWidthPet", $succes_class); ?> />
        <?php echo display_error($error, "sizeTotalPet"); ?><label>Grootte huisdier</label><input type="text" name="sizeTotalPet" <?php echo add_value($_POST, "sizeTotalPet", $succes_class); ?> /></div><br />
        
        <!--Waardeaangifte-->
        <label class="title">Waardeaangifte</label><br />
        <?php echo display_error($error, "DeclarationOfValue"); ?><label>Waardeaangifte</label><input type="text" name="DeclarationOfValue" <?php echo add_value($_POST, "DeclarationOfValue", $succes_class); ?> />
        <?php echo display_error($error, "MaxDeclarationOfValue"); ?><label>Max. waardeaangifte</label><input type="text" name="MaxDeclarationOfValue" <?php echo add_value($_POST, "MaxDeclarationOfValue", $succes_class); ?> /><br />
        
        <!--Ja/Nee-->
        <br />
        <?php echo display_error($error, "LaptopAllowedHL"); ?><label>Laptop toegestaan</label><select class="input" name="LaptopAllowedHL"><option></option><option value="true" <?php echo set_selected($_POST, "LaptopAllowedHL", "true", $succes_class) ?>>Ja</option><option value="false" <?php echo set_selected($_POST, "LaptopAllowedHL", "false", $succes_class) ?>>Nee</option></select>
        <?php echo display_error($error, "strollerAllowedHL"); ?><label>Kinderwagen toegestaan</label><select class="input" name="strollerAllowedHL"><option></option><option value="true" <?php echo set_selected($_POST, "strollerAllowedHL", "true", $succes_class) ?>>Ja</option><option value="false" <?php echo set_selected($_POST, "strollerAllowedHL", "false", $succes_class) ?>>Nee</option></select>
        <?php echo display_error($error, "Pooling"); ?><label>Pooling</label><select name="Pooling" class="input"><option></option><option value="true" <?php echo set_selected($_POST, "Pooling", "true", $succes_class) ?>>Ja</option><option value="false" <?php echo set_selected($_POST, "Pooling", "false", $succes_class) ?>>Nee</option></select>
        <?php echo display_error($error, "FreeWheelChair"); ?><label>Gratis rolstoel</label><select name="FreeWheelChair" class="input"><option></option><option value="true" <?php echo set_selected($_POST, "FreeWheelChair", "true", $succes_class) ?> >Ja</option><option value="false" <?php echo set_selected($_POST, "FreeWheelChair", "false", $succes_class) ?>>Nee</option></select>
        <?php echo display_error($error, "FreeServiceDog"); ?><label>Gratis blindengeleidehond</label><select name="FreeServiceDog" class="input"><option></option><option value="true" <?php echo set_selected($_POST, "FreeServiceDog", "true", $succes_class) ?> >Ja</option><option value="false" <?php echo set_selected($_POST, "FreeServiceDog", "false", $succes_class) ?> >Nee</option></select><br />
    
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