<?php

//Alle data classes includen
require_once ("../data/includeAll.php");
$titel = "Trajecten koppelen";
require_once ("bovenkant.php");
if (isset($_GET["beginPunt"]) && isset($_GET["eindPunt"]))
{
    $beginAirport = airports::GetAirportByName($_GET["beginPunt"]);
    $endAirport = airports::GetAirportByName($_GET["eindPunt"]);
    // kijken of de vliegvelden bestaan
    if ($beginAirport != null && $endAirport != null)
    {
        //kijken of het niet de zelfde vliegvelden zijn.
        if ($beginAirport->AirportID != $endAirport->AirportID)
        {
            
        }
    }

}

?>


<h1>Traject Koppelen</h1>
<p>Selecteer het traject</p>
<form action="trajectenAirline.php" method="get">
  <div class="ui-widget">
    <label for="beginPunt">Beginpunt: </label>
    <input name="beginPunt" id="beginPunt" />
    <label for="eindPunt">Eindpunt: </label>
    <input name="eindPunt" id="eindPunt"  />
    <input id="submit" type="submit" value="Toevoegen" />
  </div>
</form>
<select id="CurrentAirlines" multiple="true">
<?php

?>
</select>
<br />

<script src="../js/jquery-1.9.0.min.js"></script>
<script src="../js/jquery-ui.js"></script>
<script src="../js/grid.locale-nl.js" type="text/javascript"></script>
<script src="../js/jquery.jqGrid.min.js" type="text/javascript"></script>
<script src="../js/javascript.js"></script>
<script type="text/javascript">
  $(function() {
    var availableTags = [
    <?php

$airports = airports::GetAirports();
for ($i = 0; $i < count($airports); $i++)
{
    if ($i == count($airports) - 1)
    {

        echo '"' . $airports[$i]->AirportName . '"';
    } else
    {
        echo '"' . $airports[$i]->AirportName . '"' . ",";
    }
}

?>
    ];
    $( "#beginPunt" ).autocomplete({
      source: availableTags
    });
      $( "#eindPunt" ).autocomplete({
      source: availableTags
    });
  });
  </script>
  
<?php

require_once ("onderkant.php");

?>
