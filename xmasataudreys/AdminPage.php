<?php

include("database.php");
include("SantaMaker.php");

global $conn;
$q = "select username, RealName from users;";
$result = mysql_query($q, $conn);
//$DBRows = mysql_fetch_row($DB);
?>
<html>
<body>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<table border="1">
    <tr><th>Username</th><th>Real Name</th></tr>
   

<?php
while($row = mysql_fetch_assoc($result)){
    

?>
    <tr>

        <td><?php echo $row['username'];?> </td>
        <td><?php echo $row['RealName'];?> </td>
    

    </tr>

<?php }?>


</table>
    
        Username:<input type="text" name="user" maxlength="30">
        <input type="submit" name="update" value="Update"><br>
        <input type="submit" name="showGiveC" value="You don't want to do this!">
    
    

    
<?php

if (isset($_POST['update'])){
    
    //echo $_POST['user'];
    $user = $_POST['user'];
    $q = "select username, RealName from users where username = '$user';";
    $result = mysql_query($q,$conn);
    $row = mysql_fetch_row($result);
    $user = $row[0];
    $name = $row[1];
    //echo $user;
    


?>
    <br>
    <table>
        <tr><td>Username:</td><td><input type="text" name="user" maxlength="30" value= <?php echo $user; ?> ></td></tr>
        <tr><td>Real name:</td><td><input type="text" name="name" maxlength="30" value= <?php echo $name; ?> ></td></tr>
        <tr><td>Don't change:</td><td><input type="text" name="lazypaulkeeper" maxlength="30" value= <?php echo $user; ?> ></td></tr>
        <tr>
            <td><input type="submit" name="fixfortards" value="Update"></td>
            <td><input type="submit" name="resetpassword" value="Reset Password"></td></tr>
    </table>
    

<?php
}
if (isset($_POST['fixfortards'])){
    echo "Tard Saved";
    $changing = $_POST['lazypaulkeeper'];
    $q = "select username, RealName from users where username = '$changing';";
    $result = mysql_query($q,$conn);
    $row = mysql_fetch_row($result);
    $newUserName = $_POST['user'];
    $newRealName = $_POST['name'];
    //$changes = array($row[0] => $_POST['user'], $row[1] => $_POST['name']);
    $q = "update users set username='$newUserName', RealName='$newRealName' where username='$changing'";
    $UpdatedWL = mysql_query($q, $conn);
    echo "<meta http-equiv=\"Refresh\" content=\"1;url=$_SERVER[PHP_SELF]\">";
    
    
}
if (isset($_POST['resetpassword'])){
    echo "Password reset";
    $changing = $_POST['lazypaulkeeper'];
    $newPass = md5('paulrules');
    $q = "update users set password='$newPass' where username='$changing'";
    $UpdatedWL = mysql_query($q, $conn);
    echo "<meta http-equiv=\"Refresh\" content=\"1;url=$_SERVER[PHP_SELF]\">";
}
if (isset($_POST['showGiveC'])){
    $q = "select Giver, Receiver from giveceivers;";
    $result = mysql_query($q, $conn);
/*
$q = "select Receiver from giveceivers where Giver='$row[0]'";
$receiver = mysql_query($q, $conn);
$receiverRow = mysql_fetch_row($receiver);
$IGiveTo = decoder($receiverRow[0]);
*/
    
?>
    <table border="1">
    <tr><th>Givers</th><th>Receivers</th></tr>
   

<?php
while($row = mysql_fetch_assoc($result)){
    $giver = $row['Giver'];
    $receiver = decoder($row['Receiver']);

?>
    <tr>

        <td><?php echo $giver;?> </td>
        <td><?php echo $receiver;?> </td>
    

    </tr>

<?php }?>


</table>
<?php }?>
    

</form>
    Go to your <a href="/PersonalHome.php">homepage</a>
</body>
</html>

    
