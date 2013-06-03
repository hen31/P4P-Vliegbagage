<?php
session_start();
//Alle data classes includen
require_once ("../data/includeAll.php");
$error1 = " ";
$error2 = " ";

if (isset($_POST['Submit'])) {
    if (isset($_POST["uname"])) {
        if (!empty($_POST['uname']) && !empty($_POST['pword'])) {
            
            $password = user::incryptPass($_POST["pword"]);
            $user = user::login($_POST["uname"], $password);
            
            if ($user == null) {
                $error1 =  'Gebruikersnaam of wachtwoord klopt niet';
            } else {
                $fatalerror = false;
                $_SESSION["user"] = $user;
                header("Location: admin.php");
                exit;
            }
            }
            else
            {
                $error2 =  '1 of meer velden zijn niet ingevuld';
            }
        


    }
}
?>
<!DOCTYPE HTML>
<html>
    <head>
	   <meta http-equiv="content-type" content="text/html" />
	   <title>Vliegbagage.nl | <?php if(isset($titel)){ echo $titel;}?></title>
       <link href="../style.css" type="text/css" rel="stylesheet"/>
       <link rel="stylesheet" type="text/css" media="screen" href="../css/ui.jqgrid.css" />
       <link rel="stylesheet" type="text/css" media="screen" href="../css/jquery-ui.css" />
       <link rel="stylesheet" type="text/css" media="screen" href="../css/jquery-ui-1.10.3.custom.css" />
    </head>
    <body>
    <div id="container">
        <div id="header"></div>
        <div class="name">
           
            <h1>VLIEGBAGAGE.NL</h1>
        </div>
        <div id="content">
<h2>Login</h2>    
<form name="f1" id="" method="post" action="login.php">

Gebruikersnaam:<br>

<input type="text" name="uname" id="" value="" size="25" maxlength="25"><br>

Wachtwoord: <br>
<input type="password" name="pword" id="" value="" size="25" maxlength="25"><br>

<input type="hidden" name="hidden1" value="_issubmitted">
<input type="submit" name="Submit" value="Inloggen"><br>
</br>
<?php
echo $error1;
echo $error2;
?>
</br>
</form>           
       
</div>
<div id="footer">
www.vliegbagage.nl - info@vliegbagage.nl<br/>
</div>
</div>



</body>
</html>
