<?php
//Alle data classes includen
require_once("data/includeAll.php");

session_start();

if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(isset($_POST["naam"], $_POST["mail"], $_POST["onderwerp"], $_POST["bericht"], $_POST["code"]) && !empty($_POST["naam"]) && !empty($_POST["mail"]) && !empty($_POST["mail"]) && !empty($_POST["bericht"]) && !empty($_POST["code"])){
        if(validator::isEmail($_POST["mail"]) && validator::stringLimit(100, 100000, $_POST["bericht"]) && validator::validateCaptcha($_POST["code"], $_SESSION["hash"])){
            mail(ADMIN_EMAIL, "Contactformulier vliegbagage: " .$_POST["onderwerp"], $_POST["bericht"], "Reply-To: " .$_POST["mail"]);
        }
        else{
            if(!validator::isEmail(($_POST["mail"]))){
                $error["mail"] = "<span style=\"color:red\">Ongeldig e-mail adres ingevuld.</span>";
            }
            if(!validator::stringLimit(100, 100000, $_POST["bericht"])){
                $error["bericht"] = "<span style=\"color:red\">Het bericht bevat te veel of te weinig tekens.</span>";
            }
            if(!validator::validateCaptcha($_POST["code"], $_SESSION["hash"])){
                $error["code"] = "<span style=\"color:red\">De code kwam niet overeen met de afbeelding.</span>";
            }
        }
    }
    else{
        $error["base"] = "<span style=\"color:red\">Niet alle velden zijn ingevuld.</span><br /><br />";
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
            Naam:<br /><input type="text" name="naam" value="<?php echo (isset($_POST["naam"]) ? htmlspecialchars($_POST["naam"]) : ""); ?>" /><br />
            E-mail: <?php echo (isset($error["mail"]) ? $error["mail"] : ""); ?><br /><input type="email" name="mail" value="<?php echo (isset($_POST["mail"]) ? htmlspecialchars($_POST["mail"]) : ""); ?>"/><br />
            Onderwerp:<br /><input type="text" name="onderwerp" value="<?php echo (isset($_POST["onderwerp"]) ? htmlspecialchars($_POST["onderwerp"]) : ""); ?>" /><br />
            Bericht: <?php echo (isset($error["bericht"]) ? $error["bericht"] : ""); ?><br /><textarea name="bericht" rows="10" cols="30"><?php echo (isset($_POST["bericht"]) ? htmlspecialchars($_POST["bericht"]) : ""); ?></textarea>
            <br /><br />
            <img src="captcha/captcha.php" alt="Code"/><br />
            Code: <?php echo (isset($error["code"]) ? $error["code"] : "") ?><br />
            <input type="text" name="code" /><br /><br />
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