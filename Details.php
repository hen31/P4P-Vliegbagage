<?php
header('Content-Type: text/html; charset=UTF-8');
/**
 * @Auteur Hendrik de Jonge
 * @Datum 20-5-2013
 * @uses Wordt gebruikt om de gebruiker zijn informatie te geven
 */
session_start();
//Alle data classes includen
require_once ("data/includeAll.php");
require_once ("data/frontend.php");
$titel = "Details van ";
// zorgen dat alles is ingevuld
if(!isset($_GET["name"])|| !isset($_GET["class"]))
{
    exit();
}
else
{
    $titel .= $_GET["name"];
}
    //gevens ophalen   
if(airline::airline_name_exists($_GET["name"]))
{
   $airline = airline::get_airline_by_name($_GET["name"]);
      $counter = 0;
                $specialeBagage = array();
                //speciale bagage ophalen
                while (isset($_GET["Speclug" . $counter]))
                {
            
                    $spec = SpecialLuggage::GetSpecialLuggageID($_GET["Speclug" . $counter]);
                    if ($spec != null)
                    {
                        $specialeBagage[] = SpecialLuggage::GetCombo($airline->airline_id,$spec->specialluggage_id);

                    }
                    $counter++;
                }

}
else
{
    //anders stoppen met script
    exit;
}
//alles tonen
?>
<!DOCTYPE HTML>
<html>
    <head>
	   <meta http-equiv="content-type" content="text/html" />
	   <title>Vliegbagage.nl | <?php if(isset($titel)){ echo htmlspecialchars($titel);}?></title>
       <link href="style.css" type="text/css" rel="stylesheet"/>
       <link rel="stylesheet" type="text/css" media="screen" href="css/ui.jqgrid.css" />
       <link rel="stylesheet" type="text/css" media="screen" href="css/jquery-ui.css" />
       <link rel="stylesheet" type="text/css" media="screen" href="css/jquery-ui-1.10.3.custom.css" />
    </head>
    <body>
    <div id="container">
            <div id="content">
            <div id="airLogo"><img style="width: 175px; height:175px; " src="images/airlines/<?php echo $airline->logo;?>" /></div>
          <hr/>
            <div id="AirlineNamediv"><h2>
            <?php 
            echo htmlspecialchars($airline->name);?>
            </h2>
            <p>
           <?php echo htmlspecialchars($airline->notes);?>
            </p>
            </div>
            <table style="width:100%;">
            
            
            <tr>
            <td>Gratis gewicht:</td>
            <td><?php if($airline->classes[$_GET["class"]]->maxWeightLuggage !=0){ echo $airline->classes[$_GET["class"]]->maxWeightLuggage.'kg';}
            else
            {
                                echo 'Deze vliegmaatschapij rekent</br> met aantal koffers en niet met gewicht.';
            }?></td>
           <td>Gratis aantal handbagage:</td>
            <td><?php if($airline->classes[$_GET["class"]]->pcsHL !=0){echo $airline->classes[$_GET["class"]]->pcsHL.'kg';}
            else{
                echo 'Deze vliegmaatschapij rekent met gewichten</br> en niet met aantal koffers.';
            }?></td>
           
            </tr>
            
                <tr>
                  
            <td>Gratis aantal ruim koffers:</td>
            <td><?php if($airline->classes[$_GET["class"]]->pcsLuggage !=0){echo $airline->classes[$_GET["class"]]->pcsLuggage;}
            else{
                echo 'Deze vliegmaatschapij rekent met gewichten</br> en niet met aantal koffers.';
            }?></td>
            <td>Gratis gewicht handbagage:</td>
            <td><?php if($airline->classes[$_GET["class"]]->MaxWeightHL !=0){echo $airline->classes[$_GET["class"]]->MaxWeightHL.'kg';}
            else{
                echo 'Deze vliegmaatschapij rekent met aantal koffers</br> en niet met gewicht.';
            }?></td>
            </tr>
       
            
              <tr>
              <td>Ruim bagage afmetingen:</td>
            <td><?php  $afmetingen = $airline->
                classes[$_GET["class"]]->sizeTotalPerItem ? $airline->classes[$_GET["class"]]->sizeTotalPerItem.'cm' : $airline->
                classes[$_GET["class"]]->sizeLenghtPerItem . 'cm x ' . $airline->classes[$_GET["class"]]->sizeWidthPerItem .
                'cm x ' . $airline->classes[$_GET["class"]]->sizeHeightPerItem.'cm';
                echo $afmetingen;
            ?></td>
            <td>Handbagage afmetingen:</td>
            <td><?php if($airline->classes[$_GET["class"]]->sizeTotalHL !=0 ){ echo $airline->classes[$_GET["class"]]->sizeTotalHL.'cm';}
            else
            {
              $afmetingen =   $airline->
                classes[$_GET["class"]]->sizeLenghtHL . 'cm x ' . $airline->classes[$_GET["class"]]->SizeWidthHL .
                'cm x ' . $airline->classes[$_GET["class"]]->sizeHeightHL.'cm';
                echo $afmetingen;
            }?></td>
            
          

            </tr>
                 <tr>
            <td colspan="4">
            <hr /></td></tr>
            
            <tr>
             <td>Kosten overwicht(per kg):</td>
            <td>&euro;<?php echo number_format($airline->OverweightChargeG,2, ',', ' ');?></td>
                 <td>Kosten te grote koffer:</td>
            <td>&euro;<?php echo number_format($airline->OversizeCharge,2, ',', ' ');?></td>
            </tr>
             <tr>
             <td>Kosten extra koffer:</td>
            <td><?php foreach($airline->ChargeExtraBag as $bag)
            {
                echo 'koffer ' . ($bag->number+1 ). ': &euro;'. number_format($bag->costs,2, ',', ' ') . '</br>';
            }?></td>
                 <td>Kosten overgewicht per koffer:</td>
            <td>&euro;<?php echo  number_format($airline->OverweightChargeBag,2, ',', ' ');?><br />Is alleen van toepassing als per koffer wordt gerekend</td>
            </tr>
            
                   <tr>
            <td colspan="4">
            <hr /></td></tr>
        
            </table>
        
                <?php if(count($specialeBagage) >0){
                ?>
                    <div id="accordion5">
                      <table style="width:100%;">
                <tr>
            <th>
            Speciale bagage naam
            </th>
            <th>
          Opmerkingen
            </th>
          </tr>
       
                        <?php
                         foreach($specialeBagage as $specbag)
                        {
                            $naam =htmlspecialchars($specbag->Name);
                            $notes =htmlspecialchars($specbag->Notes);
                            echo '<tr><td>'.$naam.'</td><td>' .$notes .'</td></tr>';
                        }?>
            </table>
                    </div>
                    <hr />
                <?php
            }?>
            
            <div id="accordion">
            <h3>Kinderen zonder eigen stoel</h3>
            <table style="width:100%;">
                <tr>
            <td>
            Stukken bagage kind:
            </td>
            <td>
            <?php echo $airline->classes[$_GET["class"]]->pcsLuggageInfant;?>
            </td>
                 <td>
                 Max. gewicht bagage kind:
            </td>
            <td>
                        <?php echo $airline->classes[$_GET["class"]]->pcsLuggageInfantMaxWeight;?>kg</td></tr>
            </table>
        
            </div>
            <hr />
                  <div id="accordion1">
            <h3>Extra voorwaarden</h3>
            <table style="width:100%;">
                <tr>
            <td>
            Laptop gratis handbagage:
            </td>
            <td>
            <?php if( $airline->classes[$_GET["class"]]->LaptopAllowedHL == true) {echo 'ja';}else
            {
                echo 'nee';
            };?>
            </td>
                 <td>
            Pooling toegestaan:
            </td>
            <td>
            <?php if( $airline->classes[$_GET["class"]]->Pooling == true) {echo 'ja';}else
            {
                echo 'nee';
            };?>
            </td>
            </tr>
            
               <tr>
            <td>
            Rolstoel gratis:
            </td>
            <td>
            <?php if( $airline->classes[$_GET["class"]]->FreeWheelChair == true) {echo 'ja';}else
            {
                echo 'nee';
            };?>
            </td>
                 <td>
            Hulphond gratis:
            </td>
            <td>
            <?php if( $airline->classes[$_GET["class"]]->FreeServiceDog == true) {echo 'ja';}else
            {
                echo 'nee';
            };?>
            </td>
            </tr>
            <tr>
            <td> Waarde aangifte toegestaan:
                </td>
                <td><?php if($airline->classes[$_GET["class"]]->DeclarationOfValue == true){ echo 'ja';}else{echo 'nee';}?></td>
                <?php if($airline->classes[$_GET["class"]]->DeclarationOfValue == true){?>
                <td>
                Maximale waarde afgifte:
                </td>
                <td>&euro;
                <?php echo number_format($airline->classes[$_GET["class"]]->MaxDeclarationOfValue,2, ',', ' ');?>
                </td>
                    <?php }?>
                </tr>
                <tr>
                <td> Loyalty programma:
                </td>
                <td><?php if($airline->classes[$_GET["class"]]->LoyaltyProgramme == true){ echo 'ja';}else{echo 'nee';}?></td>
                <?php if($airline->classes[$_GET["class"]]->LoyaltyProgramme == true){?>
                <td>
                Extra koffers loyalty:
                </td>
                <td>
                <?php echo $airline->classes[$_GET["class"]]->LPextraPcsLuggage;?>
                </td>
                <tr>
                 <td>
                Extra gewicht loyalty:
                </td>
                <td>
                <?php echo $airline->classes[$_GET["class"]]->LPextraWeightLuggage;?>
                </td>
                   <td>
                Maximaal gewicht:
                </td>
                <td>
                <?php echo $airline->classes[$_GET["class"]]->AbsoluteMaxPerItem;?>
                </td>
                    <?php }?>
                </tr>
            
            </table>
            </div>
            <hr />
                   <div id="accordion2">
                   <h3>Huisdier</h3>
                           <table style="width:100%;">
                <tr>
                <td> Huisdier in ruim toegestaan:
                </td>
                <td><?php if($airline->classes[$_GET["class"]]->PetsAllowed == true){ echo 'ja';}else{echo 'nee';}?></td>
                <td> Huisdier in kabine toegestaan:
                </td>
                <td><?php if($airline->classes[$_GET["class"]]->petsAllowedHL == true){ echo 'ja';}else{echo 'nee';}?></td>
                </tr>
                <tr>
                                <?php if($airline->classes[$_GET["class"]]->PetsAllowed == true){?>
                <td>
                Max. totaal gewicht ruim:
                </td>
                <td>
                <?php echo $airline->classes[$_GET["class"]]->MaxWeightPet;?>
                </td>
                        <td> Max. afmetingen kooi ruim:</td>
                      <td>  <?php  $afmetingen = $airline->
                classes[$_GET["class"]]->MaxWeightPet ? $airline->classes[$_GET["class"]]->MaxWeightPet.'cm' : $airline->
                classes[$_GET["class"]]->sizeLenghtPet . 'cm x ' . $airline->classes[$_GET["class"]]->sizeWidthPet .
                'cm x ' . $airline->classes[$_GET["class"]]->sizeHeightPet.'cm';
                echo $afmetingen;
            ?></td>
                    <?php }?>
                </tr>
                </table>
                   </div>
            
            </div>
            
    </div>
    
    </body>
    <script src="js/jquery-1.9.0.min.js"></script>
<script src="js/jquery-ui.js"></script>
<script src="js/grid.locale-nl.js" type="text/javascript"></script>
<script src="js/jquery.jqGrid.min.js" type="text/javascript"></script>
<script src="js/javascript.js"></script>
    <script type="text/javascript">
  $(function() {
    $( "#accordion" ).accordion({
      collapsible: true,
      heightStyle: "content",
        active: false
    });
  });
   $(function() {
    $( "#accordion1" ).accordion({
      collapsible: true,
      heightStyle: "content",
        active: false
    });
  });
   $(function() {
    $( "#accordion2" ).accordion({
      collapsible: true,
      heightStyle: "content",
        active: false
    });
  });
  </script>
  
    </html>