<?php
//Alle data classes includen
require_once("data/includeAll.php");

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
require_once("bovenkant.php");
?>
            <?php 
            if(isset($error) || $_SERVER["REQUEST_METHOD"] == "GET"){
            ?>
            <h2>Contact</h2>
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
                echo "Het bericht is verzonden.";
            }
            ?>
            
        </div>
<?php
require_once("onderkant.php");
?>