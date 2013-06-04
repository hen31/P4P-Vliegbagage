<?php
//Alle data classes includen
require_once ("../data/includeAll.php");
$titel = "Trajecten";

if (!empty($_SERVER["QUERY_STRING"])) {
    if (isset($_GET["remove"])) {
        $remove = $_GET["remove"];

        //Remove a traject from database. - Wim
        trajecten::remove_traject_by_trajectid($remove);
        header("Location: trajecten.php");
        exit;
    }
}
if ((isset($_POST["checkPostedAdd"]))) {
    //Check if starting point and ending point arre the same. - Wim
    if (($_POST["startingPoint"] == $_POST["endingPoint"])) {
        $message = '<script type="text/javascript"> window.alert("Beginpunt en eindpunt mogen niet gelijk zijn.")</script>';
    }
    //Check if starting point and ending point are set. - Wim
    if (!($_POST["startingPoint"] == $_POST["endingPoint"])) {
        if ((!$_POST["startingPoint"] || !$_POST["endingPoint"])) {
            $message = '<script type="text/javascript"> window.alert("Beginpunt en eindpunt mogen niet leeg zijn.")</script>';
        } else {
            //Check if starting point and endeing point exists. - Wim
            if (!airports::GetAirportByName($_POST["startingPoint"]) || !airports::
                GetAirportByName($_POST["endingPoint"])) {
                $message = '<script type="text/javascript"> window.alert("Het door u ingevoerde beginpunt of eindpunt bestaat niet. Probeer het opnieuw alstublieft.")</script>';
            } else {
                //Check if traject already exists in database. - Wim
                if (trajecten::check_traject_exist($_POST["startingPoint"], $_POST["endingPoint"])) {
                    $message = '<script type="text/javascript"> window.alert("Het door u ingevoerde traject bestaat al. Probeer het opnieuw alstublieft.")</script>';
                } else {
                    //Add traject to database. - Wim
                    trajecten::add_traject($_POST["startingPoint"], $_POST["endingPoint"]);
                    $added = true;
                    session_start();
                    $_SESSION["added"] = true;
                }
            }
        }
    }
}

//Clear POST. - Wim
if (isset($added) && $added == true) {
    $added = false;
    header("Location: trajecten.php");
    exit;
}
require_once ("bovenkant.php");

?>
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

<a href="trajectenAirline.php">Traject koppelen aan luchtvaartmaatschappij</a><br/>
<br />
<h1>Traject toevoegen</h1>
<p>Via onderstaand formulier kunt u een nieuw traject toevoegen. Selecteer een begin- en eindpunt, en klik vervolgens op "Toevoegen".</p>
<form action="trajecten.php" method="post">
  <label for="startingPoint">Beginpunt: </label>
  <select name="startingPoint" id="startingPoint">
    <?php
//Populate starting point dropdown list. - Wim
$airports = airports::GetAirports();
for ($i = 0; $i < count($airports); $i++) {
    if (isset($_GET["startingPoint"]) && htmlspecialchars($_GET["startingPoint"]) ==
        $airports[$i]->AirportName) {
        echo '<option selected="true" value="' . $airports[$i]->AirportName . '">' . $airports[$i]->
            AirportName . '(' . $airports[$i]->AirportCity . ')' . '</option>';
    } else {
        echo '<option value="' . $airports[$i]->AirportName . '">' . $airports[$i]->
            AirportName . '(' . $airports[$i]->AirportCity . ')' . '</option>';
    }
} ?>
  </select>
  <label for="endingPoint">Eindpunt: </label>
  <select name="endingPoint" id="endingPoint" >
    <?php
//Populate ending point dropdown list. - Wim
$airports = airports::GetAirports();
for ($i = 0; $i < count($airports); $i++) {
    if (isset($_GET["endingPoint"]) && htmlspecialchars($_GET["endingPoint"]) == $airports[$i]->
        AirportName) {
        echo '<option selected="true" value="' . $airports[$i]->AirportName . '">' . $airports[$i]->
            AirportName . '(' . $airports[$i]->AirportCity . ')' . '</option>';
    } else {
        echo '<option value="' . $airports[$i]->AirportName . '">' . $airports[$i]->
            AirportName . '(' . $airports[$i]->AirportCity . ')' . '</option>';
    }
} ?>
  </select>
  <input id="submit" type="submit" value="Toevoegen" />
  <input type="hidden" name="checkPostedAdd" value="yes" />
</form>
<br />
<h1>Aanwezige trajecten</h1>
<p>In onderstaande tabel ziet u een overzicht van de trajecten die momenteel aanwezig zijn in de database. Indien gewenst kunt u een filter instellen om specifieke trajecten weer te geven.</p>
<input style="float:left;" type="button" onclick="return expand('Filter')" value="Filterinstellingen"/>
<form style="float:left;" action="trajecten.php" method="post" >
  <input name="action" type="submit" value="Verwijder filter" />
</form>
<br />
<div id="Filter" <?php if (!isset($_GET["filterStartingPoint"]) || !isset($_GET["filterEndingPoint"])) {
    echo "style='display:none'";
} ?> > <br />
  <form action="trajecten.php" method="get" >
    <label for="filterStartingPoint">Beginpunt: </label>
    <select name="filterStartingPoint" id="filterStartingPoint">
      <option value="">Alles</option>
      <?php
