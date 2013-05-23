<?php
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

<!--Add-->
<div id="left">
    <h1 style="margin-left: 20px;">Vliegmaatschappij toevoegen</h1><br />
    
    <form action="airline.php" method="post" class="form">
    
        <label title="Naam van de vliegmaatschappij">Naam:</label><input type="text" name="naam" />
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
        <label>Test</label><input type="text" name="" />
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
?>