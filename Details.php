<?php
//Alle data classes includen
require_once ("data/includeAll.php");
require_once ("data/frontend.php");
$titel = "Details";

if(!isset($_GET["name"]))
{
    exit();
}
else
{
    $titel .= $_GET["name"];
}
$airline = airlines::airline_name_exists($_GET["name"]);
?>
<?php
?>