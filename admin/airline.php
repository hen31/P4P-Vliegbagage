<?php
echo "Pagina begin: (" .microtime() .")";
//Alle data classes includen
require_once("../data/includeAll.php");
$titel = "Vliegmaatschappijen";
require_once("bovenkant.php");

if($_SERVER["REQUEST_METHOD"] == "POST"){
    print_r($_POST);
}

?>

<div id="menu">
    <ul>
        <li>
            <a<?php echo (isset($_GET["action"]) && $_GET["action"] == "add" ? ' class="active"' : "")?> href="airline.php?action=add">Toevoegen</a>
        </li>
        <li>
            <a<?php echo (isset($_GET["action"]) && $_GET["action"] == "edit" ? ' class="active"' : "")?> href="airline.php?action=edit">Beheren</a>
        </li>
    </ul>
</div>
<div style="clear: both;"></div><br />

<?php
if(isset($_GET["action"]) && $_GET["action"] == "add"){
?>
<script src="../js/jquery.js"></script>
  <script type="text/javascript">
  
  $(function() {
    var availableTags = [
    <?php
     $airlines = airline::get_airlines(); //alle airlines ophalen
     for ($i = 0; $i < count($airlines); $i++) {
     if($i==count($airlines)-1)
        {
    
            echo '"'.  $airlines[$i]->name.'"';
        }
        else
        {      
         echo '"'.$airlines[$i]->name.'"'.",";
        }
      }?>
    ];
    $( "#airline_name" ).autocomplete({
      source: availableTags
    });
  });
  </script>

<!--Add-->
<div id="left">
    <h1 style="margin-left: 20px;">Vliegmaatschappij toevoegen</h1><br />
    
    <form action="airline.php" method="post" class="form">
    
        <label title="Naam van de vliegmaatschappij">Naam:</label><input id="airline_name" name="naam" />
        <label title="Link naar een logo voor de vliegmaatschappij">Logo:</label><input type="url" name="logo" />
        <label title="Iata code van de vliegmaatschappij">Iata code:</label><input type="text" name="iata" />
        <label title="Kosten in euro's die extra worden gerekend per extra gram gewicht">Kosten per extra gram:</label><input type="text" name="OverweightChargeG" />
        <label title="Kosten die extra worden gerekend bij overgewicht van een koffer">Kosten overgewicht koffer:</label><input type="text" name="OverweightChargeBag" />
        <label title="Kosten die worden gerekend per extra koffer">Kosten per extra koffer:</label><input type="text" name="ChargeExtraBag" />
        <label title="Kosten die worden gerekend als een koffer te groot is">Kosten te grote koffer:</label><input type="text" name="OversizeCharge" />
        
        
        <label>&nbsp;</label><input type="submit" value="Opslaan" />
    
    </form>
</div>
<div id="right">
    <h1 style="margin-right: 20px;">Class toevoegen aan vliegmaatschappij</h1><br />
    
    <form action="airline.php" method="post" class="form">
        <label>Vliegmaatschappij:</label><input type="text" name="airline" />
        <label>Class:</label><select class="input" name="classnumber">
                                <option></option>
                                <option value="0">Economy</option>
                                <option value="1">Eerste klas</option>
                                <option value="2">Business klas</option>
                            </select>
        <label>Stukken handbagage:</label><input type="text" name="pcsHL" />
        <label>Max. gewicht handbagage:</label><input type="text" name="MaxWeightHL" />
        <label>Lengte handbagage:</label><input type="text" name="sizeLenghtHL" />
        <label>Hoogte handbagage:</label><input type="text" name="sizeHeightHL" />
        <label>Breedte handbagage:</label><input type="text" name="SizeWidthHL" />
        <label>Grootte handbagage:</label><input type="text" name="sizeTotalHL" />
        <label>Laptop toegestaan:</label><select class="input" name="LaptopAllowedHL"><option></option><option value="true">Ja</option><option value="false">Nee</option></select>
        <label>Kinderwagen toegestaan:</label><select class="input" name="strollerAllowedHL"><option></option><option value="true">Ja</option><option value="false">Nee</option></select>
        <label>Stukken handbagage kind:</label><input type="text" name="pcsInfantHL" />
        <label>Stukken bagage kind:</label><input type="text" name="pcsLuggageInfant" />
        <label>Max. gewcht bagage kind:</label><input type="text" name="pcsLuggageInfantMaxWeight" />
        <label>Stukken bagage:</label><input type="text" name="pcsLuggage" />
        <label>Max. gewicht bagage:</label><input type="text" name="maxWeightLuggage" />
        <label>Loyalty programma (LP):</label><select class="input" name="LoyaltyProgramme"><option></option><option value="true">Ja</option><option value="false">Nee</option></select>
        <label>Extra stukken bagage LP:</label><input type="text" name="LPextraPcsLuggage" />
        <label>Extra gewicht bagage LP:</label><input type="text" name="LPextraWeightLuggage" />
        <label>Abs. max. gewicht bagage:</label><input type="text" name="AbsoluteMaxPerItem" />
        <br />
        <label>Lengte per item:</label><input type="text" name="sizeLenghtPerItem" />
        <label>Hoogte per item:</label><input type="text" name="sizeHeightPerItem" />
        <label>Breedte per item:</label><input type="text" name="sizeWidthPerItem" />
        <label>Grootte per item:</label><input type="text" name="sizeTotalPerItem" />
        <br />
        <label>Pooling:</label><select name="Pooling" class="input"><option></option><option value="true">Ja</option><option value="false">Nee</option></select>
        <label>Gratis rolstoel:</label><select name="FreeWheelChair" class="input"><option></option><option value="true">Ja</option><option value="false">Nee</option></select>
        <label>Gratis blindengeleidehond:</label><select name="FreeServiceDog" class="input"><option></option><option value="true">Ja</option><option value="false">Nee</option></select>
        <label>Huisdieren toegestaan:</label><select name="PetsAllowed" class="input"><option></option><option value="true">Ja</option><option value="false">Nee</option></select>
        <label>Max. gewicht huisdier:</label><input type="text" name="MaxWeightPet" />
        <label>Lengte huisdier:</label><input type="text" name="sizeLenghtPet" />
        <label>Hoogte huisdier:</label><input type="text" name="sizeHeightPet" />
        <label>Breedte huisdier:</label><input type="text" name="sizeWidthPet" />
        <label>Grootte huisdier:</label><input type="text" name="sizeTotalPet" />
        <label>Declaration of value</label><input type="text" name="DeclarationOfValue" />
        <label>Max. declartion of value</label><input type="text" name="MaxDeclarationOfValue" />
    
        <label>&nbsp;</label><input type="submit" value="Opslaan" />
    </form>
</div>
<div style="clear: both;"></div>
<?php
}
?>

<?php
if(isset($_GET["action"]) && $_GET["action"] == ""){
?>

<!--Edit-->



<?php
}
?>
<?php
require_once("onderkant.php");
echo "Pagina eind: (" .microtime() .")";
?>