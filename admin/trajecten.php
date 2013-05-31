<?php
//Alle data classes includen
require_once ("../data/includeAll.php");
$titel = "Trajecten";

if (!empty($_SERVER['QUERY_STRING'])) {
    if (isset($_GET['remove'])) {
        $remove = $_GET['remove'];

        trajecten::RemoveTraject($remove);
        header("Location: trajecten.php");
        exit;
    }
}
if ((isset($_POST["checkPostedAdd"]))) {
    if (($_POST["beginPunt"] == $_POST["eindPunt"])) {
        $message = '<script type="text/javascript"> window.alert("Beginpunt en eindpunt mogen niet gelijk zijn.")</script>';
    }
    if (!($_POST["beginPunt"] == $_POST["eindPunt"])) {
        if ((!$_POST["beginPunt"] || !$_POST["eindPunt"])) {
            $message = '<script type="text/javascript"> window.alert("Beginpunt en eindpunt mogen niet leeg zijn.")</script>';
        } else {
            if (!airports::GetAirportByName($_POST["beginPunt"]) || !airports::
                GetAirportByName($_POST["eindPunt"])) {
                $message = '<script type="text/javascript"> window.alert("Het door u ingevoerde beginpunt of eindpunt bestaat niet. Probeer het opnieuw alstublieft.")</script>';
            } else {
                if (trajecten::CheckTrajectExist($_POST["beginPunt"], $_POST["eindPunt"])) {
                    $message = '<script type="text/javascript"> window.alert("Het door u ingevoerde traject bestaat al. Probeer het opnieuw alstublieft.")</script>';
                } else {
                    try {
                        trajecten::AddTraject($_POST["beginPunt"], $_POST["eindPunt"]);
                        $toegevoegd = true;
                        session_start();
                        $_SESSION["added"] = true;
                    }
                    catch (exception $e) {
                        $message = '<script type="text/javascript"> window.alert("Er ging iets mis met het toevoegen van het traject. Probeer het opnieuw altublieft.")</script>';
                    }
                }
            }
        }
    }
}
if (isset($toegevoegd) && $toegevoegd == true) {
    $toegevoegd = false;
    header("Location: trajecten.php");
    exit;
}
require_once ("bovenkant.php");

?>
<!-- Hier alles neerzetten-->

<script type="text/javascript">
	function expand(a) {
		var e = document.getElementById(a);
		if (!e) return true;
		if (e.style.display == "none") {
			e.style.display = "block"
		} else {
			e.style.display = "none"
		}
		return true;
	}
</script>

<h1>Traject toevoegen</h1>
<p>Via onderstaand formulier kunt u een nieuw traject toevoegen aan de database. Selecteer een begin- en eindpunt, en klik vervolgens op "Toevoegen".</p>
<form action="trajecten.php" method="post">
  <div class="ui-widget">
    <label for="beginPunt">Beginpunt: </label>
    <select name="beginPunt" id="beginPunt">
    <?php
$airports = airports::GetAirports();
for ($i = 0; $i < count($airports); $i++)
{
    if(isset($_GET['beginPunt']) && htmlspecialchars($_GET["beginPunt"]) == $airports[$i]->AirportName)
    {
         echo '<option selected="true" value="' . $airports[$i]->AirportName . '">'. $airports[$i]->AirportName . '('. $airports[$i]->AirportCity. ')'.'</option>';
        }
        else
        {
        echo '<option value="' . $airports[$i]->AirportName . '">'. $airports[$i]->AirportName . '('. $airports[$i]->AirportCity. ')'.'</option>';
}
}?></select>
    <label for="eindPunt">Eindpunt: </label>
    <select name="eindPunt" id="eindPunt" >
    <?php
$airports = airports::GetAirports();
for ($i = 0; $i < count($airports); $i++)
{
    if(isset($_GET['eindPunt']) && htmlspecialchars($_GET["eindPunt"]) == $airports[$i]->AirportName)
    {
         echo '<option selected="true" value="' . $airports[$i]->AirportName . '">'. $airports[$i]->AirportName . '('. $airports[$i]->AirportCity. ')'.'</option>';
        }
        else
        {
        echo '<option value="' . $airports[$i]->AirportName . '">'. $airports[$i]->AirportName . '('. $airports[$i]->AirportCity. ')'.'</option>';
}
}?></select>
    <input id="submit" type="submit" value="Toevoegen" />
    <input type="hidden" name="checkPostedAdd" value="yes" />
  </div>
</form>
<br />

<h1>Aanwezige trajecten</h1>
<p>In onderstaande tabel ziet u een overzicht van de trajecten die momenteel aanwezig zijn in de database. Indien gewenst kunt u filter instellen om specifieke trajecten weer te geven.</p>
<input style="float:left;" type="button" onclick="return expand('Filter')" value="Filterinstellingen"/>
<form style="float:left;" action="trajecten.php" method="post" >
  <div class="ui-widget">
    <input name="action" type="submit" value="Verwijder filter" />
    </div>
