<?php

//Alle data classes includen
require_once ("../data/includeAll.php");
$titel = "Trajecten koppelen";
require_once ("bovenkant.php");
        $falseAirport= false;
        //zorgen dat alle variabelen zijn gezet
if (isset($_SESSION["traject"]) && !isset( $_GET["beginPunt"]))
{
    $traject = $_SESSION["traject"];
    $_GET["beginPunt"] = $traject->Airport1->AirportName;
$_GET["eindPunt"] = $traject->Airport2->AirportName;
//zorgen dat alles is ingevuld
    if(isset($_GET["AirlineName"]) && isset($_GET["Zone"]) && validator::isInt($_GET["Zone"]))
    {
       $airlines = airline::get_airlines($_GET["AirlineName"],0,1);
       if(count($airlines) > 0)
       {
        trajecten::link_airport_to_traject($airlines[0],$traject,$_GET["Zone"]);
        $added= true;
        $_GET["beginPunt"] = $traject->Airport1->AirportName;
          $_GET["eindPunt"] = $traject->Airport2->AirportName;
       }
       else
       {$falseAirport = true;
//zo niet error weergeeven
               $_GET["beginPunt"] = $traject->Airport1->AirportName;
          $_GET["eindPunt"] = $traject->Airport2->AirportName; 
       }

    }
    else if(isset($_GET["CurrentAirlines"]) && validator::isInt($_GET["CurrentAirlines"]))
    {
    //vliegveld verwijderen
        trajecten::remove_linked_airport_traject($_GET["CurrentAirlines"],$traject);
    }
}
if (isset($_GET["beginPunt"]) && isset($_GET["eindPunt"]))
{
unset($traject);
 unset($_SESSION["traject"]);
 //zorgen bij nieuwe zoekactie dat alles weer wordt gezet en gecheked
    $beginAirport = airports::GetAirportByName($_GET["beginPunt"]);
    $endAirport = airports::GetAirportByName($_GET["eindPunt"]);

    // kijken of de vliegvelden bestaan
    if ($beginAirport != null && $endAirport != null)
    {

        //kijken of het niet de zelfde vliegvelden zijn.
        if ($beginAirport->AirportID != $endAirport->AirportID)
        {

            $traject = trajecten::get_traject_by_airportsid($beginAirport->AirportID, $endAirport->
                AirportID);
            if ($traject != null)
            {
                $airlinesList = trajecten::get_airlines_from_linked_traject($traject);
                $_SESSION["traject"] = $traject;
            } else
            {
                unset($_SESSION["traject"]);
            }
        }
    }

}

?>

<a href="trajecten.php">Traject aanmaken</a><br/>
<h1>Traject Koppelen</h1>
<p>Selecteer het traject</p>
<form action="trajectenAirline.php" method="get">
  <div class="ui-widget">
    <label for="beginPunt">Beginpunt: </label>
    <select name="beginPunt" id="beginPunt"> <?php
    //alle vliegvelden in een lijst zetten
$airports = airports::GetAirports();
for ($i = 0; $i < count($airports); $i++)
{
    if((isset($_GET['beginPunt']) && htmlspecialchars($_GET["beginPunt"]) == $airports[$i]->AirportName)||(isset($_SESSION["traject"])&&$_SESSION["traject"]->Airport1->AirportName ==$airports[$i]->AirportName) )
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
      //alle vliegvelden in een lijst zetten
$airports = airports::GetAirports();
for ($i = 0; $i < count($airports); $i++)
{
    if((isset($_GET['eindPunt']) && htmlspecialchars($_GET["eindPunt"]) == $airports[$i]->AirportName)||(isset($_SESSION["traject"])&&$_SESSION["traject"]->Airport2->AirportName ==$airports[$i]->AirportName) )
    {
         echo '<option selected="true" value="' . $airports[$i]->AirportName . '">'. $airports[$i]->AirportName . '('. $airports[$i]->AirportCity. ')'.'</option>';
        }
        else
        {
        echo '<option value="' . $airports[$i]->AirportName . '">'. $airports[$i]->AirportName . '('. $airports[$i]->AirportCity. ')'.'</option>';
}
}?>
    </select>
    <input id="submit" type="submit" value="Zoeken" />
    <br />
   <?php

if (isset($traject) == false)
{
    echo 'Er is geen traject tussen deze vliegvelden.<br/>U kunt deze <a href="trajecten.php">hier toevoegen.</a>';
}

?>
  </div>
</form>
<?php    if (isset($_SESSION["traject"])){?>
<form action="trajectenAirline.php" method="get">
  <label for="AirlineName">Vliegmaatschapij:</label>: </label>
<input name="AirlineName" id="AirlineName" value="<?php
//vorige ingevulde waarde terugzetten
if (isset($_GET["AirlineName"])&& !isset($added))
{
    echo htmlspecialchars($_GET["AirlineName"]);
}

?>"  />
<label for="Zone">Zone:</label>
<select id="Zone" name="Zone">
<option value="1">1</option>
<option value="2">2</option>
<option value="3">3</option>
<option value="4">4</option>
<option value="5">5</option></select>
<input type="submit" value="Toevoegen" /> 
<?php
//zorgen dat het bestaat
 if($falseAirport == true)
{
    echo "</br>Vliegmaatschapij bestaat niet.";
}?>
<input type="hidden" value="Toevoegen" id="actie" />

</form>

<form action="trajectenAirline.php" method="get">
<label for="CurrentAirlines">Huidige vliegtuigmaatschapijen op dit traject:<br /></label>
<select id="CurrentAirlines" name="CurrentAirlines" size="6" style="width:150px;" >
<?php
//opties toevoegen
if (isset($traject) && isset($airlinesList))
{
    foreach ($airlinesList as $air)
    {
        echo '<option value="' . $air->airline_id . '">' . htmlspecialchars($air->name) . '</option>';
    }
}

?>
</select>
<input type="submit" value="Verwijderen" /> 
<input type="hidden" value="Verwijderen" id="actie" />
</form>


<?php }?>
<script src="../js/jquery-1.9.0.min.js"></script>
<script src="../js/jquery-ui.js"></script>
<script src="../js/grid.locale-nl.js" type="text/javascript"></script>
<script src="../js/jquery.jqGrid.min.js" type="text/javascript"></script>
<script src="../js/javascript.js"></script>
<script type="text/javascript">
  $(function() {
    
    var availableairlines = [
    <?php
//typeahead klaar zetten
$airlines = airline::get_airlines();
for ($i = 0; $i < count($airlines); $i++)
{
    if ($i == count($airlines) - 1)
    {

        echo '"' . $airlines[$i]->name . '"';
    } else
    {
        echo '"' . $airlines[$i]->name . '"' . ",";
    }
}

?>
    ];
        $( "#AirlineName" ).autocomplete({
      source: availableairlines
    });
  });
  </script>
  
<?php

require_once ("onderkant.php");

?>
