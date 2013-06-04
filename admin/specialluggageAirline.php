<?php
require_once ("../data/includeAll.php");
$titel = "Speciale bagage koppelen";
require_once ("bovenkant.php");
?>

<div id="menu"> <a <?php if (isset($_GET["action"]) && $_GET["action"] == "add") {
    echo "class='active'";
} ?> href="specialluggageAirline.php?action=add">Koppelen</a> <a <?php if (isset
($_GET["action"]) && $_GET["action"] == "edit") {
    echo "class='active'";
} ?> href="specialluggageAirline.php?action=edit">Beheren</a> <a href="specialluggage.php">Speciale bagage toevoegen</a> </div>
<?php

//Determine what mode is selected (add or edit). - Wim
if (!empty($_SERVER["QUERY_STRING"])) {
    if (isset($_GET["action"])) {
        if ($_GET["action"] == "add") {
?>
<br />
<h1>Speciale bagage koppelen</h1>
<p>Via onderstaand formulier kunt u speciale bagage koppelen aan een luchtvaartmaatschappij.</p>
<?php
        }
        if ($_GET["action"] == "edit") {
?>
<br />
<h1>Gekoppelde speciale bagage wijzigen of ontkoppelen</h1>
<p>Via onderstaand formulier kunt u opmerkingen bij gekoppelde speciale bagage wijzigen, of gekoppelde speciale bagage ontkoppelen.</p>
<?php
        } else {
        }
    }
} else {
?>
<br />
<h1>Speciale bagage koppelen en ontkoppelen</h1>
<p>Dit gedeelte omvat het koppelen en ontkoppelen van speciale bagage aan een luchtvaartmaatschappij.</p>
<?php
}
?>
<form action="specialluggageAirline.php" method="get">
  <?php
