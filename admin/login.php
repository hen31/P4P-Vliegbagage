<?php
//Alle data classes includen
require_once ("../data/includeAll.php");

$fatalerror = false;
if (isset($_POST['Submit']))
{
    if (isset($_POST["uname"])) {
        $password = user::incryptPass($_POST["pword"]);
        
        $user = user::login($_POST["uname"], $password);
        if ($user == null) {
            $fatalerror = true;
        } else {
            $fatalerror = false;
            $_SESSION["user"] = $user;
            header("Location: admin.php");
            exit;
        }

        $titel = "Admin login";
        require_once ("../bovenkant.php");
        //if gepost
    }
}
?>
<html>
<head>

<title>Registration Form</title>
</head>
<body>
<h2>Login</h2>    
<form name="f1" id="" method="post" action="login.php">

Gebruikersnaam:<br>

<input type="text" name="uname" id="" value="" size="25" maxlength="25"><br>

Wachtwoord: <br>
<input type="password" name="pword" id="" value="" size="25" maxlength="25"><br>

<input type="hidden" name="hidden1" value="_issubmitted">
<input type="submit" name="Submit" value="Inloggen"><br>

</form>


<?php
if ($fatalerror) {
    echo "Niet alle velden zijn ingevuld.";
}
?>
</body>
</html>            
       
<?php
require_once ("onderkant.php");

?>
