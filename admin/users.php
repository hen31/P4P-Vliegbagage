<?php
//Alle data classes includen
require_once ("../data/includeAll.php");
/**
 * @Auteur Matthé Jacobs
 * @Datum 13-5-2013
 * @uses in deze class zitten alle database code voor het maken, wijzigen en verwijderen.
 */
$text1 = ' ';
$text2 = ' ';

$fatalerror = false;
$submitUser = null;


$titel = "Gebruikers";
require_once ("bovenkant.php");

if (isset($_POST['editUsername'])) {
    //Check of oude gebruikdersnaam en oude wachtwoord overeen komen.
    //Check of de textboxes niet leeg zijn
    if (!empty($_SESSION['user_id'])) {
        if (!empty($_POST['newUser'])) {
            if (!user::UsernameExists($_POST['newUser'])) {
                if (trim($_POST['newUser']) == '') {
                    $text1 = 'Gebruikersnaam mag niet uit alleen spaties bestaan';
                } else {
                    $changeCheck = user::changeUser($_SESSION['user_id'], $_POST['newUser'], null);
                    unset($_SESSION['user_id']);
                    $text1 = 'Gebruikersnaam gewijzigd';
                }
            } else {
                $text1 = 'Nieuwe gebruikersnaam bestaat al';
            }
        } else {
            $text1 = '1 of meer velden zijn niet ingevuld';
        }
    }
}

if (isset($_POST['editPassword'])) {
    if (!empty($_SESSION['user_id'])) {
        if (!empty($_POST['newPass'])) {
            $changeCheck = user::changeUser($_SESSION['user_id'], null, $_POST['newPass']);
            unset($_SESSION['user_id']);
            if ($changeCheck) {
                $text1 = 'Wachtwoord gewijzigd';
            } else {
                $text1 = 'Wachtwoord wijzigen mislukt';
            }
        }
    } else {
        $text1 = '1 of meer velden zijn leeg';
    }
}

if (isset($_GET['action']) && isset($_GET['actie']) && $_GET['actie'] ==
    'Delete') {
    $check = user::deleteUser($_GET['id']);
    if ($check) {
        $text1 = 'Gebruiker verwijderd';
    } else {
        $text1 = 'Gebruiker kan niet verwijderd worden. Moet minstens 1 gebruiker blijven bestaan';
    }
}

if (isset($_GET['id'])) {


    $submitUser = user::GetUser($_GET['id']);

    //Check of submit user niet leeg is
    if ($submitUser != null) {
        $_SESSION["user_id"] = $_GET['id'];
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

<br />
<h1>Gebruikers</h1><br />
<p>Gebruik het menu om een gebruiker toe te voegen of te bewerken.</p>

<?php
//Gebruiker toevoegen veld
if (isset($_GET["action"])) {
    if ($_GET["action"] == "add") {
        if (isset($_POST['addUser'])) {
            if (!empty($_POST['username']) && !empty($_POST['password']) && !empty($_POST['passControle'])) {
                if (!user::UsernameExists($_POST['username'])) {
                    if ($_POST['password'] == $_POST['passControle']) {
                        if (!empty($_POST['username']) && !empty($_POST['password'])) {
                            if (trim($_POST['username']) == '') {
                                $text2 = 'Gebruikersnaam mag niet uit alleen spaties bestaan.';
                            } else {
                                $addedCheck;
                                $addedCheck = $user = user::createUser($_POST["username"], $_POST["password"]);
                            }
                        } else {

                        }
                    } else {
                        $text2 = 'Wachtwoorden komen niet overeen!';
                    }
                } else {
                    $text2 = 'Gebruikersnaam bestaat al';
                }
            } else {
                $text2 = 'Een of meer velden zijn leeg';
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
            <?php echo $text2; ?>
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
        if ($users != null) {
            foreach ($users as $user) {
                //Table waar gebruikers in worden getoond.
                echo '<tr><td>' . $user->userName . '</td>
                    <td><a href="users.php?action=editUser&id=' . $user->id .
                    '&action=editUser">Gebruikersnaam wijzigen</a></td>
                    <td><a href="users.php?action=editPass&id=' . $user->id .
                    '&action=editPass">Wachtwoord wijzigen</a></td>
                    <td><a href="users.php?action=edit&id=' . $user->id .
                    '&actie=Delete">Verwijderen</a></td>
                    </tr>';
            }
        }
        echo "</table>";
?><br />
        <?php
        echo $text1;
    }

    //Stuk code voor veranderen van de gebruiker
    if ($_GET['action'] == 'editUser') {
?>

<br />
<div>
    <table>
        <tr>
            <td>
                <h1><?php echo isset($submitUser) ? $submitUser->userName : '' ?> wijzigen</h1><br />
            </td>
        </tr>
    </table>
<br />
<?php echo $text1; ?>
        <form action="users.php?action=edit" method="post" class="form">
            <div><label for="Nieuwe gebruikersnaam">Nieuwe gebruikersnaam: </label><input name="newUser" id="newUser" /></div>
            <div>&nbsp;</div>
            <div><label>&nbsp;</label><input type="submit" name="editUsername" value="Gebruikersnaam wijzigen"/></div>
        </form>
</div>
<br />


<?php
    }

    if ($_GET['action'] == 'editPass') {



?>
<br />
<div>
    <table>
        <tr>
            <td>
                <h1><?php echo isset($submitUser) ? $submitUser->userName : '' ?>'s wachtwoord wijzigen</h1><br />
            </td>
        </tr>
    </table>
<br />
        <form action="users.php?action=edit" method="post" class="form">
          
            <input name="oldPass" id="oldPass" type="hidden">
            <div><label for="Nieuw wachtwoord">Nieuw wachtwoord: </label><input name="newPass" id="newPass" type="password"></div>
            <div>&nbsp;</div>
            <div><label>&nbsp;</label><input type="submit" name="editPassword" value="Wachtwoord wijzigen"/></div>
        </form>
</div>  
<?php
    }
}
require_once ("onderkant.php");
?>