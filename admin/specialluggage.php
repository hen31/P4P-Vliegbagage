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
if (isset($_GET["action"]))
{ 
    if ($_GET["action"] == "add")
    {
        if (!empty($_POST["name"]))
        {
            if (strlen($_POST["name"]) > 0 && strlen ($_POST["name"]) < 51 )
            {
                $name = $_POST["name"];
                $CheckIfExists = specialluggage::GetSpecialLuggageName($name);
                
                if ($CheckIfExists != null)
                {
                    $name = "Dit type bagage bestaat al!"; 
                }
                else
                {
                    $_POST["name"] = null;
                    $specialeBagage = specialluggage::AddItem(($name));
                }
            }
        }
    }
}

?>
<?php
require_once ("onderkant.php");
?>