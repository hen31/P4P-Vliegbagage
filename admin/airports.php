<?php
//Alle data classes includen
require_once ("../data/includeAll.php");
$titel = "Vliegvelden";
require_once ("bovenkant.php");
//<!-- Hier alles neerzetten-->


if ($_GET["action"] == "edit") 
{
    if (isset($_GET["Edited"])) 
                {
                    
                    $Verwijderen = "";
                    $name = $_POST["name"];
                    $Iata = $_POST["Iata"];
                    $City = $_POST["airportCity"];
                    if (isset($_POST["verwijderen"])) {
                        $Verwijderen = $_POST["verwijderen"];
                    }
                    $ItemID = $_GET["Edited"];
                    $FullName = ($name . " (" . $Iata . ")");
                    
                    if ($Verwijderen == "true") 
                    {
                        airports::RemoveItem($ItemID);
                    } 
                    else {
                        airports::EditItem($ItemID, $FullName, $City);
                    }
                    
                }
                
                
                    
}    
?>
<div id="menu">
    <ul>
        <li>
            <a<?php echo (isset($_GET["action"]) && $_GET["action"] == "add" ?
' class="active" ' : "") ?> href="airports.php?action=add">Toevoegen</a>
        </li>
        <li>
            <a<?php echo (isset($_GET["action"]) && $_GET["action"] == "edit" ?
    ' class="active" ' : "") ?> href="airports.php?action=edit">Beheren</a>
        </li>
        <li>
            <a<?php echo (isset($_GET["action"]) && $_GET["action"] == "Import" ?
    ' class="active" ' : "") ?> href="airports.php?action=Import">Importeren</a>
        </li>
    </ul>
</div>
<div style="clear: both;"></div>

<?php
$AirportObject;
$name;
$city;

