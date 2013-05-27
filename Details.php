<?php
session_start();
//Alle data classes includen
require_once ("data/includeAll.php");
require_once ("data/frontend.php");
$titel = "Details van ";

if(!isset($_GET["name"]))
{
    exit();
}
else
{
    $titel .= $_GET["name"];
}
if(airline::airline_name_exists($_GET["name"]))
{
    airline::get_airline_by_name($_GET["name"]);
}
else
{
    exit;
}

?>
<!DOCTYPE HTML>
<html>
    <head>
	   <meta http-equiv="content-type" content="text/html" />
	   <title>Vliegbagage.nl | <?php if(isset($titel)){ echo $titel;}?></title>
       <link href="style.css" type="text/css" rel="stylesheet"/>
       <link rel="stylesheet" type="text/css" media="screen" href="css/ui.jqgrid.css" />
       <link rel="stylesheet" type="text/css" media="screen" href="css/jquery-ui.css" />
       <link rel="stylesheet" type="text/css" media="screen" href="css/jquery-ui-1.10.3.custom.css" />
    </head>
    <body>
    <div id="container">
            <div id="content">
            <div class="airLogo"><img src="images/airlines/<?php echo $airline->logo;?>" /></div>
            <div class="AirlineName"><h2>
            <?php 
            echo $airline->name;?>
            </h2>
            </div>
            </div>
    </div>
    </body>
    </html>