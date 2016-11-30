<?php
//include("SantaMaker.php");

/**
 * Connect to the mysql database.
 */
$conn = mysql_connect("localhost", user, password) or die(mysql_error());
mysql_select_db('XmasAtAudreys', $conn) or die(mysql_error());

?>
