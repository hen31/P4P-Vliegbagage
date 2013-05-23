<?php
//Alle data classes includen
require_once ("../data/includeAll.php");
$titel = "Trajecten";
require_once ("bovenkant.php");
?>
<!-- Hier alles neerzetten-->

<h1>Traject toevoegen</h1>
<form action="trajecten.php" method="post">
  <div class="ui-widget">
    <label for="beginPunt">Beginpunt: </label>
    <input name="beginPunt" id="beginPunt" />
    <label for="eindPunt">Eindpunt: </label>
    <input name="eindPunt"  id="eindPunt"  />
    <input id="submit" type="submit" value="Toevoegen" />
    <input type="hidden" name="checkPosted" value="yes" />
  </div>
</form>
<br />
<h1>Aanwezige trajecten</h1>
<br />
<table width="100%" border="0">
<?php

trajecten::GetAllTrajecten();

if (!empty($_SERVER['QUERY_STRING'])) {
    if (isset($_GET['remove'])) {
        $remove = $_GET['remove'];

        trajecten::RemoveItem($remove);
    }
}

$amount = trajecten::GetTrajectAmount();

if ($amount != 0) {
?>

<td>Beginpunt</td>
<td>Eindpunt</td>
<td>Actie</td>
<?php
for ($i = 0; $i < count($amount - 1); $i++)
{
    $result = trajecten::GetAllTrajecten();
?>
<tr>
<td><?php echo ($result[$i]['airport_start_id']->AirportName); ?></td>
<td><?php echo ($result[$i]['airport_stop_id']->AirportName); ?></td>
<td><a href="trajecten.php?remove=<?php echo $result[$i]['traject_id'] ?>">Verwijder</a></td>
</tr>
<?php
}
} else {
    echo ("Er zijn geen trajecten aanwezig.");
}
?>
</table>

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
for ($i = 0; $i < count($airports); $i++) {
    if ($i == count($airports) - 1) {

        echo '"' . $airports[$i]->AirportName . '"';
    } else {
        echo '"' . $airports[$i]->AirportName . '"' . ",";
    }
} ?>
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
<?php
if ((isset($_POST["checkPosted"]))) {
    if (($_POST["beginPunt"] == $_POST["eindPunt"])) {
        echo '<script type="text/javascript"> window.alert("Beginpunt en eindpunt mogen niet gelijk zijn.")</script>';
    }
    if (!($_POST["beginPunt"] == $_POST["eindPunt"])) {
        if ((!$_POST["beginPunt"] || !$_POST["eindPunt"])) {
            echo '<script type="text/javascript"> window.alert("Beginpunt en eindpunt mogen niet leeg zijn.")</script>';
        } else {
            try {
                trajecten::AddItem($_POST["beginPunt"], $_POST["eindPunt"]);
                echo '<script type="text/javascript"> window.alert("Traject is met success toegevoegd.")</script>';
            }
            catch (exception $e) {
                echo '<script type="text/javascript"> window.alert("Er ging iets mis met het toevoegen van het traject. Probeer het opnieuw altublieft.")</script>';
            }
        }
    }
}
?>