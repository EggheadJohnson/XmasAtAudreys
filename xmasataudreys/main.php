<?php 

/* Include Files *********************/
//session_save_path('include/session_store');
session_start(); 
include("database.php");
include("login.php");
include("SantaMaker.php");
//checker();
/*************************************/
//echo session_save_path();

?>

<html>
<title>Hawkins Family Secret Santa</title>
<body style="background-color:#FFA500;">
    
<div id='container' style="width:1133px">
<div id="header" style="background-color:#339933;">
    <h1 style:"margin-bottom:0;">Welcome to the Clan Audrey Secret Santa page!</h1>
</div>
<div id="menu" style="background-color:#E60000;height:300px;width:258px;float:left;">
<?php displayLogin(); ?>
<!--    <iframe src="http://free.timeanddate.com/countdown/i3wyhgv6/n137/cf11/cm0/cu4/ct0/cs0/ca0/cr0/ss0/cac000/cpc000/pcf00/tcfff/fs100/szw320/szh135/tatTime%20left%20to%20the%20exchange!/tac000/tptTime%20since%20Event%20started%20in/tpc000/matI%20can't%20wait!/mac090/mpc000/iso2013-12-28T12:00:00" frameborder="0" width="258" height="57"></iframe>
-->
</div>
<div id="content" style="background-color:#EEEEEE;height:300px;width:875px;float:left;">
<img src="http://www.coolholidaygraphics.com/christmas/clipart/trees/x3_palm1.gif" alt="Bad ass tree" width="104" height="142" title="Bad ass tree">
<img src="http://www.coolholidaygraphics.com/christmas/clipart/trees/x3_palm1.gif" alt="Bad ass tree" width="104" height="142" title="Bad ass tree">
<img src="http://www.coolholidaygraphics.com/christmas/clipart/trees/x3_palm1.gif" alt="Bad ass tree" width="104" height="142" title="Bad ass tree">
<img src="http://www.coolholidaygraphics.com/christmas/clipart/trees/x3_palm1.gif" alt="Bad ass tree" width="104" height="142" title="Bad ass tree">
<img src="http://www.coolholidaygraphics.com/christmas/clipart/trees/x3_palm1.gif" alt="Bad ass tree" width="104" height="142" title="Bad ass tree">
<img src="http://www.coolholidaygraphics.com/christmas/clipart/trees/x3_palm1.gif" alt="Bad ass tree" width="104" height="142" title="Bad ass tree">
<img src="http://www.coolholidaygraphics.com/christmas/clipart/trees/x3_palm1.gif" alt="Bad ass tree" width="104" height="142" title="Bad ass tree">
<img src="http://www.coolholidaygraphics.com/christmas/clipart/trees/x3_palm1.gif" alt="Bad ass tree" width="104" height="142" title="Bad ass tree">
<br>
    <h3>Rules:</h3>
    <ol>
    <li>Everyone gives to one person</li>
    <li>The dollar limit is $50</li>
    <li>We will all give them when we celebrate Christmas in Lakewood</li>
    </ol>

</div>


</div>
    
<div style="width:1133px; position:absolute; bottom: 5px; text-align:center;">
5419 Dunrobin &middot; Lakewood, CA 90713
    
</div>
    
</body>
</html>
