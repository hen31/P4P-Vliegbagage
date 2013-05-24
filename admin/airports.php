<?php
//Alle data classes includen
require_once("../data/includeAll.php");
$titel = "Vliegvelden";
require_once("bovenkant.php");
?>
<!-- Hier alles neerzetten-->
<div id="menu">
    <ul>
        <li>
            <a<?php echo (isset($_GET["action"]) && $_GET["action"] == "add" ? ' class="active" ' : "")?> href="airports.php?action=add">Toevoegen</a>
        </li>
        <li>
            <a<?php echo (isset($_GET["action"]) && $_GET["action"] == "edit" ? ' class="active" ' : "")?> href="airports.php?action=edit">Beheren</a>
        </li>
        <li>
            <a<?php echo (isset($_GET["action"]) && $_GET["action"] == "Import" ? ' class="active" ' : "")?> href="airports.php?action=Import">Importeren</a>
        </li>
    </ul>
</div>
<div style="clear: both;"></div>

<?php
$AirportObject;
$name;

if (isset($_GET["action"]))
{
    if ($_GET["action"] == "add")
    {   
        if (!empty($_POST["name"]))
        {
            if (strlen($_POST["name"]) > 0 && strlen($_POST["name"]) < 101 && strlen($_POST["Iata"]) > 0 && strlen($_POST["Iata"]) < 5)
            {   
                $name = $_POST["name"] . " (" . $_POST["Iata"] . ")";
                $CheckIfExists = airports::GetAirportByName($name);
                
                if ($CheckIfExists != null)
                {       
                    $name = "Vliegveld bestaat al!";
                        
                }
                else
                {
                        $_POST["name"] = null;
                        $_POST["Iata"] = null;
                        $AirportObject = airports::AddItem(($name));
                }
            }
            else
            {
                $name  = "Niet alle velden zijn correct ingevuld!";
            }
        }
        else
        {
            $name = "";
        }
        
        ?>
        <br />
        <div>
            <table>
            <tr>
                <td>
                    <h1>Vliegveld toevoegen</h1><br />
                </td>
            </tr>
            </table>
            <br />
            <form action="airports.php?action=add" method="post" class="form">
                <div><label for="airportname">Vliegveld naam: </label><input name="name" id="airportname" /></div>
                <div><label for="airportIata">Vliegveld IATA code: </label><input name="Iata" id="IataCode" /></div>
                <div>&nbsp;</div>
                <div><label>&nbsp;</label><input type="submit" value="Vliegveld toevoegen"/></div>
            </form>
        </div>
        <br />
        
        <?php
        
        if (!empty($AirportObject))
        {
            ?>
            <br /><h2>Vliegveld toegevoegd!</h2><br /><br /> 
            Vliegveld id: <?php echo $AirportObject->AirportID[0]["airport_ID"]; ?><br /> 
            Vliegveld naam: <?php echo $AirportObject->AirportName;   
        }
        else
        {
            echo $name;
            
        }
    }
    if ($_GET["action"] == "edit")
    {
        $Airports = airports::GetAirports();
        $listnumber = 0;
        
        if (count($Airports) < 35)
        {
            $listnumber = count($Airports);
        }
        else
        {
            $listnumber = 35;
        }
        ?>
        <br />
        <table>
            <tr>
                <td>
                    <h1>Vliegveld bewerken</h1><br />
                </td>
            </tr>
            <tr>
                <td>
                    <table style="width: 880px;">
                        <tr>
                            <td>
                                <div style="width:380px;">
                                    <form action="airports.php?action=edit&ItemSelected=true" method="post" >
                                        <select name="Airports" multiple="true" size="<?php echo $listnumber; ?>;" style="width:350px;">
                                        <?php
                                        foreach($Airports as $Airport)
                                        {
                                            ?>
                                            <option value="<?php echo $Airport->AirportID; ?>"> <?php echo $Airport->AirportName; ?></option>
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
                                            if (isset($_GET["ItemSelected"]))
                                            {
                                                //checken of  er een airport is gelecteerd om te wijzigen
                                                if (isset($_POST["Airports"]))
                                                {
                                                        ?>
                                                            <div>
                                                                <h1>
                                                                    <?php
                                                                        $AirportObject = airports::GetAirportByID($_POST["Airports"]);
                                                                        echo $AirportObject->AirportName;
                                                                    
                                                                        $AirportName = explode("(",$AirportObject->AirportName);
                                                                        $IataCode = explode(")", $AirportName[1]);
                                                                        $Airportid = $_POST["Airports"];
                                                                        
                                                                        //verwijdercheckbox is scheef
                                                                    ?>
                                                                </h1>
                                                                <br />
                                                                <br /> 
                                                                <form action="airports.php?action=edit&Edited=<?php echo $Airportid; ?>" method="post" class="form">
                                                                    <div><label for="airportname">Vliegveld naam: </label><input name="name" id="airportname" value="<?php echo $AirportName[0]; ?>" style="width:325px;" /></div>
                                                                    <div><label for="airportIata">Vliegveld IATA code: </label><input name="Iata" id="IataCode" value="<?php echo $IataCode[0]; ?>" style="width:325px;" /></div>
                                                                    <div><label for="Verwijderen">Vliegveld verwijderen? </label><input type="checkbox" name="verwijderen" value="true"/></div>
                                                                    <div>&nbsp;</div>
                                                                    <div><label>&nbsp;</label><input type="submit" value="Vliegveld wijzigen"/></div>
                                                                </form> 
                                                            </div>
                                                        <?php
                                                }
                                                else
                                                {
                                                    echo "<h1>Fout!</h1><br />";
                                                    echo "Voor dat er op bewerken wordt geklikt moet er een item aan de linkerzijde worden geselecteerd";
                                                    
                                                }
                                            }
                                            else
                                            {
                                                //item moet worden aangepast in de database
                                                if (isset($_GET["Edited"]))
                                                {
                                                    if (strlen($_POST["name"]) > 0 && strlen($_POST["name"]) < 101 && strlen($_POST["Iata"]) > 0 && strlen($_POST["Iata"]) < 5)
                                                    {
                                                        $Verwijderen = "";
                                                        $name = $_POST["name"];
                                                        $Iata = $_POST["Iata"];
                                                        if (isset($_POST["verwijderen"]))
                                                        {
                                                            $Verwijderen = $_POST["verwijderen"];
                                                        }
                                                        $ItemID = $_GET["Edited"];
                                                        $FullName = ($name . " (" . $Iata . ")"); 
                                                        
                                                        if ($Verwijderen == "true")
                                                        {
                                                            airports::RemoveItem($ItemID);
                                                        }
                                                        else
                                                        {
                                                            airports::EditItem($ItemID, $FullName);
                                                        }
                                                    
                                                        echo "
                                                        <h1>
                                                        Bewerken succesvol.
                                                        </h1> 
                                                        <script>
                                                        windows.location = admin/airports.php?action=edit
                                                        </script>
                                                        <br />
                                                        ";
                                                    }
                                                    else
                                                    {
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
    if ($_GET["action"] == "Import")
    {
        if (!isset($_GET["Stap2"]))
        {
        ?>
        <br />
        <table>
            <tr>
                <td>
                    <h1>Vliegvelden Importeren</h1><br />
                </td>
            </tr>
             <tr>
                <td>
                    <table style="width: 880px;">
                        <tr>
                            <td>
                                <div style="width:380px;">
                                    <form action="airports.php?action=Import&Stap2=true" method="post"
                                        enctype="multipart/form-data">
                                        <label for="file">Filename:</label>
                                        <input type="file" name="file" id="file"/><br />
                                        <input type="submit" name="submit" style="width: 380;" value="Bestand importeren" />
                                    </form>
                                </div>
                            </td>
                            <td style="width: 499px;" >
                            test
                            </td>
                        </tr>
                    </table>
                </td>
             </tr>
        </table>
        
        <?php
        }
        else
        {
        ?>
            <table>
            <tr>
                <td>
                    <h1>Ingelezen vliegvelden:</h1><br />
                    <?php
                    if ($_FILES["file"]["error"] > 0)
                    {
                        echo "Error: " . $_FILES["file"]["error"] . "<br>";
                    }
                    else
                    {
                        echo "Upload: " . $_FILES["file"]["name"] . "<br>";
                        echo "Type: " . $_FILES["file"]["type"] . "<br>";
                        echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
                        echo "Stored in: " . $_FILES["file"]["tmp_name"];
                    }
                    ?>
                </td>
            </tr>
            </table>
          <?php  
        }
    }
}
else
{
    $name = "";
}
?>


<?php
require_once("onderkant.php");
?>