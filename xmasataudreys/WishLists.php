<?php
session_start(); 
include("database.php");
include("SantaMaker.php");
global $conn;
$people = blankSlate();

?>

<html>
<body>

<div id='container' style="width:1100px">
    <h1> Welcome to the wishlists!</h1>
    Go back to your <a href="/PersonalHome.php">homepage</a>
<div id="header" style="background-color:#339933;">
<div id="leftColumn" style="width:550px;float:left">
    
    <?php

        for ($x = 0; $x<6; $x++){
            $person = $people[$x];
            $q = "select username, wishlist from users where RealName='$person';";
            $result = mysql_query($q, $conn);
            $row = mysql_fetch_row($result);
            if (mysql_numrows($result) == 0){ $greeting = $person." hasn't set up their account yet!"; }
            else{ $greeting = $person.", a.k.a. ".$row[0]."'s wishlist: "; }
            if ((mysql_numrows($result) > 0) and !empty($row[1])){ $message = $row[1]; }
            else{ $message = $person . "'s wishlist is empty"; }
            if($x%2 == 1){

    ?>
    <div id="person" style="width:550px;height:400px;background-color:#009900">
    
        <h3> <?php echo $greeting ?>    </h3><br>
        <?php echo $message ?>
    
    </div>
    <?php }
        else{
    ?>
            <div id="person" style="width:550px;height:400px;background-color:#CC0000">
    
        <h3> <?php echo $greeting ?>    </h3><br>
        <?php echo $message ?>
    
    </div>
    <?php } } ?>
</div>
<div id="rightColumn" style="width:550px;float:left">
<?php 
    for ($x = 6; $x<12; $x++){
            $person = $people[$x];
            $q = "select username, wishlist from users where RealName='$person';";
            $result = mysql_query($q, $conn);
            $row = mysql_fetch_row($result);
            if (mysql_numrows($result) == 0){ $greeting = $person." hasn't set up their account yet!"; }
            else{ $greeting = $person.", a.k.a. ".$row[0]."'s wishlist: "; }
            if ((mysql_numrows($result) > 0) and !empty($row[1])){ $message = $row[1]; }
            else{ $message = $person . "'s wishlist is empty"; }
            if($x%2 == 1){

    ?>
    <div id="person" style="width:550px;height:400px;background-color:#CC0000">
    
        <h3> <?php echo $greeting ?>    </h3><br>
        <?php echo $message ?>
    
    </div>
    <?php }
        else{
    ?>
            <div id="person" style="width:550px;height:400px;background-color:#009900">
    
        <h3> <?php echo $greeting ?>    </h3><br>
        <?php echo $message ?>
    
    </div>
    <?php } } ?>
    
</div>
</body>
</html>
