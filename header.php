<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<link rel="stylesheet" type="text/css" href="style.css">
<title>Remote Phone ITL Eraser</title>

<html lang="en">
<link rel="stylesheet" type="text/css" href="style.css">
<table class="header">
<?php

//error_reporting(E_ALL);
//ini_set('display_errors', True);

$current_url = (!empty($_SERVER['HTTPS'])) ? "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] : "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];

	echo "<table align='left' border='0' width=100%>";
	echo "<form method='post' action='auth.php'  enctype='multipart/form-data'>";
        echo "<td width=600><p class='logo'>Remote Phone ITL Eraser</td>";
        echo "<td><table align=right border=0 width=600><tr><td>";
	echo "</tr></table>";
	echo "</form>";
	echo "</table>";

?>
</table>
</html>
