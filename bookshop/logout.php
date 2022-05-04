<?php 	
	require("connection.php");
 ?>
<?php 
	session_destroy();
	echo '<meta http-equiv=Refresh content="0; index.php">';
 ?>