<?php
/**
 * @author Wim Dalof
 * @copyright 2013, All rights reserved
 * @date 07-06-3013
 */

require_once ("../data/includeAll.php");
$titel = "Speciale bagage koppelen";
require_once ("bovenkant.php");
$airlineValid = false;
$specialLuggageValid = false;
?>

<div id="menu">
  <ul>
    <li> <a <?php if (isset($_GET["action"]) && $_GET["action"] == "add") {
    echo ("class='active'");
} ?> href="specialluggageAirline.php?action=add">Koppelen</a> </li>
    <li> <a <?php if (isset($_GET["action"]) && $_GET["action"] == "edit") {
    echo ("class='active'");
} ?> href="specialluggageAirline.php?action=edit">Beheren</a> </li>
    <li> <a href="specialluggage.php">Speciale bagage toevoegen</a> </li>
  </ul>
</div>
<br />
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
<p>Via dit formulier kunt u opmerkingen wijzigen of speciale bagage ontkoppelen bij de desbetreffende luchtvaartmaatschappij.</p>
<?php
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
<form name="specialLuggageAirlineForm" action="specialluggageAirline.php" method="get" >
  <?php
if (!empty($_SERVER["QUERY_STRING"])) {
    if (isset($_GET["action"])) {
        if ($_GET["action"] == "add" || $_GET["action"] == "edit") {
            if (isset($_GET["airlineName"])) {
                //Check if airline exists in database. - Wim
                if (!airline::get_airline_by_name($_GET["airlineName"])) {
                    $airlineMessage = "De door u ingevoerde luchtvaartmaatschappij bestaat niet. Probeer het opnieuw alstublieft.";
                } else {
                    $airlineValid = true;
                }
            }
?>
  <input type="hidden" name="action" value="<?php if ($_GET["action"] == "add") {
                echo ("add");
            }
            if ($_GET["action"] == "edit") {
                echo ("edit");

            } ?>" />
  <label for="airlineName">Luchtvaartmaatschappij:</label>
  <br />
  <select name="airlineName" id="airlineName" onChange="document.specialLuggageAirlineForm.submit();">
    <option value=""></option>
    <?php
            $airs = airline::get_airlines();
            foreach ($airs as $air) {

                if (isset($_GET["airlineName"]) && $_GET["airlineName"] == $air->name) {
                    echo ('<option selected="true">' . $air->name . '</option>');

                } else {
                    echo ('<option>' . $air->name . '</option>');
                }
            } ?>
  </select>
</form>
<?php
            if (isset($airlineMessage)) {
                echo ("<p class='error'>" . $airlineMessage . "</p>");
            } else {
                echo ("<br>");
            }
        }
        if ($_GET["action"] == "add") {
            //Check if special luggage is valid.
            if (isset($_POST["availableSpecialLuggage"])) {
                if (SpecialLuggage::GetSpecialLuggageName($_POST["availableSpecialLuggage"])) {
                    $specialLuggageValid = true;
                } else {
                    $specialLuggageValid = false;
                }
            }
            if (isset($_POST["checkPostedAdd"]) && $airlineValid == true) {
                if (isset($_POST["availableSpecialLuggage"]) && $specialLuggageValid) {
                    //Check if notes is filled in. - Wim
                    SpecialLuggage::AddItem(airline::get_airline_by_name($_GET["airlineName"])->
                        airline_id, $_POST["availableSpecialLuggage"], $_POST["specialLuggageNotes"], $_POST["specialLuggageFare"],
                        $_POST["specialLuggageDimension"], $_POST["specialLuggageWeight"]);

                    $linkedSpecialLuggage = true;
                    session_start();
                    $_SESSION["linkedSpecialLuggage"] = true;
                } else {
                    $availableMessage = "<p class='error'> Er is geen speciale bagage geselecteerd. Probeer het opnieuw alstublieft.";
                }
            }
            if (isset($_POST["checkPostedAdd"]) && empty($_POST["availableSpecialLuggage"])) {
                $availableMessage = "<p class='error'> Er is geen speciale bagage geselecteerd. Probeer het opnieuw alstublieft.";
            }

            //Clear POST. - Wim
            if (isset($linkedSpecialLuggage) && $linkedSpecialLuggage == true) {
                $linkedSpecialLuggage = false;
                header("Location: specialluggageAirline.php?action=add&airlineName=" . $_GET["airlineName"]);
                exit;
            }
?>
<form action="specialluggageAirline.php?action=add&airlineName=<?php if ($airlineValid) {
                echo ($_GET["airlineName"]);
            } ?>" method="post">
  <label for="availableSpecialLuggage">Speciale bagage:</label>
  <br />
  <select id="availableSpecialLuggage" name="availableSpecialLuggage" size="7" style="width:150px" >
    <?php
            if (isset($_GET["airlineName"])) {

                if ($airlineValid == true) {
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
  <br />
    <label for="specialLuggageFare">Tarief (euro):</label>
  <br />
  <input type="text id="specialLuggageFare" name="specialLuggageFare"/>
    <br />
    <br />
    <label for="specialLuggageDimension">Afmeting:</label>
  <br />
  <input type="text id="specialLuggageDimension" name="specialLuggageDimension"/>
      <br />
      <br />
    <label for="specialLuggageWeight">Gewicht (kg):</label>
  <br />
  <input type="text id="specialLuggageWeight" name="specialLuggageWeight"/>
  <br />
  <br />
  <input type="submit" value="Koppelen" />
  <input type="hidden" name="checkPostedAdd" value="yes" />
</form>
<?php
        }
        if ($_GET['action'] == "edit") {
            //Check if special luggage is valid.
            if (isset($_POST["linkedSpecialLuggage"])) {
                if (SpecialLuggage::GetSpecialLuggageName($_POST["linkedSpecialLuggage"])) {
                    $specialLuggageValid = true;
                } else {
                    $specialLuggageValid = false;
                }
            }
            if (isset($_POST["submitChangeRemove"])) {
                //Check what button is clicked ("Ontkoppelen" or "Wijzigen"). - Wim
                if ($_POST["submitChangeRemove"] == "Ontkoppelen") {
                    if (!empty($_POST["linkedSpecialLuggage"]) && $specialLuggageValid) {

                        //Remove special luggage. - Wim
                        $result = SpecialLuggage::GetSpecialLuggageName($_POST["linkedSpecialLuggage"]);

                        SpecialLuggage::RemoveAirLineSpecialLuggage($result->specialluggage_id, airline::
                            get_airline_by_name($_GET["airlineName"])->airline_id);

                        $removedSpecialLuggage = true;
                        session_start();
                        $_SESSION["removedSpecialLuggage"] = true;
                    } else {
                        $linkMessage = "<p class='error'> Er is geen speciale bagage geselecteerd. Probeer het opnieuw alstublieft.";
                    }
                }
                if ($_POST["submitChangeRemove"] == "Wijzigen") {
                    if (!empty($_POST["linkedSpecialLuggage"]) && $specialLuggageValid) {

                        if (strlen($_POST["linkedSpecialLuggageNotes"]) < 1000) {
                            //Edit existing linked special luggage. - Wim
                            $result = SpecialLuggage::GetSpecialLuggageName($_POST["linkedSpecialLuggage"]);
                            SpecialLuggage::EditAirlineSpecialLuggage($result->specialluggage_id, airline::
                                get_airline_by_name($_GET["airlineName"])->airline_id, $_POST["linkedSpecialLuggageNotes"],
                                $_POST["linkedSpecialLuggageFare"], $_POST["linkedSpecialLuggageDimension"], $_POST["linkedSpecialLuggageWeight"]);
                            $linkMessage = "<p class='good'>Wijziging is met succes doorgevoerd.";
                        } else {
                            $linkMessage = "<p class='error'>Wijziging niet opgeslagen: een opmerking mag maximaal 1000 tekens bevatten.";
                        }
                    } else {
                        $linkMessage = "<p class='error'>Er is geen speciale bagage geselecteerd. Probeer het opnieuw alstublieft.";
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
<form name="linkedSpecialLuggageForm" action="specialluggageAirline.php?action=edit&airlineName=<?php if ($airlineValid) {
                echo $_GET["airlineName"];
            } ?>" method="post">
  <label for="linkedSpecialLuggage">Gekoppelde speciale bagage:</label>
  <br />
  <select id="linkedSpecialLuggage" name="linkedSpecialLuggage" size="7" style="width:150px" onChange="document.linkedSpecialLuggageForm.submit();">
    <?php
            //Populated select box. - Wim
            if (isset($_GET["airlineName"])) {
                if ($airlineValid == true) {
                    $result = SpecialLuggage::GetNotLinkedSpecialLuggageList(airline::
                        get_airline_by_name($_GET["airlineName"])->airline_id);
                    for ($i = 0; $i < count($result); $i++) {
?>
    <option value="<?php echo ($result[$i]->Name); ?>" <?php if (isset($_POST["linkedSpecialLuggage"])) {
                            if ($_POST["linkedSpecialLuggage"] == $result[$i]->Name) {                                {
                                    echo ("selected = 'selected'");
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
<?php if (!empty($_POST["linkedSpecialLuggage"]) && $specialLuggageValid) {
                //Populate notes texarea. - Wim
                $resulta = SpecialLuggage::GetSpecialLuggageName($_POST["linkedSpecialLuggage"]);
                $resultb = SpecialLuggage::GetCombo(airline::get_airline_by_name($_GET["airlineName"])->
                    airline_id, $resulta->specialluggage_id);
                echo ($resultb->Notes);
            } ?>
</textarea>
<br />
  <br />
    <label for="linkedSpecialLuggageFare">Tarief:</label>
  <br />
  <input type="text id="linkedSpecialLuggageFare" name="linkedSpecialLuggageFare" value="<?php if (!
            empty($_POST["linkedSpecialLuggage"]) && $specialLuggageValid) {
                //Populate notes texarea. - Wim
                $resulta = SpecialLuggage::GetSpecialLuggageName($_POST["linkedSpecialLuggage"]);
                $resultb = SpecialLuggage::GetCombo(airline::get_airline_by_name($_GET["airlineName"])->
                    airline_id, $resulta->specialluggage_id);
                echo ($resultb->Fare);
            } ?>"/>
    <br />
    <br />
    <label for="linkedSpecialLuggageDimension">Afmeting:</label>
  <br />
  <input type="text id="linkedSpecialLuggageDimension" name="linkedSpecialLuggageDimension" value="<?php if (!
            empty($_POST["linkedSpecialLuggage"]) && $specialLuggageValid) {
                //Populate notes texarea. - Wim
                $resulta = SpecialLuggage::GetSpecialLuggageName($_POST["linkedSpecialLuggage"]);
                $resultb = SpecialLuggage::GetCombo(airline::get_airline_by_name($_GET["airlineName"])->
                    airline_id, $resulta->specialluggage_id);
                echo ($resultb->Dimension);
            } ?>"/>
      <br />
      <br />
    <label for="linkedSpecialLuggageWeight">Gewicht:</label>
  <br />
  <input type="text id="linkedSpecialLuggageWeight" name="linkedSpecialLuggageWeight" value="<?php if (!
            empty($_POST["linkedSpecialLuggage"]) && $specialLuggageValid) {
                //Populate notes texarea. - Wim
                $resulta = SpecialLuggage::GetSpecialLuggageName($_POST["linkedSpecialLuggage"]);
                $resultb = SpecialLuggage::GetCombo(airline::get_airline_by_name($_GET["airlineName"])->
                    airline_id, $resulta->specialluggage_id);
                echo ($resultb->Weight);
            } ?>"/>
  <br />
  <br />
  <input type="submit" name="submitChangeRemove" value="Wijzigen" />
  <input type="submit" name="submitChangeRemove" value="Ontkoppelen" />
  <input type="hidden" name="checkPostedAdd" value="yes" />
</form>
<?php
        }
    }
}
if (isset($_SESSION["linkedSpecialLuggage"]) && $_SESSION["linkedSpecialLuggage"] == true) {
    $_SESSION["linkedSpecialLuggage"] = false;
    echo ("<p class='good'>Speciale bagage is met succes gekoppeld.</p>");
}
if (isset($_SESSION["removedSpecialLuggage"]) && $_SESSION["removedSpecialLuggage"] == true) {
    $_SESSION["removedSpecialLuggage"] = false;
    echo ("<p class='good'>Speciale bagage is met succes ontkoppeld.</p>");
}

//Show messages to inform the user when needed. - Wim
if (isset($availableMessage)) {
    echo ("<p class='error'>" . $availableMessage . "</p>");
}
if (isset($linkMessage)) {
    echo ($linkMessage . "</p>");
}

require_once ("onderkant.php");
?>
