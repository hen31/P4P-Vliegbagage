<!DOCTYPE HTML>
<html>
    <head>
	   <meta http-equiv="content-type" content="text/html" />
	   <title>Vliegbagage.nl | Home</title>
       <link href="style.css" type="text/css" rel="stylesheet"/>
    </head>
    <body>
    <?php
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        print_r($_POST);
    }
    ?>
    <div id="container">
        <div id="header"></div>
        <div class="name">
            <h1>VLIEGBAGAGE.NL</h1>
        </div>
        <div id="content">
            <h2>Wat kost het vervoer van mijn koffer?</h2>
            <p>
                Iedere luchtvaartmaatschappij hanteert tarieven voor het vervoer van reizigers;<br/> wat kost het vervoer van de bagage en wat mag je  meenemen?
                <br/><br/>
                Deze website geeft het antwoord op Uw persoonlijke situatie!<br />

                Na het invullen van de vragen krijgt U binnen maximaal drie werkdagen antwoord.
            </p>
            
                <form action="index.php" method="post">
                    Vul iets randoms in <input type="text" name="iets" /><br />
                    <input type="submit" value="En klik op deze mooie knop" />
                </form>          
            </div>
<div id="footer">
www.vliegbagage.nl - info@vliegbagage.nl<br/>
</div>
</div>

</body>
</html>