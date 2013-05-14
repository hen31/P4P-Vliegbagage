<?php
//Alle data classes includen
require_once("data/includeAll.php");

require_once("bovenkant.php");
?>

            <h2>Wat kost het vervoer van mijn koffer?</h2>
            <p>
                Iedere luchtvaartmaatschappij hanteert tarieven voor het vervoer van reizigers;<br/> wat kost het vervoer van de bagage en wat mag je  meenemen?
                <br/><br/>
                Deze website geeft het antwoord op deze vraag<br />
            </p>
            
                <form action="index.php" method="post">
                <div class="ui-widget">
                  <label for="beginPunt">Beginpunt: </label>
                  <input id="beginPunt" autocomplete="off"/>
                  <label for="eindPunt">Eindpunt: </label>
                  <input id="eindPunt" autocomplete="off" />
                </div>
                <div>
             
                
                </div>
                <input id="submit" type="submit" value="Zoeken" /> 
                </form>          
                
                <div class="results">
                
                </div>
            </div>

<div id="footer">
www.vliegbagage.nl - info@vliegbagage.nl<br/>
</div>
</div>

<script src="js/jquery.js"></script>
<script src="js/javascript.js"></script>
<script src="js/jquery-ui.js"></script>


  <script>
  $(function() {
    var availableTags = [
    <?php
     $airports = Airports::GetAirports();
     for ($i = 0; $i <= count($airports); $i++) {
     if($i==count($airports)-1)
        {
    
            echo $airports[$i]->Naam;
        }
        else
        {        
         echo $airports[$i]->Naam.",";
        }
      }?>
    ];
    $( "#beginPunt" ).autocomplete({
      source: availableTags
    });
      $( "#eindPunt" ).autocomplete({
      source: availableTags
    });
  });
  </script>
</body>
</html>            
<?php
require_once("onderkant.php");
?>
