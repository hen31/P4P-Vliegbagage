<?php
//Alle data classes includen
require_once("../data/includeAll.php");

$titel = "Administratie";
require_once("bovenkant.php");
?>

<div class="welkom">
Hallo <?php echo $_SESSION["user"]->userName;?>,<br />
U kunt in het menu bovenin het scherm kiezen waar u naar toe wilt gaan.</div>

<script src="../js/jquery-1.9.0.min.js"></script>
<script src="../js/jquery-ui.js"></script>
<script src="../js/grid.locale-nl.js" type="text/javascript"></script>
<script src="../js/jquery.jqGrid.min.js" type="text/javascript"></script>
<script src="../js/javascript.js"></script>

<?php
include_once("../admin/onderkant.php");
?>