//Populate starting point dropdown list (filter). - Wim
$airports = airports::GetAirports();
for ($i = 0; $i < count($airports); $i++) {
    if (isset($_GET["filterStartingPoint"]) && htmlspecialchars($_GET["filterStartingPoint"]) ==
        $airports[$i]->AirportName) {
        echo '<option selected="true" value="' . $airports[$i]->AirportName . '">' . $airports[$i]->
            AirportName . '(' . $airports[$i]->AirportCity . ')' . '</option>';
    } else {
        echo '<option value="' . $airports[$i]->AirportName . '">' . $airports[$i]->
            AirportName . '(' . $airports[$i]->AirportCity . ')' . '</option>';
    }
} ?>
    </select>
    <label for="filterEndingPoint">Eindpunt: </label>
    <select name="filterEndingPoint" id="filterEndingPoint" >
      <option value="">Alles</option>
      <?php
//Populate ending point dropdown list. - Wim
$airports = airports::GetAirports();
for ($i = 0; $i < count($airports); $i++) {
    if (isset($_GET["filterEndingPoint"]) && htmlspecialchars($_GET["filterEndingPoint"]) ==
        $airports[$i]->AirportName) {
        echo '<option selected="true" value="' . $airports[$i]->AirportName . '">' . $airports[$i]->
            AirportName . '(' . $airports[$i]->AirportCity . ')' . '</option>';
    } else {
        echo '<option value="' . $airports[$i]->AirportName . '">' . $airports[$i]->
            AirportName . '(' . $airports[$i]->AirportCity . ')' . '</option>';
    }
} ?>
    </select>
    <input id="submit" type="submit" value="Filter" />
  </form>
</div>
<br />
<table id="trajectentable" border="0">
  <?php

$startAirportId = null;
$stopAirportId = null;
$filter = false;

$id = 0;
$begin = $id;
$end = ($begin + 10);


if (!empty($_SERVER["QUERY_STRING"])) {
    if (isset($_GET["pageId"])) {
        $id = $_GET["pageId"];
        $begin = $_GET["pageId"] * 10;
        $end = ($begin + 10);
    }
}
if (!empty($_SERVER["QUERY_STRING"])) {
    if (isset($_GET["filterStartingPoint"]) || isset($_GET["filterEndingPoint"])) {

        //Determine what the user selected for filtering and apply the filter. - Wim
        if (airports::GetAirportByName($_GET["filterStartingPoint"]) || airports::
            GetAirportByName($_GET["filterEndingPoint"])) {

            $filter = true;
            $id = 0;
            $begin = $id;
            $end = ($begin + 10);

            if (!empty($_GET["filterStartingPoint"])) {
                $startAirportName = airports::GetAirportByName($_GET["filterStartingPoint"]);
                $startAirportId = $startAirportName->AirportID;
            }

            if (!empty($_GET["filterEndingPoint"])) {
                $stopAirportName = airports::GetAirportByName($_GET["filterEndingPoint"]);
                $stopAirportId = $stopAirportName->AirportID;
            }
            if (!empty($_SERVER["QUERY_STRING"])) {
                if (isset($_GET["pageId"])) {
                    $id = $_GET["pageId"];
                    $begin = $_GET["pageId"] * 10;
                    $end = ($begin + 10);
                }
                $result = trajecten::get_all_trajecten($begin, $startAirportId, $stopAirportId);
            }
        } else {
            $message = '<script type="text/javascript"> window.alert("Het door u ingevoerde beginpunt of eindpunt bestaat niet. Probeer het opnieuw alstublieft.")</script>';
        }
    }
}
if (!$filter) {
    $result = trajecten::get_all_trajecten($begin, null, null);
}
//Variabels for pagination. - Wim
$idmin = $id - 1;
$idplus = $id + 1;

if (isset($result)) {
    //Populate the table in which the trajecten are displayed. - Wim.
    $count = count($result);

?>
  <td><b>Beginpunt</b></td>
    <td><b>Eindpunt</b></td>
    <td><b>Actie</b></td>
    <?php
    for ($i = 0; $i < $count; $i++) {
?>
  <tr>
    <td><?php echo ($result[$i]["airport_start_id"]->AirportName); ?></td>
    <td><?php echo ($result[$i]["airport_stop_id"]->AirportName); ?></td>
    <td><a href="trajecten.php?remove=<?php echo $result[$i]["traject_id"] ?>">Verwijder</a></td>
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
<a href="trajecten.php?pageId=<?php echo $idmin ?>">Vorige</a>
<?php
}

if (($idplus * 10) < trajecten::get_traject_amount($startAirportId, $stopAirportId)) {
?>
<a href="trajecten.php?pageId=<?php echo $idplus ?>">Volgende</a>
<?php
}
require_once ("onderkant.php");
?>
<?php

//Show messages to inform the user when needed. - Wim
if (isset($message)) {
    echo $message;
}
if (isset($_SESSION["added"]) && $_SESSION["added"] == true) {
    $_SESSION["added"] = false;
    echo '<script type="text/javascript"> window.alert("Traject is met succes toegevoegd.")</script>';
}
?>
