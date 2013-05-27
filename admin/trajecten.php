<?php
//Alle data classes includen
require_once ("../data/includeAll.php");
$titel = "Trajecten";
require_once ("bovenkant.php");
print_r($_POST);
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
    <input name="beginPunt" id="beginPunt" />
    <label for="eindPunt">Eindpunt: </label>
    <input name="eindPunt" id="eindPunt"  />
    <input id="submit" type="submit" value="Toevoegen" />
    <input type="hidden" name="checkPostedAdd" value="yes" />
  </div>
</form>
<br />

<h1>Aanwezige trajecten</h1>
<p>In onderstaande tabel ziet u een overzicht van de trajecten die momenteel aanwezig zijn in de database. Indien gewenst kunt u filter instellen om specifieke trajecten weer te geven.</p>
<input type="button" onclick="return expand('Filter')" value="Filter instellingen"/>
<br />
<div id="Filter" style="display:none">
<br />
<form action="trajecten.php" method="post" >
  <div class="ui-widget">
    <label for="filterBeginPunt">Beginpunt: </label>
    <input name="filterBeginPunt" id="beginPunt" />
    <label for="filterEindPunt">Eindpunt: </label>
    <input name="filterEindPunt"  id="eindPunt"  />
    <input type="hidden" name="checkPostedFilter" value="yes" />
    <input name="action" type="submit" value="Filter" />
    <input name="action" type="submit" value="Verwijder filter" />
    </div>
</form>
</div>
<br />

<table width="100%" border="0">
<?php

if (isset($_POST["checkPostedFilter"])) {
    if ($_POST["action"] == "Verwijder filter") {
        $_SESSION["filterBeginPunt"] = 0;
        $_SESSION["filterEindPunt"] = 0;
        $_SESSION["filterId"] = 0;
    }
    if ($_POST["action"] == "Filter") {
        if (empty($_POST["filterBeginPunt"]) && empty($_POST["filterEindPunt"])) {
            $_SESSION["filterId"] = 0;
            echo '<script type="text/javascript"> window.alert("Beginpunt en eindpunt mogen niet beide leeg zijn.")</script>';
        }
        if ($_POST["filterBeginPunt"] && $_POST["filterEindPunt"]) {

            $startAirportName = airports::GetAirportByName($_POST["filterBeginPunt"]);
            $startAirportId = $startAirportName->AirportID;

            $stopAirportName = airports::GetAirportByName($_POST["filterEindPunt"]);
            $stopAirportId = $stopAirportName->AirportID;

            $_SESSION["filterBeginPunt"] = $startAirportId;
            $_SESSION["filterEindPunt"] = $stopAirportId;
            $_SESSION["filterId"] = 1;
        }
        if ($_POST["filterBeginPunt"] && empty($_POST["filterEindPunt"])) {

            $startAirportName = airports::GetAirportByName($_POST["filterBeginPunt"]);
            $startAirportId = $startAirportName->AirportID;

            $_SESSION["filterBeginPunt"] = $startAirportId;

            $_SESSION["filterEindPunt"] = 0;
            $_SESSION["filterId"] = 2;
        }
        if (empty($_POST["filterBeginPunt"]) && $_POST["filterEindPunt"]) {

            $stopAirportName = airports::GetAirportByName($_POST["filterEindPunt"]);
            $stopAirportId = $stopAirportName->AirportID;

            $_SESSION["filterBeginPunt"] = 0;
            $_SESSION["filterEindPunt"] = $stopAirportId;
            $_SESSION["filterId"] = 3;
        }
    }
} else {
    if (!isset($_SESSION["filterId"])) {
        $_SESSION["filterBeginPunt"] = 0;
        $_SESSION["filterEindPunt"] = 0;
        $_SESSION["filterId"] = 0;
    }
}

if (!empty($_SERVER['QUERY_STRING'])) {
    if (isset($_GET['remove'])) {
        $remove = $_GET['remove'];

        trajecten::RemoveItem($remove);
        $id = 0;
        $begin = $id;
        $end = ($begin + 5);
    }
}
if (!empty($_SERVER['QUERY_STRING'])) {
    if (isset($_GET['add'])) {
        echo ("fck");
    }
}

if (!empty($_SERVER['QUERY_STRING'])) {
    if (isset($_GET['resultid'])) {
        $id = $_GET['resultid'];
        $begin = $_GET['resultid'] * 5;
        $end = ($begin + 5);
    }

} else {
    $id = 0;
    $begin = $id;
    $end = ($begin + 5);
}
$idmin = $id - 1;
$idplus = $id + 1;

