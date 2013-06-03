<?php
//Alle data classes includen
require_once ("../data/includeAll.php");
$titel = "SpecialluggageAirline";
require_once ("bovenkant.php");
?>
<a href="specialluggageAirline.php?action=add">Koppelen</a>
<a href="specialluggageAirline.php?action=edit">Beheren</a>
<br />
<?php
if (!empty($_SERVER['QUERY_STRING'])) {
    if (isset($_GET['action'])) {
        if ($_GET['action'] == "add") {
?>
<br />
<h1>Speciale baggage koppelen</h1>
<p>Via onderstaand formulier kunt u speciale baggage koppelen aan een luchtvaartmaatschappij.</p>
<?php
        }
        if ($_GET['action'] == "edit") {
?>
<br />
            <h1>Gekoppelde speciale baggage wijzigen of ontkoppelen</h1>
<p>Via onderstaand formulier kunt u opmerkingen bij gekoppelde speciale bagagge wijzigen, of gekoppelde speciale baggage ontkoppelen.</p>
            <?php
        } else {
        }
    }
} else {
?>
    <br />
<h1>Speciale baggage koppelen en ontkoppelen</h1>
<p>Dit gedeelte omvat het koppelen en ontkoppelen van speciale baggage aan een luchtvaartmaatschappij.</p>
    <?php
}
?>

            <form action="specialluggageAirline.php" method="get">
