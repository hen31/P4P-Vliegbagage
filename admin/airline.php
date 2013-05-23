<?php
//Alle data classes includen
require_once("../data/includeAll.php");
$titel = "Vliegmaatschappijen";
require_once("bovenkant.php");
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
<h1 style="margin-left: 20px;">Vliegmaatschappij toevoegen</h1>

<form action="airline.php" method="post" id="form">

    <div><label>Naam:</label><input type="text" name="naam" /></div>
    <div><label>Logo:</label><input type="url" name="logo" /></div>
    <div><label>Iata code:</label><input type="text" name="iata" /></div>
    <input type="submit" value="Opslaan" />

</form>

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