#print_r($_SESSION);

if (isset($_SESSION["filterId"])) {
    if ($_SESSION["filterId"] == 0) {
        $result = trajecten::GetAllTrajecten($begin, null, null);
    }
    if ($_SESSION["filterId"] == 1) {
        $result = trajecten::GetAllTrajecten($begin, $_SESSION["filterBeginPunt"], $_SESSION["filterEindPunt"]);
    }
    if ($_SESSION["filterId"] == 2) {
        $result = trajecten::GetAllTrajecten($begin, $_SESSION["filterBeginPunt"], null);
    }
    if ($_SESSION["filterId"] == 3) {
        $result = trajecten::GetAllTrajecten($begin, null, $_SESSION["filterEindPunt"]);
    }
} else {
    $result = trajecten::GetAllTrajecten($begin, null, null);
}
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

if (($idplus * 5) < trajecten::GetTrajectAmount($_SESSION["filterId"], $_SESSION["filterBeginPunt"],
    $_SESSION["filterEindPunt"])) {
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
if ((isset($_POST["checkPostedAdd"]))) {
    if (($_POST["beginPunt"] == $_POST["eindPunt"])) {
        echo '<script type="text/javascript"> window.alert("Beginpunt en eindpunt mogen niet gelijk zijn.")</script>';
    }
    if (!($_POST["beginPunt"] == $_POST["eindPunt"])) {
        if ((!$_POST["beginPunt"] || !$_POST["eindPunt"])) {
            echo '<script type="text/javascript"> window.alert("Beginpunt en eindpunt mogen niet leeg zijn.")</script>';
        } else {
            if (!airports::GetAirportByName($_POST["beginPunt"]) || !airports::
                GetAirportByName($_POST["eindPunt"])) {
                echo '<script type="text/javascript"> window.alert("Het door u ingevoerde beginpunt of eindpunt bestaat niet. Probeer het opnieuw alstublieft.")</script>';
            } else {
                if (trajecten::CheckExist($_POST["beginPunt"], $_POST["eindPunt"])) {
                    echo '<script type="text/javascript"> window.alert("Het door u ingevoerde traject bestaat al. Probeer het opnieuw alstublieft.")</script>';
                } else {
                    try {
                        trajecten::AddItem($_POST["beginPunt"], $_POST["eindPunt"]);
                        $_POST = array();                        
                        #                        echo '<script type="text/javascript"> window.alert("Traject is met success toegevoegd."); window.location.refresh();</script>';
                        #echo '<script type="text/javascript"> window.alert("Traject is met success toegevoegd."); window.location = "trajecten.php;</script>';
                    }
                    catch (exception $e) {
                        echo '<script type="text/javascript"> window.alert("Er ging iets mis met het toevoegen van het traject. Probeer het opnieuw altublieft.")</script>';
                    }
                }
            }
        }
    }
}

#if ((isset($_POST["checkPostedAdd"]))) {
#    if (($_POST["beginPunt"] == $_POST["eindPunt"])) {
#        echo '<script type="text/javascript"> window.alert("Beginpunt en eindpunt mogen niet gelijk zijn.")</script>';
#    }
#    if (!($_POST["beginPunt"] == $_POST["eindPunt"])) {
#        if ((!$_POST["beginPunt"] || !$_POST["eindPunt"])) {
#            echo '<script type="text/javascript"> window.alert("Beginpunt en eindpunt mogen niet leeg zijn.")</script>';
#        } else {
#            if (!airports::GetAirportByName($_POST["beginPunt"]) || !airports::
#                GetAirportByName($_POST["eindPunt"])) {
#                echo '<script type="text/javascript"> window.alert("Het door u ingevoerde beginpunt of eindpunt bestaat niet. Probeer het opnieuw alstublieft.")</script>';
#            } else {
#                try {
#                    trajecten::AddItem($_POST["beginPunt"], $_POST["eindPunt"]);
#                    #                        echo '<script type="text/javascript"> window.alert("Traject is met success toegevoegd."); window.location.refresh();</script>';
#                    #echo '<script type="text/javascript"> window.alert("Traject is met success toegevoegd."); window.location = "trajecten.php;</script>';
#                }
#                catch (exception $e) {
#                    echo '<script type="text/javascript"> window.alert("Er ging iets mis met het toevoegen van het traject. Probeer het opnieuw altublieft.")</script>';
#                }
#            }
#
#        }
#    }
#}

?>