if (!empty($_SERVER["QUERY_STRING"])) {
    if (isset($_GET["action"])) {
        if ($_GET["action"] == "add" || $_GET["action"] == "edit") {
            if (isset($_GET["airlineName"])) {
                //Check if airline exists in database. - Wim
                if (!airline::get_airline_by_name($_GET["airlineName"])) {
                    $message = '<script type="text/javascript"> window.alert("De door u ingevoerde luchtvaartmaatschappij bestaat niet. Probeer het opnieuw alstublieft.")</script>';
                } else {
                    $valid = true;
                }
            }
?>
  <input type="hidden" name="action" value="<?php if ($_GET["action"] == "add") {
                echo "add";
            }
            if ($_GET["action"] == "edit") {
                echo "edit";

            } ?>" />
  <label for="airlineName">Luchtvaartmaatschappij:</label>
  <br />
  <select name="airlineName" id="airlineName">
            <?php
            $airs = airline::get_airlines();
            foreach ($airs as $air) {

                if (isset($_GET["airlineName"]) && $_GET["airlineName"] == $air->name) {
                    echo '<option selected="true">' . $air->name . '</option>';

                } else {
                    echo '<option>' . $air->name . '</option>';
                }
            } ?>
            </select>
  <input type="submit" value="Selecteer" />
</form>
<?php
        }
        if ($_GET["action"] == "add") {
            if (isset($_POST["checkPostedAdd"]) && $valid == true) {
                if (isset($_POST["selectedSpecialLuggage"])) {
                    //Check if notes is filled in. - Wim
                    if (empty($_POST["specialLuggageNotes"])) {
                        SpecialLuggage::AddItem(airline::get_airline_by_name($_GET["airlineName"])->
                            airline_id, $_POST["selectedSpecialLuggage"], "");

                        $linkedSpecialLuggage = true;
                        session_start();
                        $_SESSION["linkedSpecialLuggage"] = true;
                    } else {

                        if (strlen($_POST["linkedSpecialLuggageNotes"]) < 1000) {
                            SpecialLuggage::AddItem(airline::get_airline_by_name($_GET["airlineName"])->
                                airline_id, $_POST["selectedSpecialLuggage"], $_POST["specialLuggageNotes"]);

                            $linkedSpecialLuggage = true;
                            session_start();
                            $_SESSION["linkedSpecialLuggage"] = true;
                        } else {
                            $message = '<script type="text/javascript"> window.alert("Een opmerking mag maximaal 1000 tekens bevatten.")</script>';
                        }
                    }
                } else {
                    $message = '<script type="text/javascript"> window.alert("Er is geen speciale baggage geselecteerd. Probeer het opnieuw alstublieft.")</script>';
                }
            }

            //Clear POST. - Wim
            if (isset($linkedSpecialLuggage) && $linkedSpecialLuggage == true) {
                $linkedSpecialLuggage = false;
                header("Location: specialluggageAirline.php?action=add&airlineName=" . $_GET["airlineName"]);
                exit;
            }
?>
<br />
<form action="specialluggageAirline.php?action=add&airlineName=<?php echo $_GET["airlineName"] ?>" method="post">
  <label for="selectedSpecialLuggage">Speciale baggage:</label>
  <br />
  <select id="selectedSpecialLuggage" name="selectedSpecialLuggage" size="7" style="width:150px">
    <?php
            if (isset($_GET["airlineName"])) {

                if ($valid == true) {
                    $result = SpecialLuggage::GetLinkedSpecialLuggageList(airline::
                        get_airline_by_name($_GET["airlineName"])->airline_id);
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
  <label for="specialLuggageNotes">Opmerkingen:</label>
  <br />
  <textarea id="specialLuggageNotes" name="specialLuggageNotes" cols="40" rows="10" wrap="virtual" maxlength="1000" style="resize:none"></textarea>
  <br />
  <input type="submit" value="Koppelen" />
  <input type="hidden" name="checkPostedAdd" value="yes" />
</form>
<?php
        }
        if ($_GET['action'] == "edit") {
            if (isset($_POST["submitChangeRemove"])) {
                //Check what button is clicked ("Ontkoppelen" or "Wijzigen"). - Wim
                if ($_POST["submitChangeRemove"] == "Ontkoppelen") {
                    if (!empty($_POST["linkedSpecialLuggage"])) {

                        //Remove special luggage. - Wim
                        $result = SpecialLuggage::GetSpecialLuggageName($_POST["linkedSpecialLuggage"]);

                        SpecialLuggage::RemoveAirLineSpecialLuggage($result->specialluggage_id, airline::
                            get_airline_by_name($_GET["airlineName"])->airline_id);

                        $removedSpecialLuggage = true;
                        session_start();
                        $_SESSION["removedSpecialLuggage"] = true;
                    } else {
                        $message = '<script type="text/javascript"> window.alert("Er is geen speciale baggage geselecteerd. Probeer het opnieuw alstublieft.")</script>';
                    }
                }
                if ($_POST["submitChangeRemove"] == "Wijzigen") {
                    if (!empty($_POST["linkedSpecialLuggage"])) {

                        if (strlen($_POST["linkedSpecialLuggageNotes"]) < 1000) {
                            //Edit existing linked special luggage. - Wim
                            $result = SpecialLuggage::GetSpecialLuggageName($_POST["linkedSpecialLuggage"]);
                            SpecialLuggage::EditAirlineNotes($result->specialluggage_id, airline::
                                get_airline_by_name($_GET["airlineName"])->airline_id, $_POST["linkedSpecialLuggageNotes"]);
                        } else {
                            $message = '<script type="text/javascript"> window.alert("Wijziging niet opgeslagen: een opmerking mag maximaal 1000 tekens bevatten.")</script>';
                        }
                    } else {
                        $message = '<script type="text/javascript"> window.alert("Er is geen speciale baggage geselecteerd. Probeer het opnieuw alstublieft.")</script>';
                    }
                }
            }

            //Clear POST. - Wim
            if (isset($removedSpecialLuggage) && $removedSpecialLuggage == true) {
                $removedSpecialLuggage = false;
                header("Location: specialluggageAirline.php?action=edit&airlineName=" . $_GET["airlineName"]);
                exit;
            }
?>
<br />
<form name="linkedSpecialLuggageForm" action="specialluggageAirline.php?action=edit&airlineName=<?php echo
            $_GET["airlineName"] ?>" method="post">
  <label for="linkedSpecialLuggage">Gekoppelde speciale bagage:</label>
  <br />
  <select id="linkedSpecialLuggage" name="linkedSpecialLuggage" size="7" style="width:150px" onChange="document.linkedSpecialLuggageForm.submit();">
    <?php
            //Populated select box. - Wim
            if (isset($_GET["airlineName"])) {
                if ($valid == true) {
                    $result = SpecialLuggage::GetNotLinkedSpecialLuggageList(airline::
                        get_airline_by_name($_GET["airlineName"])->airline_id);
                    for ($i = 0; $i < count($result); $i++) {
?>
    <option value="<?php echo ($result[$i]->Name); ?>" <?php if (isset($_POST["linkedSpecialLuggage"])) {
                            if ($_POST["linkedSpecialLuggage"] == $result[$i]->Name) {                                {
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
  <label for="linkedSpecialLuggageNotes">Opmerkingen:</label>
  <br />
  <textarea id="linkedSpecialLuggageNotes" name="linkedSpecialLuggageNotes" cols="40" rows="10" wrap="virtual" maxlength="1000" style="resize:none">
<?php if (!empty($_POST["linkedSpecialLuggage"])) {
                //Populate notes texarea. - Wim
                $resulta = SpecialLuggage::GetSpecialLuggageName($_POST["linkedSpecialLuggage"]);
                $resultb = SpecialLuggage::GetCombo(airline::get_airline_by_name($_GET["airlineName"])->
                    airline_id, $resulta->specialluggage_id);
                echo ($resultb->Notes);
            } ?>
</textarea>
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

//Show messages to inform the user when needed. - Wim
if (isset($message)) {
    echo $message;
}
if (isset($_SESSION["linkedSpecialLuggage"]) && $_SESSION["linkedSpecialLuggage"] == true) {
    $_SESSION["linkedSpecialLuggage"] = false;
    echo '<script type="text/javascript"> window.alert("Speciale bagage is met succes toegevoegd.")</script>';
}
if (isset($_SESSION["removedSpecialLuggage"]) && $_SESSION["removedSpecialLuggage"] == true) {
    $_SESSION["removedSpecialLuggage"] = false;
    echo '<script type="text/javascript"> window.alert("Speciale bagage is met succes ontkoppeld.")</script>';
}
?>
