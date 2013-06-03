<?php
//Alle data classes includen
require_once ("../data/includeAll.php");

$fatalerror = false;
$submitUser = null;


$titel = "Gebruikers";
require_once ("bovenkant.php");

if(isset($_GET['id']))
{
    if(isset($_GET['actie'])&& $_GET['actie'] == 'Delete')
    {
        user::deleteUser($_GET['id']);
    }
  
    $submitUser = user::GetUser($_GET['id']);
    
    //Check of submit user niet leeg is
    if($submitUser != null)
    {
    $_SESSION["user_id"] =  $_GET['id'];
    }
 
}
?>
<div id="menu">
    <ul>
        <li>
            <a<?php echo (isset($_GET["action"]) && $_GET["action"] == "add" ?
    ' class="active" ' : "") ?> href="users.php?action=add">Toevoegen</a>
        </li>
        <li>
            <a<?php echo (isset($_GET["action"]) && $_GET["action"] == "edit" ?
    ' class="active" ' : "") ?> href="users.php?action=edit">Beheren</a>
        </li>
    </ul>
</div>
<div style="clear: both;"></div>

<?php
//Gebruiker toevoegen veld
if (isset($_GET["action"])) 
{
    if ($_GET["action"] == "add") 
    {
        if (isset($_POST['addUser']))
         {
            if($_POST['password'] == $_POST['passControle'])
            {
                if (!empty($_POST['username']) && !empty($_POST['password'])) {                    
                    $user = user::createUser($_POST["username"], $_POST["password"]);
                } else {
                    
                }
            }
            else
            {
                echo "Wachtwoorden komen niet overeen!";
            }
        }
    
?>

<br />
        <div>
            <table>
            <tr>
                <td>
                    <h1>Gebruiker toevoegen</h1><br />
                </td>
            </tr>
            </table>
            <br />
            <form action="users.php?action=add" method="post" class="form">
                <div><label for="Gebruikersnaam">Gebruikersnaam: </label><input name="username" id="username" /></div>
                <div><label for="Wachtwoord">Wachtwoord: </label><input name="password" id="password" type="password"></div>
                <div><label for="Controle">Wachtwoord controle: </label><input name="passControle" id="password" type="password"></div>
                <div>&nbsp;</div>
                <div><label>&nbsp;</label><input type="submit" name="addUser" value="Nieuwe gebruiker"/></div>
            </form>
        </div>
        <br />
        
<?php
    }
    if ($_GET["action"] == "edit") {
        $users = user::GetUsers('');

        echo "<table>";
        if ($users != null) 
        {
            foreach ($users as $user) 
            {
                //Table waar gebruikers in worden getoond.
                echo '<tr><td>' . $user->userName .
                    '</td><td><a href="users.php?actie=Change&id=' . $user->id .
                    '&action=edit">Bewerken</a></td><td><a href="users.php?actie=Delete&id=' . $user->id .
                    '&action=edit">Verwijderen</a></td></tr>';
            }
        }
        echo "</table>";
        
        //Stuk code voor veranderen van de gebruiker
        if (isset($_POST['changeUser'])) 
        {
            //Check of oude gebruikdersnaam en oude wachtwoord overeen komen.
            if (user::checkUser($_POST["oldUser"], user::incryptPass($_POST["oldPass"]))) 
            {
                if(!empty($_SESSION["user_id"]))
                {
                //Check of de textboxes niet leeg zijn
                    if(!empty($_POST['newUser']) && !empty($_POST['newPass']))
                    {
                    $changeCheck = user::changeUser($_SESSION["user_id"] , ($_POST["newUser"]), $_POST["newPass"]);
                    unset($_SESSION["user_id"]);
                        if($changeCheck)
                        {
                            echo 'Gebruiker gewijzigd';
                        }
                        else
                        {
                            echo 'Gebruikersnaam bestaat al';
                        }    
                
                    }
                    else
                    {
                    //De tekst 'Er is een leeg veld' wordt geprint
                        echo 'Er is een leeg veld';
                    }
                }
                else
                {
                    echo 'Druk op bewerken bij een gebruiker';
                }
            }
            else
            {
                echo "Niet alles is ingevuld";
            }
        }
?>

<br />
<div>
    <table>
        <tr>
            <td>
                <h1>Gebruiker wijzigen</h1><br />
            </td>
        </tr>
    </table>
<br />
        <form action="users.php?action=edit" method="post" class="form">
            <div><label for="Oude gebruikersnaam">Oude gebruikersnaam: </label><input name="oldUser" id="oldUser" 
            value="<?php echo isset($submitUser)  ?  $submitUser->userName : ''?>" /></div>
            <div><label for="Oud wachtwoord">Oud wachtwoord: </label><input name="oldPass" id="oldPass" type="password"></div>
            <div><label for="Nieuwe gebruikersnaam">Nieuwe gebruikersnaam: </label><input name="newUser" id="newUser" /></div>
            <div><label for="Nieuw Wachtwoord">Nieuw wachtwoord: </label><input name="newPass" id="newPass" type="password"></div>
            <div>&nbsp;</div>
            <div><label>&nbsp;</label><input type="submit" name="changeUser" value="Verander gebruiker"/></div>
        </form>            
</div>
<br />


<?php
}
}
if ($fatalerror) 
{
echo "Niet alle velden zijn ingevuld.";
}
require_once ("onderkant.php");
?>