//Toevoegen gedeelte:
if (isset($_GET["action"])) {
    if ($_GET["action"] == "add") {
        if (!empty($_POST["name"])) {
            if (strlen($_POST["name"]) > 0 && strlen($_POST["name"]) < 101 && strlen($_POST["Iata"]) >
                0 && strlen($_POST["Iata"]) < 5 && strlen($_POST["City"]) > 0 && strlen($_POST["City"]) < 101){
                    
                $name = $_POST["name"] . " (" . $_POST["Iata"] . ")";
                $city = $_POST["City"];
                          
                $CheckIfExists = airports::GetAirportByName($name);

                if ($CheckIfExists != null) {
                    $name = "Vliegveld bestaat al!";

                } else {
                    $_POST["name"] = null;
                    $_POST["Iata"] = null;
                    $AirportObject = airports::AddItem($name, $city);
                }
            } else {
                $name = "Niet alle velden zijn correct ingevuld!";
            }
        } else {
            $name = "";
        }

?>
        <br />
            <table>
            <tr>
                <td>
                    <h1 style="margin-left: 20px;">Vliegveld toevoegen</h1><br />
                </td>
            </tr>
            </table>
            <form action="airports.php?action=add" method="post" class="form">
                <div><label for="airportname">Vliegveld naam: </label><input name="name" id="airportname" /></div>
                <div><label for="airportIata">Vliegveld IATA code: </label><input name="Iata" id="IataCode" /></div>
                <div><label for="City">Plaatsnaam </label><input name="City" id="City" /></div>
                <div>&nbsp;</div>
                <?php

        if (!empty($AirportObject)) {
?>          
            <div><label for="airportAdded">Vliegveld toegevoegd: <?php echo $AirportObject->AirportName . "in " . $AirportObject->AirportCity; ?></label></div><br /><br />
            <?php
        } else {
            echo $name;

        }
        ?>
                <div><label>&nbsp;</label><input type="submit" value="Vliegveld toevoegen"/></div>
            </form>
        <br />
        
    <?php
    }
    //Beheer gedeelte:
    if ($_GET["action"] == "edit") {
        if (isset($_GET["zoekQuery"] ))
        {   
            $Airports = airports::SearchAirports($_POST["Zoekveld"]);
            $listnumber = 0;
            
        }
        else
        {
            $Airports = airports::GetAirports();
            $listnumber = 0;
        }
        
        if (count($Airports) < 35) {
            if (count ($Airports) < 15) {
                $listnumber = 15;
            }
            else {
                $listnumber = count($Airports);
            }
        } else {
            $listnumber = 35;
        }
        
?>
        <br />
        <table>
            <tr>
                <td>
                    <h1 style="margin-left: 20px;">Vliegveld bewerken</h1><br />
                </td>
            </tr>
            <tr>
                <td>
                    <table style="width: 880px;">
                        <tr>
                            <td>
                                <div style="width:380px;">
                                    <form action="airports.php?action=edit&ItemSelected=true&zoekQuery=true" method="post" >
                                            <div><input name="Zoekveld" id="Zoekveld" style="width:250px;" /><input type="submit" style="width:100px;" value="Zoeken"/></div>
                                            
                                    </form>
                                    
                                    <form action="airports.php?action=edit&ItemSelected=true" method="post" >
                                        <select name="Airports" multiple="true" size="<?php echo
                                            $listnumber; ?>;" style="width:350px;">
                                        <?php
                                                foreach ($Airports as $Airport) {
                                                                                ?>
                                                <option value="<?php echo $Airport->
                                                AirportID; ?>"> <?php echo $Airport->AirportName; ?></option>
                                                <?php
                                            }

                                                ?>
                                        </select>
                                        <input type="submit" style="width:350px;" value="Geselecteerd vliegveld bewerken"/></div>
                                    </form>
                                 </div>
                            </td>
                            <td style="width: 499px;" >
                                <div style="height: <?php echo $listnumber * 15; ?>px; width: *%;">
                                        <?php
        if (isset($_GET["ItemSelected"])) {
            //checken of  er een airport is gelecteerd om te wijzigen
            if (isset($_POST["Airports"])) {
?>
                                                            <div>
                                                                <h1>
                                                                    <?php
                $AirportObject = airports::GetAirportByID($_POST["Airports"]);
                echo $AirportObject->AirportName;
                
                $AirportName = explode("(", $AirportObject->AirportName);
                
                $IataCode = $AirportName[Count($AirportName) - 1];
                unset($AirportName[(Count($AirportName) - 1)]);
                $AirportName = implode("(", $AirportName);
                
                $IataCode = explode(")", $IataCode);
                
                $Airportid = $_POST["Airports"];
                $AirportCity = $AirportObject->AirportCity;
                //verwijdercheckbox is scheef


?>
                                                                </h1>
                                                                <br />
                                                                <br /> 
                                                                <form action="airports.php?action=edit&Edited=<?php echo
                $Airportid; ?>" method="post" class="form">
                                                                    <div><label for="airportname">Vliegveld naam: </label><input name="name" id="airportname" value="<?php echo
                $AirportName; ?>" style="width:325px;" /></div>
                                                                    <div><label for="airportIata">Vliegveld IATA code: </label><input name="Iata" id="IataCode" value="<?php echo
                $IataCode[0]; ?>" style="width:325px;" /></div>
                                                                    <div><label for="airportCity">Vliegveld plaats</label><input name="airportCity" id="airportCity" value="<?php echo
                $AirportCity; ?>" style="width:325px;" /></div>
                                                                    <div><label for="Verwijderen">Vliegveld verwijderen? </label><input type="checkbox" name="verwijderen" value="true"/></div>
                                                                    <div>&nbsp;</div>
                                                                    <div><label>&nbsp;</label><input type="submit" value="Vliegveld wijzigen"/></div>
                                                                </form> 
                                                            </div>
                                                        <?php
            } else {
                if (isset($_GET["zoekQuery"]))
                {
                     echo "<h1>Er is gezocht op: " . $_POST["Zoekveld"] . "</h1><br />";
                     echo "Stappen om een vliegveld te bewerken of te verwijderen: <br /><br />";
                     echo "Stap 1: Selecteer een vliegveld in de linkerlijst. <br />Stap 2: Klik op bewerken om een vliegveld te bewerken.";
                }
                else
                {
                    echo "<h1>Fout!</h1><br />";
                    echo "Voor dat er op bewerken wordt geklikt moet er een item aan de linkerzijde worden geselecteerd";
                }
            }
        } else {
            //item moet worden aangepast in de database
            if (isset($_GET["Edited"])) {
                if (strlen($_POST["name"]) > 0 && strlen($_POST["name"]) < 101 && strlen($_POST["Iata"]) >
                    0 && strlen($_POST["Iata"]) < 5 && strlen($_POST["airportCity"]) > 0 && strlen($_POST["airportCity"]) < 101) {
                   
                    /*
                    $Verwijderen = "";
                    $name = $_POST["name"];
                    $Iata = $_POST["Iata"];
                    $City = $_POST["airportCity"];
                    if (isset($_POST["verwijderen"])) {
                        $Verwijderen = $_POST["verwijderen"];
                    }
                    $ItemID = $_GET["Edited"];
                    $FullName = ($name . " (" . $Iata . ")");

                    if ($Verwijderen == "true") {
                        airports::RemoveItem($ItemID);
                    } else {
                        airports::EditItem($ItemID, $FullName, $City);
                    }
                    */
                    echo "
                                                        <h1>
                                                        Bewerken succesvol.
                                                        </h1> 
                                                        <script>
                                                        windows.location = admin/airports.php?action=edit
                                                        </script>
                                                        <br />
                                                        ";
                } else {
                    echo "
                                                        <h1>
                                                        Bewerken niet succesvol. <br /><br />
                                                        </h1>
                                                        
                                                        Niet alle velden zijn juist ingevuld. <br /><br />
                                                        Denk om het volgende:<br />
                                                        
                                                        *Vliegveld naam mag niet langer zijn dan 100 tekens.<br />
                                                        *IATA code mag niet langer zijn dan 4 tekens.<br /><hr /><br />
                                                        ";
                }


            }
            echo "Stappen om een vliegveld te bewerken of te verwijderen: <br /><br />";
            echo "Stap 1: Selecteer een vliegveld in de linkerlijst. <br />Stap 2: Klik op bewerken om een vliegveld te bewerken.";
        }
?>
                                </div>
                            </td>
                        </tr>    
                    </table>
                
                </td>
            </tr>        
        </table>
        <?php
    }
    //Importeer gedeelte:
    if ($_GET["action"] == "Import") {
        if (!isset($_GET["Stap2"])) {
?>
        <br />
        <table>
            <tr>
                <td>
                    <h1 style="margin-left: 20px;">Vliegvelden Importeren</h1><br />
                </td>
            </tr>
             <tr>
                <td>
                    <table style="width: 880px;">
                        <tr>
                            <td>
                                <div style="width:380px; height:395px;">
                                    <form action="airports.php?action=Import&Stap2=true" method="post"
                                        enctype="multipart/form-data">
                                        <label for="file">Bestand:</label>
                                        <input type="file" name="file" id="file"/><br />
                                        <input type="submit" name="submit" style="width: 295px;" value="Bestand importeren" />
                                    </form>
                                </div>
                            </td>
                            <td style="width: 499px;" >
                            <h1>Handleiding vliegvelden importeren.</h1><br /><br />
                            Met deze pagina kan er op een snellere mannier vliegvelden worden toegevoegd doormiddel van een vooraf gemaakt tekst bestand. <br /><br />
                            
                            - Stap 1: Maak in het windows programma kladblok een bestand aan met vliegvelden. Vliegvelden moeten als volgt in het tekstbestand staan: <br /><br />
                            &nbsp; &nbsp; Amsterdam Airport Schiphol (AMS), Amsterdam <br />
                            &nbsp; &nbsp; Bangkok international Airport (BKK), Bangkok <br />
                            &nbsp; &nbsp; London Heathrow Airport (LHR), Londen <br /><br />
                            Enzovoort. <br /><br />
                            <br />
                            - Stap 2: Upload de lijst door op bladeren te klikken en het bestand te selecteren. Klik daarna op Bestand importeren. <br /><br />
                            - Stap 3: Controleer de geimporteerde lijst die op het scherm wordt getoont. Als alles klopt klik dan op "Vliegvelden inlezen".<br /><br />
                            De vliegvelden staan nu in de database.
                            </td>
                        </tr>
                    </table>
                </td>
             </tr>
        </table>
        
        <?php
        }
        if (isset($_GET["Stap2"]) && !(isset($_GET["Stap3"]))) {
?>
            <br />
            <table>
            <tr>
                <td>
                    <h1 style="margin-left: 20px;">Vliegvelden in het verstuurde tekst bestand:</h1><br />
                    <br />
                    <?php
            if ($_FILES["file"]["error"] > 0) {
                echo "Error: " . $_FILES["file"]["error"] . "<br>";
            } else {

                $myFile = $_FILES["file"]["tmp_name"];
                $file = file_get_contents($myFile);
                $lines = explode("\n", $file);

                $InleesArray = array();

                foreach ($lines as $line) {
                    if (strpos($line, '(') != false && (strpos($line, ')') != false) && strlen($line) >
                        4 && strlen($line < 105)&& (strpos($line, ',') != false)) {
                        array_push($InleesArray, $line);
                    }
                }
                $teller = 0;
?>
                            
                            <div style="width:380px;">
                                    <form action="airports.php?action=Import&Stap2=true&Stap3=true" method="post">
                                        <select name="Airports" multiple="true" size="25" style="width:840px;">
                                        <?php
                foreach ($InleesArray as $line) {
                    $teller = $teller + 1;
?>
                                                <option value="<?php echo $teller; ?>"> <?php echo
                    $line; ?></option>
                                                <?php
                }

                $_SESSION['Lines'] = $InleesArray;

?>
                                        </select>
                                        <input type="submit" style="width:840px;" value="Vliegvelden inlezen"/></div>
                                    </form>
                                 </div>
                            <?php
            }

?>
                </td>
            </tr>
            </table>
          <?php
        }
        if (isset($_GET["Stap3"])) {
?>
            <br />
            <table>
            <tr>
                <td>
                    <h1 style="margin-left: 20px;">Inlezen succesvol:</h1><br />
            <?php
            $counter = 0;

            if (isset($_SESSION['Lines'])) {
                foreach ($_SESSION['Lines'] as $line) {
                    $counter = $counter + 1;
                    $StringLineAray = explode(',', $line);
                    $City = $StringLineAray[Count($StringLineAray) - 1];
                    unset($StringLineAray[Count($StringLineAray) - 1]);
                    $Name = implode(',', $StringLineAray);
                    
                    airports::AddItem($Name, $City);
                }
            }

            echo $counter . " vliegvelden ingelezen.";
?>
                </td>
            </tr>
            </table>
            <?php
        }
    }
} else {
?>
        <br />
            <table>
            <tr>
                <td>
                    <h1 style="margin-left: 20px;">Administratie gedeelte vliegvelden.</h1><br /><br />
                    <div style="margin-left: 20px; ">
                        Gebruik het menu om een vliegveld toe te voegen te bewerken of vliegvelden in te lezen.
                    </div>
                    
                </td>
            </tr>
            </table>
            <?php

    $name = "";
}
?>


<?php
require_once ("onderkant.php");
?>