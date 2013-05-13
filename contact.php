<?php
//Alle data classes includen
require("data/includeAll.php");

if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(isset($_POST["naam"], $_POST["mail"], $_POST["onderwerp"], $_POST["bericht"]) && !empty($_POST["naam"]) && !empty($_POST["mail"]) && !empty($_POST["mail"]) && !empty($_POST["bericht"])){
        print_r($_POST);
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
            
            <p>Mocht u contact op willen nemen met de eigenaar van deze website, dan kunt u het volgende contact formulier gebruiken.</p>
            
            <form action="contact.php" method="post">
            
            Naam:<br /><input type="text" name="naam" /><br />
            E-mail:<br /><input type="email" name="mail" /><br />
            Onderwerp:<br /><input type="text" name="onderwerp" /><br />
            Bericht:<br /><textarea name="bericht" rows="10" cols="30"></textarea>
            <br /><br />
            <input type="submit" value="Verzenden" />
            
            </form>
            
        </div>
<div id="footer">
www.vliegbagage.nl - info@vliegbagage.nl<br/>
</div>
</div>

<script src="js/jquery.js"></script>
<script src="js/javascript.js"></script>

</body>
</html>