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
            <a href="index.php">Home</a>
            <a href="contact.php">Contact</a>
            <h1>VLIEGBAGAGE.NL</h1>
        </div>
        <div id="content">