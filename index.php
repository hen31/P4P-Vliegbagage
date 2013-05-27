<?php

//Alle data classes includen
require_once ("data/includeAll.php");
require_once ("data/frontend.php");
$titel = "Home";
require_once ("bovenkant.php");
//check if het is ingevuld
if (isset($_GET["beginPunt"]) && isset($_GET["eindPunt"]))
{
    //vliegvelden ophalen
    $beginAirport = airports::GetAirportByName($_GET["beginPunt"]);
    $endAirport = airports::GetAirportByName($_GET["eindPunt"]);
    // kijken of de vliegvelden bestaan
    if ($beginAirport != null && $endAirport != null)
    {
        //kijken of het niet de zelfde vliegvelden zijn.
        if ($beginAirport->AirportID != $endAirport->AirportID)
        {
            //kijken of er een klasse is ingevuld
            if (isset($_GET["class"]))
            {
                $klasse = $_GET["class"];
                $counter = 0;
                $specialeBagage = array();
                //speciale bagage ophalen
                while (isset($_GET["specLug" . $counter]))
                {
                    $spec = SpecialLuggage::GetSpecialLuggageName($_GET["specLug" . $counter]);
                    if ($spec != null)
                    {
                        $specialeBagage[] = $spec;

                    }
                    $counter++;
                }
                $results = FrontEnd::Search($beginAirport, $endAirport, $klasse, $specialeBagage);
            }
        }
    }
}

?>
            <h2>Wat kost het vervoer van mijn koffer?</h2>
            <p>
                Iedere luchtvaartmaatschappij hanteert tarieven voor het vervoer van reizigers;<br/> wat kost het vervoer van de bagage en wat mag je  meenemen?
                <br/><br/>
                Deze website geeft het antwoord op deze vraag<br />
            </p>
                
            
                <form action="index.php" method="get" id="IndexForm">
                <?php

$counter = 0;
while (isset($_GET["specLug" . $counter]))
{
    echo '<input type="hidden" id="specLug' . $counter . '" name="specLug' . $counter .
        '" value="' . htmlspecialchars($_GET["specLug" . $counter]) . '">'; //echo '<input type="hidden" id="specLug'. $counter .'" name="specLug'.$counter '" value="'.$_GET["specLug".$counter] .'">';
    $counter++;
}

?>

                <div class="ui-widget">
                  <label for="beginPunt">Beginpunt: </label>
                  <input name="beginPunt" id="beginPunt" value="<?php

if (isset($_GET["beginPunt"]))
{
    echo htmlspecialchars($_GET["beginPunt"]);
}

?>" />
                  <label for="eindPunt">Eindpunt: </label>
                  <input name="eindPunt" id="eindPunt" value="<?php

if (isset($_GET["eindPunt"]))
{
    echo htmlspecialchars($_GET["eindPunt"]);
}

?>"  />
                </div>
                <label for="classSel">Klasse</label>
                <select name="class" id="classSel">
                <option value="0"
                 <?php

if (isset($_GET["class"]))
{
    if ($_GET["class"] == '0')
    {
        echo 'Selected="true"';
    }
}

?>
                >Economy</option>
                <option value="1" 
                <?php

if (isset($_GET["class"]))
{
    if ($_GET["class"] == '1')
    {
        echo 'Selected="true"';
    }
}

?>
                >Eerste klas</option>
                <option value="2"
                 <?php

if (isset($_GET["class"]))
{
    if ($_GET["class"] == '2')
    {
        echo 'Selected="true"';
    }
}

?>>Business klas</option>
                </select>
                                <div>
                                <div>
                                    <label for="spec">SpecialeBagege</label>
                                    <input name="spec" id="spec" />
                                    <button onClick="AddSpec(); return false;" value="Toevoegen" ></button>
                                    <br/>
                                    <ul id="specList">
                                        <?php

$counter = 0;
while (isset($_GET["specLug" . $counter]))
{
    echo '<li id="' . $counter . '">' . htmlspecialchars($_GET["specLug" . $counter]) .
        '<img src="images/deleteIcon.png" onClick="Delete(\'' . $counter . '\'); return false;" /></li>';
    $counter++;
}

?>
                                    </ul>
                                    </div>
             
                
                </div>
                <input id="submit" type="submit" value="Zoeken" /> 
                </form>          
                
                <div class="results">
                <?php

if (isset($results))
{

?>
                    <table id="list4" ></table>
                    <?php

} else
{

?>
                        <div class="Message">
                        Er zijn Vliegtuigmaatschapijen die aan deze voorwaarden voldoen.
                        </div>
                        <?php

}

?>
                </div>

<script src="js/jquery-1.9.0.min.js"></script>
<script src="js/jquery-ui.js"></script>
<script src="js/grid.locale-nl.js" type="text/javascript"></script>
<script src="js/jquery.jqGrid.min.js" type="text/javascript"></script>
<script src="js/javascript.js"></script>

