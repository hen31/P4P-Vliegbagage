<?php
//Alle data classes includen
require_once("../data/includeAll.php");

$fatalerror = false;
if(isset($_POST['Submit']))
{
    if(!empty($_POST['uname']) && !empty($_POST['pword']))
    {
        $user = users::createUser($_POST["uname"],$_POST["pword"]);
        if($user == null)
        {
            $fatalerror = true;
        }
    }
}
if(isset($_POST['Submit2']))
{
    if(!empty($_POST['userId_del']) and !empty($_POST['pword_del']))
    {
        $user = users::deleteUser($_POST["userId_del"], $_POST["pword_del"]);
        if($user = null)
        {
            $fatalerror = true;
        }
    }
}

$titel = "Gebruikers";
require_once("bovenkant.php");
?>

<html>
<head>

<title>Registration Form</title>
</head>
<body>

<h2>Create User</h2>    
<form name="f1" id="" method="post" action="users.php">

User name:<br>

<input type="text" name="uname" id="" value="" size="25" maxlength="25"><br>

Password: <br>

<input type="password" name="pword" id="" value="" size="25" maxlength="25"><br>

<input type="hidden" name="hidden1" value="_issubmitted">
<input type="submit" name="Submit" value="submit"><br>

</form>

<h2>Delete Userr</h2>    
<form name="f2" id="" method="post" action="users.php">

User ID:<br>

<input type="text" name="userId_del" id="" value="" size="25" maxlength="25"><br>

Password: <br>
<input type="password" name="pword_del" id="" value="" size="25" maxlength="25"><br>

<input type="hidden" name="hidden2" value="_issubmitted">
<input type="submit" name="Submit2" value="submit"><br>

</form>

<?php
if($fatalerror)
{
    echo "Niet alle velden zijn ingevuld.";
}
?>

</body>
</html>  



<?php
require_once("onderkant.php");
?>