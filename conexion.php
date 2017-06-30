<?php $dbcon = mysql_connect("localhost","root","produciondb@2013"); 
if (!$dbcon) { 
	die('Could not connect: ' . mysql_error()); } 
	mysql_select_db("dbtri", $dbcon);

	$result = mysql_query("SELECT * FROM producciondasdas");
das
	echo print($result);

	 while($row = mysql_fetch_array($result)) { 
	 	echo $row['user']; echo " "; 
	 }