<?php

/**
 * @Auteur Hendrik de Jonge
 * @Datum 20-5-2013
 * @uses Wordt gebruikt om de gebruiker zijn informatie te geven
 */

//Alle data classes includen
require_once ("data/includeAll.php");
require_once ("data/FrontEnd.php");
$titel = "Home";
require_once ("bovenkant.php");
//check if het is ingevuld
if (isset($_GET["beginPunt"]) && isset($_GET["eindPunt"])) {
    if (!validator::isInt($_GET["beginPunt"])) {
        $beginAirport = htmlspecialchars($_GET["beginPunt"]);
    } else {
        $beginAirport = airports::GetAirportByID($_GET["beginPunt"]);
    }
    if (!validator::isInt($_GET["eindPunt"])) {
        $endAirport = htmlspecialchars($_GET["eindPunt"]);
    } else {
        $endAirport = airports::GetAirportByID($_GET["eindPunt"]);
    }
    //vliegvelden ophalen


    // kijken of de vliegvelden bestaan
    if ($beginAirport != null && $endAirport != null) {
        //kijken of het niet de zelfde vliegvelden zijn.
        /*  if ($beginAirport->AirportID != $endAirport->AirportID)
        {*/
        //kijken of er een klasse is ingevuld
        if (isset($_GET["class"])) {
            $klasse = $_GET["class"];
            $counter = 0;
            $specialeBagage = array();
            //speciale bagage ophalen
            while (isset($_GET["specLug" . $counter])) {
                $spec = SpecialLuggage::GetSpecialLuggageName($_GET["specLug" . $counter]);
                if ($spec != null) {
                    $specialeBagage[] = $spec;

                }
                $counter++;
            }
            $results = FrontEnd::Search($beginAirport, $endAirport, $klasse, $specialeBagage);
        }
        //}
    }
}

?>
<noscript>
<div class="errorJava">
<div class="errorJavaCenter">
<h1 style="color: red;">Om deze site te bekijken moet u javascript aanzetten<br />Om te zien hoe dit moet gaat u naar <a href="http://www.enable-javascript.com/nl">deze site</a></h1><br />
</div>
</div>
</noscript>
            <h2>Wat kost het vervoer van mijn koffer?</h2>
            <p>
                Iedere luchtvaartmaatschappij hanteert tarieven voor het vervoer van reizigers;<br/> wat kost het vervoer van de bagage en wat mag je  meenemen?
                <br/><br/>
                Deze website geeft het antwoord op deze vraag.<br />
            </p>
                
            
                <form action="index.php" method="get" id="IndexForm" name="IndexForm" class="form">
                <?php

//als special luggage is die toevoegen als hiddenfield
$counter = 0;
while (isset($_GET["specLug" . $counter])) {
    echo '<input type="hidden" id="specLug' . $counter . '" name="specLug' . $counter .
        '" value="' . htmlspecialchars($_GET["specLug" . $counter]) . '">'; //echo '<input type="hidden" id="specLug'. $counter .'" name="specLug'.$counter '" value="'.$_GET["specLug".$counter] .'">';
    $counter++;
}

?>

                <div >
                  <label for="beginPunt">Beginpunt: </label>
                  <select name="beginPunt" id="beginPunt" class="input" onchange="this.form.submit();">
                  <option></option>
    <?php

//vliegvelden toevoegen voor typeahead
$airports = frontend::GetAirportsBegin();// airports::GetAirportsTwoPerCity();
$CitieCount = array();
for ($i = 0; $i < count($airports); $i++) {
    if (isset($_GET["beginPunt"]) && htmlspecialchars($_GET["beginPunt"]) == $airports[$i]->
        AirportID) {
        echo '<option selected="true" value="' . $airports[$i]->AirportID . '">' . $airports[$i]->
            AirportName . '(' . $airports[$i]->AirportCity . ')' . '</option>';
    } else {
        echo '<option value="' . $airports[$i]->AirportID . '">' . $airports[$i]->
            AirportName . '(' . $airports[$i]->AirportCity . ')' . '</option>';
    }
     if (array_key_exists($airports[$i]->AirportCity, $CitieCount)) {
        if ($CitieCount[$airports[$i]->AirportCity] == 1) {
            $CitieCount[$airports[$i]->AirportCity] = 2;
               if (isset($_GET["beginPunt"]) && htmlspecialchars($_GET["beginPunt"]) ==  $airports[$i]->AirportCity) {
        echo '<option selected="true" value="' .  $airports[$i]->AirportCity . '">' .  $airports[$i]->AirportCity .
            '(alle vliegvelden)</option>';
    } else {
        echo '<option  value="' .  $airports[$i]->AirportCity . '">' .  $airports[$i]->AirportCity .
            '(alle vliegvelden)</option>';
    }
            
        }
    } else {
        $CitieCount[$airports[$i]->AirportCity] = 1;
        
    }
}

?>
</select>
                  <label for="eindPunt">Eindpunt: </label>
                  <select name="eindPunt" id="eindPunt" class="input">
                   <option></option>
                   
                  <?php

