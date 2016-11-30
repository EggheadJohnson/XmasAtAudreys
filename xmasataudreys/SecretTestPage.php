<?php 

session_start();
include("database.php");
include("SantaMaker.php");
global $conn;
$uname = $_SESSION['username'];
$q = "select RealName from users where username='$uname';";
$RN = mysql_query($q,$conn);
$row = mysql_fetch_row($RN);
$q = "select wishlist from users where username='$uname'";
$WL = mysql_query($q, $conn);
$WLRow = mysql_fetch_row($WL);
//echo $row[0];
$q = "select Receiver from giveceivers where Giver='$row[0]'";
$receiver = mysql_query($q, $conn);
$receiverRow = mysql_fetch_row($receiver);
$IGiveTo = decoder($receiverRow[0]);
//echo "<br>";
//echo $IGiveTo;


?>
<title><?php echo $row[0]; ?>'s personal homepage</title>
<body style="background-color:#FFA500;">
    
    
<h1 style="background-color:339933">Welcome, <b> <?php echo $uname; ?> </b></h1>
<div id="countdown" style="height:57px; width:1100px;">
        <iframe src="http://free.timeanddate.com/countdown/i3wyhgv6/n137/cf11/cm0/cu4/ct0/cs0/ca0/cr0/ss0/cac000/cpc000/pcf00/tcfff/fs100/szw320/szh135/tatTime%20left%20to%20the%20exchange!/tac000/tptTime%20since%20Event%20started%20in/tpc000/matI%20can't%20wait!/mac090/mpc000/iso2013-12-28T12:00:00" frameborder="0" width="258" height="57"></iframe>

    
</div>
<div id="about you" style="height:800px;width:600px;float:left;">
See everyone else's <a href="/WishLists.php"> Wishlist!	</a>
<h2 style="background-color:#E60000">Give your secret santa some ideas</h2>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<textarea name='wishlist' rows="20" cols="50"><?php echo $WLRow[0]; ?>
</textarea><br>

<input type="submit" name="update" value="Save wishlist!">
<br>
<h4>In case you need to reset your password:</h4>
Old password: <input type="password" name="oldpass" maxlength="32"><br>
New password: <input type="password" name="mynewpass" maxlength="32"><br>

<input type="submit" name="newpass" value="Set New Password">

    
    
</form>
<a href="/logout.php"> Logout</a>

<?php

if (isset($_POST['newpass'])){
    $q = "select password from users where username = '$uname';";
    $result = mysql_query($q, $conn);
    $row = mysql_fetch_row($result);
    $oldpass = $row[0];
    if ($oldpass == md5($_POST['oldpass'])){
        $myNewPass = md5($_POST['mynewpass']);
        
        $q = "update users set password='$myNewPass' where username='$uname'";
        
        $result = mysql_query($q, $conn);
        $_SESSION['password'] = $myNewPass;
        if ($result){ echo "Password Reset"; }
        echo "<meta http-equiv=\"Refresh\" content=\"1;url=$_SERVER[PHP_SELF]\">";
    }
}

if ($row[0] == 'Paul'){
    ?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <input type="submit" name="reset" value="MASTER RESET">
    </form>
<a href="/AdminPage.php">Paul's Secret Admin</a>

<?php
}
if (isset($_POST['update'])){
    
    #$q = " wishlist from users where username='$uname'";
    $mywishes = $_POST['wishlist'];
    $q = "update users set wishlist='$mywishes' where username='$uname'";
    $UpdatedWL = mysql_query($q, $conn);
    echo "<meta http-equiv=\"Refresh\" content=\"0;url=$_SERVER[PHP_SELF]\">";
    return;
}
if (isset($_POST['reset'])){
    run();
    echo "<meta http-equiv=\"Refresh\" content=\"0;url=$_SERVER[PHP_SELF]\">";
}

?>
<br>
<img src="http://beefeaters.com/uploads/santas_little_helper.png" alt="W3Schools.com" width="420" height="294">

</div>
<div id="about your receiver" style=";height:800px;width:500px;float:left;"><br>
    <h2 style="background-color:#009933">You are giving to <?php echo $IGiveTo;   ?> </h2>

<?php
    $q = "select wishlist from users where RealName = '$IGiveTo'";
    $result = mysql_query($q,$conn);
    $q = "select wishlist from users where RealName='$IGiveTo'";
    $WLRec = mysql_query($q, $conn);
    $WLRecRow = mysql_fetch_row($WLRec);
    $WLRecWish = $WLRecRow[0];
    $WLRecWish = trim($WLRecWish);
    if ((mysql_numrows($result) > 0) and !empty($WLRecWish)){ 
?>

<h3 style="background-color:#E60000"><?php echo $IGiveTo;   ?>'s Wishlist: </h3>
        <div id="wishlist" style="background-color:#E4E4E6;height:400px;width:500px;float:left;">
        <?php echo $WLRecWish; ?>
        </div>
<?php }
    else {
?>
    <h3 style="background-color:#E60000"><?php echo $IGiveTo; ?> has not yet set a wishlist</h3>
<?php } ?>

<img src="http://cdn.someecards.com/someecards/filestorage/particularly-care-youve-naughty-christmas-ecard-someecards.jpg" alt="W3Schools.com" width="420" height="294">
</div>
</body>
</html>
