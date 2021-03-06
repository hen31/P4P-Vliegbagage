<?php
session_start();
header('Content-Type: text/html; charset=UTF-8');
if ((!isset($_SESSION["user"]) || $_SESSION["user"] == null) && !DEBUG) {
    header("Location: login.php");
    exit();
} ?>
<!DOCTYPE HTML>
<html>
    <head>
	   <meta http-equiv="content-type" content="text/html" />
	   <title>Vliegbagage.info | <?php if (isset($titel)) {
    echo $titel;
} ?></title>
       <link href="../style.css" type="text/css" rel="stylesheet"/>
       <link rel="stylesheet" type="text/css" media="screen" href="../css/ui.jqgrid.css" />
       <link rel="stylesheet" type="text/css" media="screen" href="../css/jquery-ui.css" />
       <link rel="stylesheet" type="text/css" media="screen" href="../css/jquery-ui-1.10.3.custom.css" />
    </head>
    <body>
    <div id="container">
        <div id="header"></div>
        <div class="name">
            <a href="airline.php">Luchtvaartmaatschappijen</a>
            <a href="airports.php">Vliegvelden</a>
            <a href="trajecten.php">Trajecten</a>
            <a href="specialluggage.php">Speciale bagage</a>
            <a href="users.php">Gebruikers</a>
            <a href="loguit.php">Uitloggen</a>
            <h1>VLIEGBAGAGE.INFO</h1>
        </div>
        <div id="content">