<script type="text/javascript">
jQuery("#list4").jqGrid({
	datatype: "local",
	height: 500,
    width: 875,
   	colNames:['Logo','Naam', 'Gratis gewicht', 'Afmeting','Aantal stukken','Gewicht handbagage'],
   	colModel:[
   		{name:'logo',index:'logo', width:110, sortable:false, align:"center"},
   		{name:'name',index:'name', width:90, sorttype:"text",align:"center"},
   		{name:'GwGrts',index:'GwGrts', width:100, sorttype:"int",align:"center"},
   		{name:'Afmeting',index:'Afmeting', width:80, sorttype:"int",align:"center" },
   		{name:'Apcs',index:'Apcs', width:80, sorttype:"int",align:"center" },		
   		{name:'Gwhl',index:'Gwhl', width:80, sorttype:"int",align:"center"}
           		
   	],
   	multiselect: false,
   	caption: "Zoekresultaten",
    onSelectRow: function (id) {
        var selr = jQuery('#list4').jqGrid('getGridParam', 'selrow')
var kelr = jQuery('#list4').jqGrid('getCell', selr, 'name');
        popitup("details.php?name=" + kelr, kelr);
}
});
function popitup(url, kerl) {
    var w = 900;
    var h= 600;
 var left = (screen.width/2)-(w/2);
	newwindow=window.open(url,kerl, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', left='+left);
	if (window.focus) {newwindow.focus()}
	return false;
}
var mydata = [
<?php

/*	{identifier:"120",invdate:"2007-10-01",name:"test",note:"note",amount:"200.00",tax:"10.00",total:"210.00",},
{identifier:"2",invdate:"2007-10-02",name:"test2",note:"note2",amount:"300.00",tax:"20.00",total:"320.00"},
{identifier:"3",invdate:"2007-09-01",name:"test3",note:"note3",amount:"400.00",tax:"30.00",total:"430.00"},
{identifier:"11",invdate:"2007-10-04",name:"test",note:"note",amount:"200.00",tax:"10.00",total:"210.00"},
{identifier:"5",invdate:"2007-10-05",name:"test2",note:"note2",amount:"300.00",tax:"20.00",total:"320.00"},
{identifier:"6",invdate:"2007-09-06",name:"test3",note:"note3",amount:"400.00",tax:"30.00",total:"430.00"},
{identifier:"7",invdate:"2007-10-04",name:"test",note:"note",amount:"200.00",tax:"10.00",total:"210.00"},
{identifier:"8",invdate:"2007-10-03",name:"test2",note:"note2",amount:"300.00",tax:"20.00",total:"320.00"},
{identifier:"9",invdate:"2007-09-01",name:"test3",note:"note3",amount:"400.00",tax:"30.00",total:"430.00"}*/
if (isset($results) && count($results) > 0)
{
    for ($s = 0; $s < count($results); $s++)
    {
        
        $airline = $results[$s];
        $afmetingen = $airline->
                classes[0]->sizeTotalPerItem ? $airline->classes[0]->sizeTotalPerItem : $airline->
                classes[0]->sizeLenghtPerItem . 'x' . $airline->classes[0]->sizeWidthPerItem .
                'x' . $airline->classes[0]->sizeHeightPerItem;
        if (count($results) - 1 == $s)
        {
            echo '{logo:"<img style=\"width:100px;height:100px;\" src=\"images/airlines/' . $airline->logo . '\"/>",name:"' . $airline->name . '",' .
                'GwGrts:"' . $airline->classes[0]->maxWeightLuggage . '",Afmeting:"' . $afmetingen. '",Apcs:"' . $airline->classes[0]->
                pcsLuggage . '",Gwhl:"' . $airline->classes[0]->pcsHL . '"}';
        } else
        {
 echo '{logo:"<img style=\"width:100px;height:100px;\" src=\"images/airlines/' . $airline->logo . '\"/>",name:"' . $airline->name . '",' .
                'GwGrts:"' . $airline->classes[0]->maxWeightLuggage . '",Afmeting:"' . $afmetingen. '",Apcs:"' . $airline->classes[0]->
                pcsLuggage . '",Gwhl:"' . $airline->classes[0]->pcsHL . '"},';
        }
    }

}

?>
		];
for(var i=0;i<=mydata.length;i++)
	jQuery("#list4").jqGrid('addRowData',i+1,mydata[i]);
    
    
  
</script>
<script type="text/javascript"> 
function strip(html)
{
   var tmp = document.createElement("DIV");
   tmp.innerHTML = html;
   return tmp.textContent||tmp.innerText;
}
var counter =0;
function AddSpec()
  {
    var text = strip($("#spec").val());
    if(/\S/.test(text) == true )
    {
    $('#IndexForm').append('<input type="hidden" id="specLug'+counter +'" name="specLug'+counter +'" value="'+text +'">');
        $('#specList').append('<li id="'+counter+'">'+text +'<img src="images/deleteIcon.png" onClick="Delete(\''+counter+'\'); return false;" /></li>');
    counter = counter + 1;
    $("#spec").val('');
    }
    return false;
  }
  function Delete( counter)
  {
$('#'+counter).remove();
   $('#specLug'+counter).remove();
   
  }
   $(function() {
    var availableSpec = [
    <?php

$specialeBagage = SpecialLuggage::GetSpecialLuggageList();
for ($i = 0; $i < count($specialeBagage); $i++)
{
    if ($i == count($specialeBagage) - 1)
    {

        echo '"' . $specialeBagage[$i]->Name . '"';
    } else
    {
        echo '"' . $specialeBagage[$i]->Name . '"' . ",";
    }
}

?>
    ];
    $( "#spec" ).autocomplete({
      source: availableSpec
    });
      });
  </script>
  <script type="text/javascript">
  $(function() {
    var availableTags = [
    <?php

$airports = frontend::GetAirports();
for ($i = 0; $i < count($airports); $i++)
{
    if ($i == count($airports) - 1)
    {

        echo '"' . $airports[$i]->AirportName . '"';
    } else
    {
        echo '"' . $airports[$i]->AirportName . '"' . ",";
    }
}

?>
    ];
    $( "#beginPunt" ).autocomplete({
      source: availableTags
    });
      $( "#eindPunt" ).autocomplete({
      source: availableTags
    });
  });
 
  </script>
          
<?php

require_once ("onderkant.php");

?>
