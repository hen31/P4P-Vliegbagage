<?php
//Alle data classes includen
require_once ("../data/includeAll.php");
$titel = "Vliegmaatschappijen";
require_once ("bovenkant.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["act"]) && $_POST["act"] == "airline") {
    $postvelden = array("naam", "iata", "OverweightChargeG", "OverweightChargeBag", "ChargeExtraBag", "OversizeCharge");
        
        foreach($postvelden as $postveld){
            if(empty($_POST[$postveld]) && $_POST[$postveld] != "0"){
                $error[$postveld] = '<span style="color:red;">Niets ingevuld.</span>';
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
        
        if(!isset($error)){
            airline::add_airline_without_class($_POST["naam"], $name, $_POST["OverweightChargeG"], $_POST["OverweightChargeBag"], $_POST["ChargeExtraBag"], $_POST["OversizeCharge"], $_POST["iata"]);
            $succes_airline = true;
        }
}
elseif($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["act"]) && $_POST["act"] == "class"){
    
}

?>

<div id="menu">
    <ul>
        <li>
            <a<?php echo (isset($_GET["action"]) && $_GET["action"] == "add" ?
' class="active"' : "") ?> href="airline.php?action=add">Toevoegen</a>
        </li>
        <li>
            <a<?php echo (isset($_GET["action"]) && $_GET["action"] == "edit" ?
' class="active"' : "") ?> href="airline.php?action=edit">Beheren</a>
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
    <h2>Vliegmaatschappij <?php echo htmlspecialchars($_POST["naam"]); ?> succesvol toegevoegd.</h2>
    <?php } ?>
    <form action="airline.php?action=add" method="post" class="form" enctype="multipart/form-data">
        <input type="hidden" name="act" value="airline" />
        <?php echo (isset($error["naam"]) ? $error["naam"] : ""); ?><label title="Naam van de vliegmaatschappij">Naam:</label><input type="text" name="naam" value="<?php echo (isset($_POST["name"]) ? $_POST["name"] : "") ?>" />
        <?php echo (isset($error["logo"]) ? $error["logo"] : ""); ?><label title="Logo van de vligmaatschappij">Logo:</label><input type="file" name="logo" />
        <?php echo (isset($error["iata"]) ? $error["iata"] : ""); ?><label title="Iata code van de vliegmaatschappij">Iata code:</label><input type="text" name="iata" value="<?php echo (isset($_POST["iata"]) ? $_POST["iata"] : "") ?>" />
        <?php echo (isset($error["OverweightChargeG"]) ? $error["OverweightChargeG"] : ""); ?><label title="Kosten in euro's die extra worden gerekend per extra kilogram gewicht">Kosten per extra kilogram:</label><input type="text" name="OverweightChargeG" value="<?php echo (isset($_POST["OverweightChargeG"]) ? $_POST["OverweightChargeG"] : "") ?>" />
        <?php echo (isset($error["OverweightChargeBag"]) ? $error["OverweightChargeBag"] : ""); ?><label title="Kosten die extra worden gerekend bij overgewicht van een koffer">Kosten overgewicht koffer:</label><input type="text" name="OverweightChargeBag" value="<?php echo (isset($_POST["OverweightChargeBag"]) ? $_POST["OverweightChargeBag"] : "") ?>" /> 
        <?php echo (isset($error["ChargeExtraBag"]) ? $error["ChargeExtraBag"] : ""); ?><label title="Kosten die worden gerekend per extra koffer">Kosten per extra koffer:</label><input type="text" name="ChargeExtraBag" value="<?php echo (isset($_POST["ChargeExtraBag"]) ? $_POST["ChargeExtraBag"] : "") ?>" />
        <?php echo (isset($error["OversizeCharge"]) ? $error["OversizeCharge"] : ""); ?><label title="Kosten die worden gerekend als een koffer te groot is">Kosten te grote koffer:</label><input type="text" name="OversizeCharge" value="<?php echo (isset($_POST["OversizeCharge"]) ? $_POST["OversizeCharge"] : "") ?>" /> 
        
        
        <label>&nbsp;</label><input type="submit" value="Opslaan" />
    
    </form>
