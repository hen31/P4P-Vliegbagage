<?php
//Alle data classes includen
require("data/includeAll.php");

if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(isset($_POST["naam"], $_POST["mail"], $_POST["onderwerp"], $_POST["bericht"]) && !empty($_POST["naam"]) && !empty($_POST["mail"]) && !empty($_POST["mail"]) && !empty($_POST["bericht"])){
        if(validator::isEmail($_POST["mail"]) && validator::stringLimit(100, 100000, $_POST["bericht"])){
            mail(ADMIN_EMAIL, "Contactformulier vliegbagage: " .$_POST["onderwerp"], $_POST["bericht"], "Reply-To: " .$_POST["mail"]);
        }
        else{
            if(!validator::isEmail(($_POST["mail"]))){
                $error["mail"] = "<span style=\"color:red\">Ongeldig e-mail adres ingevuld.</span>";
            }
            if(!validator::stringLimit(100, 100000, $_POST["bericht"])){
                $error["bericht"] = "<span style=\"color:red\">Het bericht bevat te veel of te weinig tekens.</span>";
            }
        }
    }
    else{
        $error["base"] = "<span style=\"color:red\">Niet alle velden zijn ingevuld.</span>";
    }
}
?>

<!DOCTYPE HTML>
<html>
    <head>
	   <meta http-equiv="content-type" content="text/html" />
	   <title>Vliegbagage.nl | Home</title>
       <link href="style.css" type="text/css" rel="stylesheet"/>
    </head>
    <body>
    <div id="container">
        <div id="header"></div>
        <div class="name">
            <h1>VLIEGBAGAGE.NL</h1>
        </div>
        <div id="content">
            <?php 
            if(isset($error) || $_SERVER["REQUEST_METHOD"] == "GET"){
            ?>
            <p>Mocht u contact op willen nemen met de eigenaar van deze website, dan kunt u het volgende contact formulier gebruiken.</p>
            
            <form action="contact.php" method="post">
            <?php echo (isset($error["base"]) ? $error["base"] : ""); ?>
            Naam:<br /><input type="text" name="naam" /><br />
            E-mail: <?php echo (isset($error["mail"]) ? $error["mail"] : ""); ?><br /><input type="email" name="mail" /><br />
            Onderwerp:<br /><input type="text" name="onderwerp" /><br />
            Bericht: <?php echo (isset($error["bericht"]) ? $error["bericht"] : ""); ?><br /><textarea name="bericht" rows="10" cols="30"></textarea>
            <br /><br />
            <input type="submit" value="Verzenden" />
            
            </form>
            
            <?php
            }
            else
            {
                echo "De mail is verzonden. Veel geluk met je leven.";
            }
            ?>
            
        </div>
<div id="footer">
www.vliegbagage.nl - info@vliegbagage.nl<br/>
</div>
</div>

<script src="js/jquery.js"></script>
<script src="js/javascript.js"></script>

</body>
</html>