<?php
//Alle data classes includen
require_once("../data/includeAll.php");
$titel = "Vliegmaatschappijen";
require_once("bovenkant.php");
?>

<div id="menu">
    <ul>
        <li>
            <a<?php echo (isset($_GET["action"]) && $_GET["action"] == "add" ? ' class="active" ' : "")?> href="airline.php?action=add">Toevoegen</a>
        </li>
        <li>
            <a<?php echo (isset($_GET["action"]) && $_GET["action"] == "edit" ? ' class="active" ' : "")?> href="airline.php?action=edit">Beheren</a>
        </li>
    </ul>
</div>
<div style="clear: both;"></div>


<?php
require_once("onderkant.php");
?>