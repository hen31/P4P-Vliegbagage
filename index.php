<?php
//Alle data classes includen
require_once("data/includeAll.php");
$titel = "Home";
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
                  <input id="beginPunt" />
                  <label for="eindPunt">Eindpunt: </label>
                  <input id="eindPunt"  />
                </div>
                <div>
             
                
                </div>
                <input id="submit" type="submit" value="Zoeken" /> 
                </form>          
                
                <div class="results">
                    <table id="list4"></table>
                </div>
                </div>

<script src="js/jquery-1.9.0.min.js"></script>
<script src="js/jquery-ui.js"></script>
<script src="js/grid.locale-nl.js" type="text/javascript"></script>
<script src="js/jquery.jqGrid.min.js" type="text/javascript"></script>
<script src="js/javascript.js"></script>

<script type="text/javascript">
jQuery("#list4").jqGrid({
	datatype: "local",
	height: 250,
   	colNames:['Inv No','Date', 'Client', 'Amount','Tax','Total','Notes'],
   	colModel:[
   		{name:'id',index:'id', width:60, sorttype:"int"},
   		{name:'invdate',index:'invdate', width:90, sorttype:"date"},
   		{name:'name',index:'name', width:100},
   		{name:'amount',index:'amount', width:80, align:"right",sorttype:"float"},
   		{name:'tax',index:'tax', width:80, align:"right",sorttype:"float"},		
   		{name:'total',index:'total', width:80,align:"right",sorttype:"float"},		
   		{name:'note',index:'note', width:150, sortable:false}		
   	],
   	multiselect: false,
   	caption: "Manipulating Array Data",
    onSelectRow: function (id) {
        var selr = jQuery('#list4').jqGrid('getGridParam', 'selrow')
var kelr = jQuery('#list4').jqGrid('getCell', selr, 'id');
        alert(kelr);
}
});
var mydata = [
		{id:"1",invdate:"2007-10-01",name:"test",note:"note",amount:"200.00",tax:"10.00",total:"210.00",},
		{id:"2",invdate:"2007-10-02",name:"test2",note:"note2",amount:"300.00",tax:"20.00",total:"320.00"},
		{id:"3",invdate:"2007-09-01",name:"test3",note:"note3",amount:"400.00",tax:"30.00",total:"430.00"},
		{id:"11",invdate:"2007-10-04",name:"test",note:"note",amount:"200.00",tax:"10.00",total:"210.00"},
		{id:"5",invdate:"2007-10-05",name:"test2",note:"note2",amount:"300.00",tax:"20.00",total:"320.00"},
		{id:"6",invdate:"2007-09-06",name:"test3",note:"note3",amount:"400.00",tax:"30.00",total:"430.00"},
		{id:"7",invdate:"2007-10-04",name:"test",note:"note",amount:"200.00",tax:"10.00",total:"210.00"},
		{id:"8",invdate:"2007-10-03",name:"test2",note:"note2",amount:"300.00",tax:"20.00",total:"320.00"},
		{id:"9",invdate:"2007-09-01",name:"test3",note:"note3",amount:"400.00",tax:"30.00",total:"430.00"}
		];
for(var i=0;i<=mydata.length;i++)
	jQuery("#list4").jqGrid('addRowData',i+1,mydata[i]);
    
    
  
</script>

  <script type="text/javascript">
  $(function() {
    var availableTags = [
    <?php
     $airports = airports::GetAirports();
     for ($i = 0; $i < count($airports); $i++) {
     if($i==count($airports)-1)
        {
    
            echo '"'.  $airports[$i]->AirportName.'"';
        }
        else
        {      
         echo '"'.$airports[$i]->AirportName.'"'.",";
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
          
<?php
require_once("onderkant.php");
?>
