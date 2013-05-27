<?php
//Alle data classes includen
require_once ("../data/includeAll.php");
$titel = "Speciale bagage";
require_once ("bovenkant.php");
?>
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
$name;
//Toevoegen van Specialluggage:
if (isset($_GET["action"])) {
    if ($_GET["action"] == "add") {
        if (!empty($_POST["name"])) {
            if (strlen($_POST["name"]) > 0 && strlen($_POST["name"]) < 51) {
                $name = $_POST["name"];
                $CheckIfExists = specialluggage::GetSpecialLuggageName($name);

                if ($CheckIfExists != null) {
                    $name = "Dit type bagage bestaat al!";
                } else {
                    $_POST["name"] = null;
                    $specialeBagage = specialluggage::AddItem(($name));
                }
            } else {
                $name = "Niet alle velden zijn correct ingevuld!";
            }
        } else {
            $name = "";
        }
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
        <form action="specialluggage.php?action.add" method="post" class="form">
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
                Specialebagage id: <?php echo $specialeBagage->specialluggage_id[0]["specialluggage_id"]; ?><br /> 
                Specialbagage naam: <?php echo $specialeBagage->name; ?>
            </div>
            <?php
    } else {
        echo $name;

    }

}
?>

<?php
require_once ("onderkant.php");
?>