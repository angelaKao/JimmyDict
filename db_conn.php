<?php
$dbhost = '127.0.0.1';
$dbuser = 'root';
$dbpass = 'angela0323';
$dbname = 'dictionary';
$conn = mysql_connect($dbhost, $dbuser, $dbpass) or die('Error with MySQL connection');
mysql_query("SET NAMES 'utf8'");
mysql_select_db($dbname);

?>