</div>
<div id="right">
    <h1 style="margin-right: 20px;">Class toevoegen aan vliegmaatschappij</h1><br />
    
    <form action="airline.php?action=add" method="post" class="form">
        <input type="hidden" name="act" value="class" />
        <label>Vliegmaatschappij:</label><input type="text" id="airline_name" name="airline" />
        <label>Class:</label><select class="input" name="classnumber">
                                <option></option>
                                <option value="0">Economy</option>
                                <option value="1">Eerste klas</option>
                                <option value="2">Business klas</option>
                            </select><br />
                            
        
        <!--Ruimbagage-->
        <label class="title">Ruimbagage</label><br />
        <label>Stukken of gewicht</label><select id="ruimbagage" class="input" name="pcs_weight"><option></option><option value="pcs">Stukken</option><option value="weight">Gewicht</option></select>
        <div class="pcs"><label>Stukken bagage</label><input type="text" name="pcsLuggage" /></div>
        <div class="weight"><label>Max. gewicht bagage</label><input type="text" name="maxWeightLuggage" /></div>
        <div class="pcs"><label>Stukken bagage kind</label><input type="text" name="pcsLuggageInfant" /></div>
        <div class="weight"><label>Max. gewicht bagage kind</label><input type="text" name="pcsLuggageInfantMaxWeight" /></div><br />
        
        <!--Handbagage-->
        <label class="title">Handbagage</label><br />
        <label>Stukken of gewicht</label><select id="handbagage" class="input" name="pcs_weightHL"><option></option><option value="pcs">Stukken</option><option value="weight">Gewicht</option></select>
        <div class="pcsHL"><label>Stukken handbagage</label><input type="text" name="pcsHL" /></div>
        <div class="pcsHL"><label>Stukken handbagage kind</label><input type="text" name="pcsInfantHL" /></div>
        <div class="weightHL"><label>Max. gewicht handbagage</label><input type="text" name="MaxWeightHL" /></div>
        <label>Lengte handbagage</label><input type="text" name="sizeLenghtHL" />
        <label>Hoogte handbagage</label><input type="text" name="sizeHeightHL" />
        <label>Breedte handbagage</label><input type="text" name="SizeWidthHL" />
        <label>Grootte handbagage</label><input type="text" name="sizeTotalHL" /><br />
        
        <!--Items-->
        <label class="title">Items</label><br />
        <label>Lengte per item</label><input type="text" name="sizeLenghtPerItem" />
        <label>Hoogte per item</label><input type="text" name="sizeHeightPerItem" />
        <label>Breedte per item</label><input type="text" name="sizeWidthPerItem" />
        <label>Grootte per item</label><input type="text" name="sizeTotalPerItem" /><br />
        
        <!--LP-->
        <label class="title">Loyalty programma (LP)</label><br />
        <label>Loyalty programma</label><select id="LP_select" class="input" name="LoyaltyProgramme"><option></option><option value="true">Ja</option><option value="false">Nee</option></select>
        <div class="LP"><label>Extra stukken bagage LP</label><input type="text" name="LPextraPcsLuggage" />
        <label>Extra gewicht bagage LP</label><input type="text" name="LPextraWeightLuggage" />
        <label>Abs. max. gewicht bagage</label><input type="text" name="AbsoluteMaxPerItem" /></div><br />
        
        <!--Huisdieren-->
        <label class="title">Huisdieren</label><br />
        <label>Huisdieren toegestaan</label><select id="pets" name="PetsAllowed" class="input"><option></option><option value="true">Ja</option><option value="false">Nee</option></select>
        <div class="pets"><label>Max. gewicht huisdier</label><input type="text" name="MaxWeightPet" />
        <label>Lengte huisdier</label><input type="text" name="sizeLenghtPet" />
        <label>Hoogte huisdier</label><input type="text" name="sizeHeightPet" />
        <label>Breedte huisdier</label><input type="text" name="sizeWidthPet" />
        <label>Grootte huisdier</label><input type="text" name="sizeTotalPet" /></div><br />
        
        <!--Waardeaangifte-->
        <label class="title">Waardeaangifte</label><br />
        <label>Waardeaangifte</label><input type="text" name="DeclarationOfValue" />
        <label>Max. waardeaangifte</label><input type="text" name="MaxDeclarationOfValue" /><br />
        
        <!--Ja/Nee-->
        <br />
        <label>Laptop toegestaan</label><select class="input" name="LaptopAllowedHL"><option></option><option value="true">Ja</option><option value="false">Nee</option></select>
        <label>Kinderwagen toegestaan</label><select class="input" name="strollerAllowedHL"><option></option><option value="true">Ja</option><option value="false">Nee</option></select>
        <label>Pooling</label><select name="Pooling" class="input"><option></option><option value="true">Ja</option><option value="false">Nee</option></select>
        <label>Gratis rolstoel</label><select name="FreeWheelChair" class="input"><option></option><option value="true">Ja</option><option value="false">Nee</option></select>
        <label>Gratis blindengeleidehond</label><select name="FreeServiceDog" class="input"><option></option><option value="true">Ja</option><option value="false">Nee</option></select><br />
    
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