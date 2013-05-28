<?php
//Alle data classes includen
require_once ("../data/includeAll.php");
$titel = "Speciale bagage";
require_once ("bovenkant.php");
?>
<div>
<div id="menu">
    <ul>
        <li>
            <a<?php echo (isset($_GET["action"]) && $_GET["action"] == "add" ?
' class="active" ' : "") ?> href="specialluggage.php?action=add">Toevoegen</a>
            <a<?php echo (isset($_GET["action"]) && $_GET["action"] == "edit" ?
    ' class="active" ' : "") ?> href="specialluggage.php?action=edit">Beheren</a>
        </li>
    </ul>
</div>
<div style="clear: both;"></div>

<?php
$specialeBagage;

//Toevoegen van Specialluggage:
if (isset($_GET["action"])) {
    if ($_GET["action"] == "add") {
        $name;
        if (!empty($_POST["name"])) {
            if (strlen($_POST["name"]) > 0 && strlen($_POST["name"]) < 51) {
                $name = $_POST["name"];
                $CheckIfExists = specialluggage::GetSpecialLuggageName($name);

                if ($CheckIfExists != null) {
                    $name = "Dit type bagage bestaat al!";
                } else {
                    $_POST["name"] = null;
                    $specialeBagage = specialluggage::AddItem(null, ($name), null);
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
        <h1 style="margin-left: 20px;"> Speciale Bagage toevoegen</h1><br />
        </td>
        </tr>
        </table>
        <form action="specialluggage.php?action=add" method="post" class="form">
            <div><label for="specialluggagename">Speciale bagage naam: </label><input name="name" id="specialluggagename" /></div>
            <div>&nbsp;</div>
            <div><label>&nbsp;</label><input type="submit" value="Speciale bagage toevoegen"/></div>
        </form>
        <br />
        
        <?php


        if (!empty($specialeBagage)) {
?>
            <div style="margin-left: 20px;">
                <br /><h1>Speciale bagage toegevoegd!</h1><br /><br /> 
                Specialebagage id: <?php echo $specialeBagage->
            specialluggage_id[0]["specialluggage_id"]; ?><br /> 
                Specialbagage naam: <?php echo $specialeBagage->name; ?>
            </div>
            <?php
        }
        else
        {
            echo $name;
        }

    }
    if ($_GET["action"] == "edit") {
        if (isset($_GET["zoekQuery"])) {
            $Specialluggage = specialluggage::SearchSpecialLuggage($_POST["Zoekveld"]);
            $listnumber = 0;

        } else {
            $Specialluggage = specialluggage::GetSpecialLuggageList();
            $listnumber = 0;
        }
        if (count($Specialluggage) < 35) {
            if (count($Specialluggage) < 15) {
                $listnumber = 15;
            } else {
                $listnumber = count($Specialluggage);
            }
        } else {
            $listnumber = 35;
        }
?>
        <br />
        <table>
            <tr>
                <td>
                    <h1 style="margin-left: 20px;">Speciale bagage bewerken</h1><br />
                </td>
            </tr>
            <tr>
                <td>
                    <table style="width: 880px;">
                        <tr>
                            <td>
                            
                                <div style="width:380px;">
                                    <form action="specialluggage.php?action=edit&ItemSelected=true&zoekQuery=true" method="post" >
                                            <div><input name="Zoekveld" id="Zoekveld" style="width:250px;" /><input type="submit" style="width:100px;" value="Zoeken"/></div>
                                            
                                    </form>
                                    
                                    <form action="speciallagguge.php?action=edit&ItemSelected=true" method="post" >
                                        <select name="Specialluggage" multiple="true" size="<?php echo
        $listnumber; ?>;" style="width:350px;">
                                        <?php
        foreach ($Specialluggage as $Specialluggage) {
?>
                                                <option value="<?php echo $Specialluggage->
            Specialluggage_id; ?>"> <?php echo $Specialluggage->Name; ?></option>
                                                <?php
        }

?>
                                        </select>
                                        <input type="submit" style="width:350px;" value="Geselecteerd Speciale bagage bewerken"/></div>
                                    </form>
                                    
                                 </div>
                            </td>
                            <td style="width: 499px;" >
                                <div style="height: <?php echo $listnumber * 15; ?>  px; width: *%;">
<?php

        if (isset($_GET["ItemSelected"])) {
            //checken of  er een airport is gelecteerd om te wijzigen
            if (isset($_POST["Specialluggage"])) {
?>
                                                            <div>
                                                                <h1>
                                                                <?php
                $specialeBagage = specialluggae::GetSpecialLuggageID($_POST["Specialluggage"]);
                echo $specialeBagage->Name;

                $Name = explode("(", $specialeBagage->Name);


                $specialluggage_id = $_POST["Specialeluggage"];

                //verwijdercheckbox is scheef

?>
                                                                </h1>
                                                                <br />
                                                                <br /> 
                                                                <form action="specialluggage.php?action=edit&Edited=<?php echo
                $Airportid; ?>" method="post" class="form">
                                                                    <div><label for="specialluggagename">Speciale bagage naam: </label><input name="name" id="specialluggagename" value="<?php echo
                $AirportName; ?>" style="width:325px;" /></div>
                                                                    
                                                                    <div><label for="Verwijderen">Speciale bagage verwijderen? </label><input type="checkbox" name="verwijderen" value="true"/></div>
                                                                    <div>&nbsp;</div>
                                                                    <div><label>&nbsp;</label><input type="submit" value="Speciale bagage wijzigen"/></div>
                                                                </form> 
                                                            </div>
                                                        <?php
            } else {
                if (isset($_GET["zoekQuery"])) {
                    echo "<h1>Er is gezocht op: " . $_POST["Zoekveld"] . "</h1><br />";
                    echo "Stappen om een vliegveld te bewerken of te verwijderen: <br /><br />";
                    echo "Stap 1: Selecteer een vliegveld in de linkerlijst. <br />Stap 2: Klik op bewerken om een vliegveld te bewerken.";
                } else {
                    echo "<h1>Fout!</h1><br />";
                    echo "Voor dat er op bewerken wordt geklikt moet er een item aan de linkerzijde worden geselecteerd";
                }
            }
        } else {
            //item moet worden aangepast in de database
            if (isset($_GET["Edited"])) {
                if (strlen($_POST["name"]) > 0 && strlen($_POST["name"]) < 101) {
                    $Verwijderen = "";
                    $name = $_POST["name"];
                    if (isset($_POST["verwijderen"])) {
                        $Verwijderen = $_POST["verwijderen"];
                    }
                    $ItemID = $_GET["Edited"];

                    if ($Verwijderen == "true") {
                        airports::RemoveItem($ItemID);
                    } else {
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
    }
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
require_once ("onderkant.php");
?>