<!DOCTYPE HTML>
<html>
    <head>
	   <meta http-equiv="content-type" content="text/html" />
	   <title>Vliegbagage.nl | Home</title>
       <link href="style.css" type="text/css" rel="stylesheet"/>
    </head>
    <body>
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
            <form name="form1" action="formmail.php" method="get">
                <input type="hidden" name="recipient" value="info@vliegbagage.nl"> 
		        <input type="hidden" name="subject" value="Infoaanvraag via VLIEGBAGAGE.NL "> 
		        <input type="hidden" name="redirect" value="http://www.vliegbagage.nl/bedankt.html" target="_blank"> 
                <input type="hidden" name="env_report" value="REMOTE_HOST,REMOTE_ADDR,HTTP_USER_AGENT">

          <table border="0" width="100%">
           <tr>
              <td width="27%">Naam: </td>
             <td width="73%"><input name="naam" type="text" id="naam" size="30"></td>
            </tr>
            
           
             
            <tr>
              <td>Email:</td>
              <td><input name="email" type="text" id="email" size="30"></td>
            </tr>
          
            <tr>
              <td>Waarvandaan vertrekt U? </td>
	      <td valign="top"><input name="vertrek" type="text" id="vertrek" size="30"></td>
            </tr>
                       <tr>
              <td>Waar vliegt U naar toe? </td>
              <td valign="top"><input name="bestemming" type="text" id="bestemming" size="30"></td>
            </tr>
              <tr>
              <td>Met welke maatschappij reist U? </td>
              <td valign="top"><input name="maatschappij" type="text" id="maatschappij" size="30"><input name="Maatschappij nog onbekend" type="checkbox" value="Maatschappij nog onbekend" />Onbekend
   </td>
            </tr>
               <tr>
              <td>In welke klasse vliegt U?</td>
              <td valign="top"><input name="klasse" type="text" id="klasse" size="30"></td>
            </tr>
               <tr>
              <td>Heeft u bijzondere bagage bij zich? (rolstoel, sportartikelen, huisdier bijv.)</td>
              <td valign="top"><input name="bijzondere bagage" type="text" id="bijzondere bagage" size="30"></td>
            </tr>
           
         <tr>
              <td valign="top">Opmerkingen:</td>
              <td><textarea name="textarea" cols="35" rows="10"></textarea></td>
            </tr>
            <tr>
              <td></td>
              <td>
                <input name="Verzend" type="submit" id="Verzend" value="Verzend">
             <input name="Verzend2" type="reset" id="Wis" value="Wis" /></td>
            </tr>
          </table>
</form></div>
<div id="footer">
www.vliegbagage.nl - info@vliegbagage.nl<br/>
</div>
</div>

</body>
</html>