if (isset($_GET["beginPunt"]) && !empty($_GET["beginPunt"])) {
    if (airports::GetAirportByID(htmlspecialchars($_GET["beginPunt"])) != null) {
        $airports = frontend::GetAirportsEndByStart(htmlspecialchars($_GET["beginPunt"]));
        for ($i = 0; $i < count($airports); $i++) {
            if (isset($_GET["eindPunt"]) && htmlspecialchars($_GET["eindPunt"]) == $airports[$i]->
                AirportID) {
                echo '<option selected="true" value="' . $airports[$i]->AirportID . '">' . $airports[$i]->
                    AirportName . '(' . $airports[$i]->AirportCity . ')' . '</option>';
            } else {
                echo '<option value="' . $airports[$i]->AirportID . '">' . $airports[$i]->
                    AirportName . '(' . $airports[$i]->AirportCity . ')' . '</option>';
            }
                if (array_key_exists($airports[$i]->AirportCity, $CitieCount)) {
                if ($CitieCount[$airports[$i]->AirportCity] == 1) {
                    $CitieCount[$airports[$i]->AirportCity] = 2;
                      if (isset($_GET["eindPunt"]) && htmlspecialchars($_GET["eindPunt"]) ==  $airports[$i]->AirportCity) {
                echo '<option selected="true" value="' .  $airports[$i]->AirportCity . '">' .  $airports[$i]->AirportCity .
                    '(alle vliegvelden)</option>';
            } else {
                echo '<option  value="' .  $airports[$i]->AirportCity . '">' .  $airports[$i]->AirportCity .
                    '(alle vliegvelden)</option>';
            }
                }
            } else {
                $CitieCount[$airports[$i]->AirportCity] = 1;
            }
        }
    }
}
?>
                  </select>
                </div>
                <label for="classSel">Klasse:</label>
                <select name="class" id="classSel" class="input">
                <option value="0"
                 <?php

//als een klasse is ingevuld die selecteren
if (isset($_GET["class"])) {
    if ($_GET["class"] == '0') {
        echo 'Selected="true"';
    }
}

?>
                >Economy</option>
                <option value="1" 
                <?php

//als een klasse is ingevuld die selecteren
if (isset($_GET["class"])) {
    if ($_GET["class"] == '1') {
        echo 'Selected="true"';
    }
}

?>
                >Eerste klas</option>
                <option value="2"
                 <?php

//als een klasse is ingevuld die selecteren
if (isset($_GET["class"])) {
    if ($_GET["class"] == '2') {
        echo 'Selected="true"';
    }
}

?>>Business klas</option>
                </select>
                                <div>
                                <div >
                                
                                    <label for="spec">Extra bagage:</label>
                                    <select name="spec" id="spec" class="input" style="float:left;">
                                    <option></option>
                                <?php

$specialeBagage1 = SpecialLuggage::GetSpecialLuggageList();
for ($i = 0; $i < count($specialeBagage1); $i++) {

    echo '<option>' . $specialeBagage1[$i]->Name . '</option>';

}

?></select>
                                    <button onClick="AddSpec(); return false;" class="input">Toevoegen</button>
                                    <div id="specListDiv" class="input" >
                                    <ul id="specList">
                                        <?php

//zorgen dat er jvavascript word uitgevoerd als op delete word geklikt
$counter = 0;
while (isset($_GET["specLug" . $counter])) {
    echo '<li id="' . $counter . '">' . htmlspecialchars($_GET["specLug" . $counter]) .
        '<img src="images/deleteIcon.png" onClick="Delete(\'' . $counter . '\'); return false;"  /></li>';
    $counter++;
}

?>
                                    </ul>
                                    </div>
                                    </div>
             
                
                </div>
                <label for="submit" >&nbsp;</label>
                <input id="submitBtn" type="submit" value="Zoeken" /> 
                </form>          
                
                <div class="results">
                <?php

