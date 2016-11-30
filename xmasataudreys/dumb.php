<?php


$conn = mysql_connect("localhost", username, password) or die(mysql_error());
mysql_select_db('XmasAtAudreys', $conn) or die(mysql_error());
function dumbgo(){
	echo "dumb";
}

$q = "select username from users where username='paul';";

$result = mysql_query($q, $conn);

$row = mysql_fetch_row($result);
echo $row[0];


?>
