<?php
//Alle data classes includen
require_once ("../data/includeAll.php");
$titel = "SpecialluggageAirline";
require_once ("bovenkant.php");
?>
<!-- Hier alles neerzetten-->

<h1>1. Slecteer vliegmaatschappij</h1>

<form action="trajectenAirline.php" method="get">
  <label for="AirlineName">Vliegmaatschapij:</label>
<input name="AirlineName" id="AirlineName" value="<?php

if (isset($_GET["AirlineName"]) && !isset($added)) {
    echo htmlspecialchars($_GET["AirlineName"]);
} ?>"  />
<input type="submit" value="Selecteer" />
</form>

<br />

<h1>2. Selecteer speciale bagage</h1>

<form action="trajectenAirline.php" method="get">
<select id="CurrentAirlines" name="CurrentAirlines" size="6" style="width:150px;" >
<?php

if (isset($traject) && isset($airlinesList)) {
    foreach ($airlinesList as $air) {
        echo '<option value="' . $air->airline_id . '">' . htmlspecialchars($air->name) .
            '</option>';
    }
}

?>
</select>
<input type="submit" value="Verwijderen" /> 
<input type="hidden" value="Verwijderen" id="actie" />
</form>

<form
<?php
require_once ("onderkant.php");
?>