</form>
<br />
<div id="Filter" style="display:none">
<br />
<form action="trajecten.php" method="get" >
  <div class="ui-widget">
    <label for="filterBeginpunt">Beginpunt: </label>
    <select name="filterBeginpunt" id="filterBeginpunt">
<?php
$airports = airports::GetAirports();
for ($i = 0; $i < count($airports); $i++)
{
    if(isset($_GET['filterBeginpunt']) && htmlspecialchars($_GET["filterBeginpunt"]) == $airports[$i]->AirportName)
    {
         echo '<option selected="true" value="' . $airports[$i]->AirportName . '">'. $airports[$i]->AirportName . '('. $airports[$i]->AirportCity. ')'.'</option>';
        }
        else
        {
        echo '<option value="' . $airports[$i]->AirportName . '">'. $airports[$i]->AirportName . '('. $airports[$i]->AirportCity. ')'.'</option>';
}
}?>
</select>
    <label for="filterEindpunt">Eindpunt: </label>
    <select name="filterEindpunt" id="filterEindpunt" >
    <?php
$airports = airports::GetAirports();
for ($i = 0; $i < count($airports); $i++)
{
    if(isset($_GET['filterEindpunt']) && htmlspecialchars($_GET["filterEindpunt"]) == $airports[$i]->AirportName)
    {
         echo '<option selected="true" value="' . $airports[$i]->AirportName . '">'. $airports[$i]->AirportName . '('. $airports[$i]->AirportCity. ')'.'</option>';
        }
        else
        {
        echo '<option value="' . $airports[$i]->AirportName . '">'. $airports[$i]->AirportName . '('. $airports[$i]->AirportCity. ')'.'</option>';
}
}?>
</select>
    <input id="submit" type="submit" value="Filter" />
    </div>
</form>
</div>
<br />

<table width="100%" border="0">
<?php

$startAirportId = null;
$stopAirportId = null;
$filter = false;

$id = 0;
$begin = $id;
$end = ($begin + 5);


if (!empty($_SERVER['QUERY_STRING'])) {
    if (isset($_GET['resultid'])) {
        $id = $_GET['resultid'];
        $begin = $_GET['resultid'] * 5;
        $end = ($begin + 5);
    }
}
if (!empty($_SERVER['QUERY_STRING'])) {
    if (isset($_GET['filterBeginpunt']) || isset($_GET['filterEindpunt'])) {

        if (airports::GetAirportByName($_GET['filterBeginpunt']) || airports::
            GetAirportByName($_GET['filterEindpunt'])) {

            $filter = true;
            $id = 0;
            $begin = $id;
            $end = ($begin + 5);

            if (!empty($_GET['filterBeginpunt'])) {
                $startAirportName = airports::GetAirportByName($_GET["filterBeginpunt"]);
                $startAirportId = $startAirportName->AirportID;
            }

            if (!empty($_GET['filterEindpunt'])) {
                $stopAirportName = airports::GetAirportByName($_GET["filterEindpunt"]);
                $stopAirportId = $stopAirportName->AirportID;
            }
            if (!empty($_SERVER['QUERY_STRING'])) {
                if (isset($_GET['resultid'])) {
                    $id = $_GET['resultid'];
                    $begin = $_GET['resultid'] * 5;
                    $end = ($begin + 5);
                }
                $result = trajecten::GetAllTrajecten($begin, $startAirportId, $stopAirportId);
            }
        } else {
            $message = '<script type="text/javascript"> window.alert("Het door u ingevoerde beginpunt of eindpunt bestaat niet. Probeer het opnieuw alstublieft.")</script>';
        }
    }
}
if (!$filter) {
    $result = trajecten::GetAllTrajecten($begin, null, null);
}
$idmin = $id - 1;
$idplus = $id + 1;

$count = count($result);

if (count($result) != 0) {
?>
	<td><b>Beginpunt</b></td>
	<td><b>Eindpunt</b></td>
	<td><b>Actie</b></td>
<?php
    for ($i = 0; $i < $count; $i++) {
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
<?php
if ($idmin > 0 || $idmin == 0) {
?>
	<a href="trajecten.php?resultid=<?php echo $idmin ?>">Vorige</a>
	<?php
}

if (($idplus * 5) < trajecten::GetTrajectAmount($startAirportId, $stopAirportId)) {
?>
	<a href="trajecten.php?resultid=<?php echo $idplus ?>">Volgende</a>
    <?php
}
?>

<script src="../js/jquery-1.9.0.min.js"></script>
<script src="../js/jquery-ui.js"></script>
<script src="../js/grid.locale-nl.js" type="text/javascript"></script>
<script src="../js/jquery.jqGrid.min.js" type="text/javascript"></script>
<script src="../js/javascript.js"></script>

  
<?php
require_once ("onderkant.php");
?>
<?php

if (isset($message)) {
    echo $message;
}
if (isset($_SESSION["added"]) && $_SESSION["added"] == true) {
    $_SESSION["added"] = false;
    echo '<script type="text/javascript"> window.alert("Traject is met success toegevoegd.")</script>';
}
?>