<?php
if (!empty($_SERVER['QUERY_STRING'])) {
    if (isset($_GET['action'])) {
        if ($_GET['action'] == "add" || $_GET['action'] == "edit") {
            if (isset($_GET['AirlineName'])) {
                if (!airline::get_airline_by_name($_GET["AirlineName"])) {
                    $message = '<script type="text/javascript"> window.alert("De door u ingevoerde luchtvaartmaatschappij bestaat niet. Probeer het opnieuw alstublieft.")</script>';
                } else {
                    $valid = true;
                }
            }
?>
    <input type="hidden" name="action" value="<?php if ($_GET['action'] == "add") {
                echo "add";
            }
            if ($_GET['action'] == "edit") {
                echo "edit";

            } ?>" />
  <label for="AirlineName">Luchtvaartmaatschappij:</label>
  <br />
<input name="AirlineName" id="AirlineName" value="<?php if (isset($_GET["AirlineName"])) {
                echo ($_GET["AirlineName"]);
            } else {
                echo null;
            } ?>"/>
<input type="submit" value="Selecteer" />
</form>
<?php
        }
        if ($_GET['action'] == "add") {
            if (isset($_POST["checkPostedAdd"]) && $valid == true) {
                if (isset($_POST["SelectedSpecialLuggage"])) {
                    if (empty($_POST["SpecialLuggageNotes"])) {
                        SpecialLuggage::AddItem(airline::get_airline_by_name($_GET["AirlineName"])->
                            airline_id, $_POST["SelectedSpecialLuggage"], "");

                        $linkedSpecialLuggage = true;
                        session_start();
                        $_SESSION["linkedSpecialLuggage"] = true;
                    } else {
                        SpecialLuggage::AddItem(airline::get_airline_by_name($_GET["AirlineName"])->
                            airline_id, $_POST["SelectedSpecialLuggage"], $_POST["SpecialLuggageNotes"]);

                        $linkedSpecialLuggage = true;
                        session_start();
                        $_SESSION["linkedSpecialLuggage"] = true;
                    }
                } else {
                    $message = '<script type="text/javascript"> window.alert("Er is geen speciale baggage geselecteerd. Probeer het opnieuw alstublieft.")</script>';
                }
            }

            if (isset($linkedSpecialLuggage) && $linkedSpecialLuggage == true) {
                $linkedSpecialLuggage = false;
                header("Location: specialluggageAirline.php?action=add&AirlineName=" . $_GET["AirlineName"]);
                exit;
            }
?>
<br />
<form action="specialluggageAirline.php?action=add&AirlineName=<?php echo $_GET["AirlineName"] ?>" method="post">
<label for="SelectedSpecialLuggage">Speciale bagage:</label>
  <br />
<select id="SelectedSpecialLuggage" name="SelectedSpecialLuggage" size="7" style="width:150px">
<?php
            if (isset($_GET["AirlineName"])) {

                if ($valid == true) {
                    $result = SpecialLuggage::GetSpecialLuggageListTest(airline::
                        get_airline_by_name($_GET["AirlineName"])->airline_id);
                    for ($i = 0; $i < count($result); $i++) {
?>
    <option value="<?php echo ($result[$i]->Name); ?>"><?php echo ($result[$i]->
                        Name); ?></option>
<?php
                    }
                }
            }
?>
</select>
<br />
<br />
  <label for="SpecialLuggageNotes">Opmerkingen:</label>
  <br />
<textarea id="SpecialLuggageNotes" name="SpecialLuggageNotes" cols="40" rows="10" wrap="virtual" maxlength="1000" style="resize:none"></textarea>
<br />
<input type="submit" value="Koppelen" /> 
    <input type="hidden" name="checkPostedAdd" value="yes" />
</form>

<?php
        }
        if ($_GET['action'] == "edit") {

            if (isset($_POST["submitChangeRemove"])) {
                if ($_POST["submitChangeRemove"] == "Ontkoppelen") {

                    if (!empty($_POST["ConnectedSpecialLuggage"])) {
                        $resulta = SpecialLuggage::GetSpecialLuggageName($_POST["ConnectedSpecialLuggage"]);

                        SpecialLuggage::RemoveAirLineSpecialLuggage($resulta->specialluggage_id, airline::
                            get_airline_by_name($_GET["AirlineName"])->airline_id);

                        $removedSpecialLuggage = true;
                        session_start();
                        $_SESSION["removedSpecialLuggage"] = true;
                    } else {
                        $message = '<script type="text/javascript"> window.alert("Er is geen speciale baggage geselecteerd. Probeer het opnieuw alstublieft.")</script>';
                    }
                }
                if ($_POST["submitChangeRemove"] == "Wijzigen") {
                    if (!empty($_POST["ConnectedSpecialLuggage"])) {
                        $resulta = SpecialLuggage::GetSpecialLuggageName($_POST["ConnectedSpecialLuggage"]);
                        SpecialLuggage::EditAirlineNotes($resulta->specialluggage_id, airline::
                            get_airline_by_name($_GET["AirlineName"])->airline_id, $_POST["ConnectedSpecialLuggageNotes"]);
                    } else {
                        $message = '<script type="text/javascript"> window.alert("Er is geen speciale baggage geselecteerd. Probeer het opnieuw alstublieft.")</script>';
                    }
                }
            }

            if (isset($removedSpecialLuggage) && $removedSpecialLuggage == true) {
                $removedSpecialLuggage = false;
                header("Location: specialluggageAirline.php?action=edit&AirlineName=" . $_GET["AirlineName"]);
                exit;
            }
?>
<br />
<form name="ConnectedSpecialLuggageForm" action="specialluggageAirline.php?action=edit&AirlineName=<?php echo
            $_GET["AirlineName"] ?>" method="post">
  <label for="ConnectedSpecialLuggage">Speciale bagage:</label>
  <br />
<select id="ConnectedSpecialLuggage" name="ConnectedSpecialLuggage" size="7" style="width:150px" onChange="document.ConnectedSpecialLuggageForm.submit();">
<?php
            if (isset($_GET["AirlineName"])) {

                if ($valid == true) {
                    $result = SpecialLuggage::GetSpecialLuggageListNotTest(airline::
                        get_airline_by_name($_GET["AirlineName"])->airline_id);
                    for ($i = 0; $i < count($result); $i++) {
?>
    <option value="<?php echo ($result[$i]->Name); ?>" <?php if (isset($_POST["ConnectedSpecialLuggage"])) {
                            if ($_POST["ConnectedSpecialLuggage"] == $result[$i]->Name) {                                {
                                    echo "selected = 'selected'";
                                }
                            }
                        } ?>><?php echo ($result[$i]->Name); ?></option>
<?php
                    }
                }
            }
?>
</select>
<br />
<br />
  <label for="ConnectedSpecialLuggageNotes">Opmerkingen:</label>
  <br />
<textarea id="ConnectedSpecialLuggageNotes" name="ConnectedSpecialLuggageNotes" cols="40" rows="10" wrap="virtual" maxlength="1000" style="resize:none">
<?php if (!empty($_POST["ConnectedSpecialLuggage"])) {
                $resulta = SpecialLuggage::GetSpecialLuggageName($_POST["ConnectedSpecialLuggage"]);
                $resultb = SpecialLuggage::GetCombo(airline::get_airline_by_name($_GET["AirlineName"])->
                    airline_id, $resulta->specialluggage_id);
                echo ($resultb->Notes);
            } ?></textarea>
<br />
<input type="submit" name="submitChangeRemove" value="Wijzigen" /> 
<input type="submit" name="submitChangeRemove" value="Ontkoppelen" /> 
    <input type="hidden" name="checkPostedAdd" value="yes" />
</form>
<?php
        }
    }
}
require_once ("onderkant.php");
?>
<?php

if (isset($message)) {
    echo $message;
}
if (isset($_SESSION["linkedSpecialLuggage"]) && $_SESSION["linkedSpecialLuggage"] == true) {
    $_SESSION["linkedSpecialLuggage"] = false;
    echo '<script type="text/javascript"> window.alert("Speciale bagagge is met success toegevoegd.")</script>';
}
if (isset($_SESSION["removedSpecialLuggage"]) && $_SESSION["removedSpecialLuggage"] == true) {
    $_SESSION["removedSpecialLuggage"] = false;
    echo '<script type="text/javascript"> window.alert("Speciale bagagge is met success ontkoppeld.")</script>';
}
?>