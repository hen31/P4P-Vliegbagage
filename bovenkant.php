<?php
header('Content-Type: text/html; charset=UTF-8');
session_start();
?>
<!DOCTYPE HTML>
<html>
    <head>
	   <meta http-equiv="content-type" content="text/html" />
	   <title>Vliegbagage.nl | <?php if (isset($titel)) {
    echo $titel;
} ?></title>
       <link href="style.css" type="text/css" rel="stylesheet"/>
       <link rel="stylesheet" type="text/css" media="screen" href="css/ui.jqgrid.css" />
       <link rel="stylesheet" type="text/css" media="screen" href="css/jquery-ui.css" />
       <link rel="stylesheet" type="text/css" media="screen" href="css/jquery-ui-1.10.3.custom.css" />
    </head>
    <body>
    <div id="container">
        <div id="header"></div>
        <div class="name">
            <a href="index.php">Home</a>
            <a href="contact.php">Contact</a>
            <h1>VLIEGBAGAGE.NL</h1>
        </div>
        <div id="content">