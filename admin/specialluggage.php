<?php

/**
 * @Auteur Ivar de Lange & Niels Riemersma
 * @Datum 27-5-2013 t/m 30-5-2013
 */
//Alle data classes includen
require_once ("../data/includeAll.php");
$titel = "Speciale bagage";
require_once ("bovenkant.php");
?>
<div>
<?php
//item moet worden aangepast in de database
if (isset($_GET["Edited"]) && !isset($_GET["ItemSelected"])) {
    $nietSuc6BestaatAl = false;
    if (strlen($_POST["name"]) > 0 && strlen($_POST["name"]) < 51) {
        $Verwijderen = "";
        $Name = $_POST["name"];
        if (isset($_POST["verwijderen"])) {
            $Verwijderen = $_POST["verwijderen"];
        }
        $EditSpecialLuggage = $_GET["Edited"];
        if ($Verwijderen == "true") {
            SpecialLuggage::RemoveSpecialLuggage($EditSpecialLuggage); //verwijderen bagage
        } else {
            $CheckIfExists = specialluggage::GetSpecialLuggageName($Name);
            if ($CheckIfExists == null) {
                SpecialLuggage::EditItem($EditSpecialLuggage, $Name); //wijzigen naam bagage
            } else {
                $nietSuc6BestaatAl = true;
            }
        }
    }
}
?>
<div id="menu">
    <ul>
        <li>
            <a<?php echo (isset($_GET["action"]) && $_GET["action"] == "add" ?
' class="active" ' : "") ?> href="specialluggage.php?action=add">Toevoegen</a>
            <a<?php echo (isset($_GET["action"]) && $_GET["action"] == "edit" ?
    ' class="active" ' : "") ?> href="specialluggage.php?action=edit">Beheren</a>   
    <a href="specialluggageAirline.php">Koppelen aan luchtvaartmaatschappij</a>
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
                    $name = "Dit type bagage bestaat al!"; // kijken of bagage al bestaat
                } else {
                    $_POST["name"] = null;
                    $specialeBagage = specialluggage::AddItem(null, ($name), null);
                    $name = "Speciale bagage is toegevoegd!"; // bagage toevoegen
                }
            } else {
                $name = "Niet alle velden zijn correct ingevuld!"; // controle of er iets ingevoerd is
            }
        } else {
            $name = "";
        }
?>
        <br />
        <table>
        <tr>
        <td>
        <h1 style="margin-left: 20px;"> Speciale bagage toevoegen</h1><br />
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
            specialluggage_id; ?><br /> 
                Specialbagage naam: <?php echo $specialeBagage->name; ?>
            </div>
            <?php
        } else {
            echo $name;
        }
    }
    //Zoek opties naar bagage in lijst.
    //Lijst aanmaken met bagage
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
                                    <form action="specialluggage.php?action=edit&ItemSelected=true" method="post" >
                                    
                                        <select name="Specialluggage" multiple="true" size="<?php echo
        $listnumber; ?>;" style="width:350px;">
                                        <?php
        foreach ($Specialluggage as $Special) {
?>
                                                <option value="<?php echo $Special->
            specialluggage_id; ?>"> <?php echo $Special->Name; ?></option>
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
                $Specialluggage = specialluggage::GetSpecialLuggageID($_POST["Specialluggage"]);

                if ($Specialluggage != null) {
                    echo $Specialluggage->Name;

                    $specialluggage_id = $_POST["Specialluggage"]; //List en textbox Id van bagage koppelen.
                }
?>
                                                                </h1>
                                                                <br />
                                                                <br /> 
                                                                <form action="specialluggage.php?action=edit&Edited=<?php echo
                $specialluggage_id; ?>" method="post" class="form">
                                                                    <div><label for="specialluggagename">Speciale bagage naam: </label><input name="name" id="specialluggagename" value="<?php echo
                $Specialluggage->Name; ?>" style="width:325px;" /></div>
                                                                    <div><label for="Verwijderen">Bagage verwijderen? </label></div>
                                                                    <input type="checkbox" name="verwijderen" value="true"/></div>
                                                                    <div>&nbsp;</div>
                                                                    <div><label>&nbsp;</label><input type="submit" value="Speciale bagage wijzigen"/></div>
                                                                </form> 
                                                            </div>                                                            
                                                        <?php
            } else {
                if (isset($_GET["zoekQuery"])) {
                    echo "<h1>Er is gezocht op: " . $_POST["Zoekveld"] . "</h1><br />";
                    echo "Stappen om speciale bagage te bewerken of te verwijderen: <br /><br />";
                    echo "Stap 1: Selecteer speciale bagage in de linkerlijst. <br />Stap 2: Klik op bewerken om speciale bagage te bewerken.";
                } else {
                    echo "<h1>Fout!</h1><br />";
                    echo "Voor dat er op bewerken wordt geklikt moet er een item aan de linkerzijde worden geselecteerd";
                }
            }
        } else {
            //Item wordt aangepast in de database
            if (isset($_GET["Edited"])) {
                if (strlen($_POST["name"]) > 0 && strlen($_POST["name"]) < 51 && $nietSuc6BestaatAl == false) {
                    $Verwijderen = "";
                    $Name = $_POST["name"];
                    if (isset($_POST["verwijderen"])) {
                        $Verwijderen = $_POST["verwijderen"];
                    }
                    $EditSpecialLuggage = $_GET["Edited"];


                    echo "
                                                        <h1>
                                                        Bewerken succesvol.
                                                        </h1> 
                                                        <script>
                                                        windows.location = admin/specialluggage.php?action=edit
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
                                                        
                                                        *Speciale bagage naam mag niet langer zijn dan 50 tekens.<br />
                                                        ";
                }
            }
            echo "Stappen om speciale bagage te bewerken of te verwijderen: <br /><br />";
            echo "Stap 1: Selecteer speciale bagage in de linkerlijst. <br />Stap 2: Klik op bewerken om speciale bagage te bewerken.";
        }
    }
} else {
?>
    <table>
            <tr>
                <td><br />
                    <h1 style="margin-left: 20px;">Administratie voor Speciale bagage.</h1><br /><br />
                    <div style="margin-left: 20px; ">
                        Gebruik het menu om speciale bagage toe te voegen en bewerken.
                    </div>
                    
                </td>
            </tr>
            </table>
            <?php
}
?>                                
                            </td>
                        </tr>    
                    </table>
                
                </td>
            </tr>   
                  
        </table>  

<?php
echo "</div>";
require_once ("onderkant.php");
?>