//als er resultaat is die tonen
if (isset($results) && count($results) > 0) {

?>
                    <table id="list4" ></table>
                    <?php

} else {

?>
                        <p>
                    <?php
    if (isset($_GET["beginPunt"])) {

?>
                        Er zijn geen luchtvaartmaatschapijen die aan deze voorwaarden voldoen.
                        <?php

    }

?>
                        </p>
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

$(document).ready(function(){
   <?php

if (isset($_GET["specLug0"]) == false) {

?>
   $('#specListDiv').hide();
    <?php

}

?>
});
jQuery("#list4").jqGrid({
	datatype: "local",
	height: 500,
    width: 875,
   	colNames:['Logo','Naam', 'Max. Gw. Ruimbagage', 'Afmeting','Max. koffers ruim','Gewicht handbagage', 'Handbagage afmeting'],
   	colModel:[
   		{name:'logo',index:'logo', width:110, sortable:false, align:"center"},
   		{name:'name',index:'name', width:90, sorttype:"text",align:"center"},
   		{name:'GwGrts',index:'GwGrts', width:120, sorttype:"int",align:"center"},
   		{name:'Afmeting',index:'Afmeting', width:90, sorttype:"int",align:"center" },
   		{name:'Apcs',index:'Apcs', width:100, sorttype:"int",align:"center" },		
   		{name:'Gwhl',index:'Gwhl', width:90, sorttype:"int",align:"center"},		
   		{name:'AfmetingHL',index:'AfmetingHL', width:90, sorttype:"int",align:"center"}
           		
   	],
   	multiselect: false,
   	caption: "Zoekresultaten",
    onSelectRow: function (id) {
        var selr = jQuery('#list4').jqGrid('getGridParam', 'selrow')
var kelr = jQuery('#list4').jqGrid('getCell', selr, 'name');
        popitup("Details.php?name=" + kelr, kelr);
}
});
function popitup(url, kerl) {
    var w = 900;
    var h= 600;
 var left = (screen.width/2)-(w/2);
 <?php

if (isset($results) && count($results) > 0) {
    //class nummer in javascript zetten.
    echo 'var classnumber =' . $results[0]->classes[0]->classnumber . ';';
}

?>
     <?php

///speciale bagage toevoegen aan javascript
if (isset($specialeBagage)) {
    $stringSpec = 'var SpecLug ="';
    for ($i = 0; $i < count($specialeBagage); $i++) {
        $stringSpec .= 'Speclug' . $i . '=' . $specialeBagage[0]->specialluggage_id .
            '&';
    }

    $stringSpec .= '";';
    echo $stringSpec;
}

?>
	newwindow=window.open(url+'&class='+classnumber +'&'+ SpecLug,kerl, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, copyhistory=no, width='+w+', height='+h+', left='+left);
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
if (isset($results) && count($results) > 0) {
    for ($s = 0; $s < count($results); $s++) {
        //resultaten voor javascript formateren
        $airline = $results[$s];
        $afmetingen = $airline->classes[0]->sizeTotalPerItem ? $airline->classes[0]->
            sizeTotalPerItem . 'cm' : $airline->classes[0]->sizeLenghtPerItem . ' x ' . $airline->
            classes[0]->sizeWidthPerItem . ' x ' . $airline->classes[0]->sizeHeightPerItem .
            'cm';
        $afmetingenHL = $airline->classes[0]->sizeTotalHL ? $airline->classes[0]->
            sizeTotalHL . 'cm' : $airline->classes[0]->sizeLenghtHL . ' x ' . $airline->
            classes[0]->SizeWidthHL . ' x ' . $airline->classes[0]->sizeHeightHL . 'cm';
        if ($airline->classes[0]->MaxWeightHL == 0) {
            $airline->classes[0]->MaxWeightHL = 'n.v.t.';
        } else {
            $airline->classes[0]->MaxWeightHL .= 'kg';
        }

        if ($airline->classes[0]->pcsLuggage == 0) {
            $airline->classes[0]->pcsLuggage = 'n.v.t.';
        }

        if ($airline->classes[0]->maxWeightLuggage == 0) {
            $airline->classes[0]->maxWeightLuggage = 'n.v.t.';
        } else {
            $airline->classes[0]->maxWeightLuggage .= 'kg';
        }
        if (count($results) - 1 == $s) {
            $dataString = '{logo:"<img style=\"width:100px;height:100px;\" src=\"images/airlines/' .
                $airline->logo . '\"/>",name:"' . htmlspecialchars($airline->name) . '",' .
                'GwGrts:"' . $airline->classes[0]->maxWeightLuggage . '",Afmeting:"' . $afmetingen .
                '",Apcs:"' . $airline->classes[0]->pcsLuggage . '",Gwhl:"' . $airline->classes[0]->
                MaxWeightHL . '",AfmetingHL:"' . $afmetingenHL . '"}';
        } else {
            $dataString = '{logo:"<img style=\"width:100px;height:100px;\" src=\"images/airlines/' .
                $airline->logo . '\"/>",name:"' . htmlspecialchars($airline->name) . '",' .
                'GwGrts:"' . $airline->classes[0]->maxWeightLuggage . '",Afmeting:"' . $afmetingen .
                '",Apcs:"' . $airline->classes[0]->pcsLuggage . '",Gwhl:"' . $airline->classes[0]->
                MaxWeightHL . '",AfmetingHL:"' . $afmetingenHL . '"},';
        }
        echo $dataString;
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
    var availableSpec = [
    <?php

//speciale bagage maken
$specialeBagage = SpecialLuggage::GetSpecialLuggageList();
for ($i = 0; $i < count($specialeBagage); $i++) {
    if ($i == count($specialeBagage) - 1) {

        echo '"' . $specialeBagage[$i]->Name . '"';
    } else {
        echo '"' . $specialeBagage[$i]->Name . '"' . ",";
    }
}

?>
    ];
function AddSpec()
  {
    var text = $("#spec").find(":selected").text();
    if(/\S/.test(text) == true)
    {
           $('#specListDiv').show();
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
    if( $('#specList').find('li').length ==0)
   {

       $('#specListDiv').hide();
   }
   
  }
  </script>
          
<?php

//onderkant toevoegen.
require_once ("onderkant.php");

?>
