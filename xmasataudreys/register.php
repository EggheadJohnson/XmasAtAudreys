<?php
session_start(); 
include("database.php");

/**
 * Returns true if the username has been taken
 * by another user, false otherwise.
 */
function usernameTaken($username){
   global $conn;
   if(!get_magic_quotes_gpc()){
      $username = addslashes($username);
   }
   $q = "select username from users where username = '$username'";
   $result = mysql_query($q,$conn);
   //return $result;
   //echo $result;
   //echo mysql_numrows($result);
   return (mysql_numrows($result) > 0);
}

function realnameTaken($realname){
   global $conn;
   
   $q = "select RealName from users where RealName = '$realname'";
   $result = mysql_query($q,$conn);
   //return $result;
   //echo $result;
   //echo mysql_numrows($result);
   return (mysql_numrows($result) > 0);
}

/**
 * Inserts the given (username, password) pair
 * into the database. Returns true on success,
 * false otherwise.
 */
function addNewUser($username, $password, $RealName){
   global $conn;
   $q = "INSERT INTO users VALUES ('$username', '$password', '$RealName', NULL, NULL)";
    
   $_SESSION['username']=$username;
   return mysql_query($q,$conn);
}

/**
 * Displays the appropriate message to the user
 * after the registration attempt. It displays a 
 * success or failure status depending on a
 * session variable set during registration.
 */
function displayStatus(){
   $uname = $_SESSION['reguname'];
   if($_SESSION['regresult']){
?>

<h1>Registered!</h1>
<p>Thank you <b><?php echo $uname; ?></b>, your information has been added to the database, you may now <a href="/PersonalHome.php" title="Home">visit your homepage</a>.</p>

<?php
   }
   else{
?>

<h1>Registration Failed</h1>
<p>We're sorry, but an error has occurred and your registration for the username <b><?php echo $uname; ?></b>, could not be completed.<br>
Please try again at a later time.
Return to <a href="/main.php" title="Login">log in</a></p>

<?php
   }
   unset($_SESSION['reguname']);
   unset($_SESSION['registered']);
   unset($_SESSION['regresult']);
}

if(isset($_SESSION['registered'])){
/**
 * This is the page that will be displayed after the
 * registration has been attempted.
 */
?>

<html>
<title>Registration Page</title>
<body>

<?php displayStatus(); ?>

</body>
</html>

<?php
   return;
}

/**
 * Determines whether or not to show to sign-up form
 * based on whether the form has been submitted, if it
 * has, check the database for consistency and create
 * the new account.
 */
if(isset($_POST['subjoin'])){
   /* Make sure all fields were entered */
   if(!$_POST['user'] || !$_POST['pass'] || ($_POST['RealName']=='UNSET')){
      die('You didn\'t fill in a required field.');
   }

   /* Spruce up username, check length */
   $_POST['user'] = trim($_POST['user']);
   if(strlen($_POST['user']) > 30){
      die("Sorry, the username is longer than 30 characters, please shorten it.");
   }

   /* Check if username is already in use */
   if(usernameTaken($_POST['user'])){
      $use = $_POST['user'];
      die("Sorry, the username: <strong>$use</strong> is already taken, please pick another one.");
   }
   if(realnameTaken($_POST['RealName'])){
       $use = $_POST['RealName'];
       die("Sorry, the name: <strong>$use</strong> is already taken, please choose your own name or let Paul know if this is in error.");
   }

   /* Add the new account to the database */
   $md5pass = md5($_POST['pass']);
   $_SESSION['reguname'] = $_POST['user'];
   $_SESSION['regresult'] = addNewUser($_POST['user'], $md5pass, $_POST['RealName']);
   $_SESSION['registered'] = true;
   echo "<meta http-equiv=\"Refresh\" content=\"0;url=$_SERVER[PHP_SELF]\">";
   return;
}
else{
/**
 * This is the page with the sign-up form, the names
 * of the input fields are important and should not
 * be changed.
 */
?>

<html>
<title>Registration Page</title>
<body style="background-color:FFA500">
<div id='container' style="width:1100px">

<div id="header" style="background-color:#339933;">
    <h1>Register</h1>
</div>
<div id='instruction'>
    <h3>Enter a username and password then use the list on the right to select your name.</h3>
</div>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<div id="menu" style="background-color:#E60000;height:300px;width:225px;float:left;">
Username:<input type="text" name="user" maxlength="30">
Password:<input type="password" name="pass" maxlength="30">
Back to <a href="/main.php">main</a>
    </div>
<div style="background-color:#EEEEEE;" class="field form-inline radio">
  
  <label class="radio" for="txtContact"><h4>Yeah, but who are you really?</h4></label>
  <input class="radio" type="hidden" name="RealName" value="UNSET" />
  <input class="radio" type="radio" name="RealName" value="Amy" /> <span>Amy</span><br>
  <input class="radio" type="radio" name="RealName" value="Christina" /> <span>Christina</span><br>
  <input class="radio" type="radio" name="RealName" value="Courtney" /> <span>Courtney</span><br>
  <input class="radio" type="radio" name="RealName" value="David" /> <span>David</span><br>
  <input class="radio" type="radio" name="RealName" value="Divah" /> <span>Divah</span><br>
  <input class="radio" type="radio" name="RealName" value="Jackie" /> <span>Jackie</span><br>
  <input class="radio" type="radio" name="RealName" value="Lee" /> <span>Lee</span><br>
  <input class="radio" type="radio" name="RealName" value="Paul" /> <span>Paul</span><br>
  <input class="radio" type="radio" name="RealName" value="Elizabeth" /> <span>Elizabeth</span><br>
  <input class="radio" type="radio" name="RealName" value="Wayne" /> <span>Wayne</span><br>
  <input class="radio" type="radio" name="RealName" value="Lynda" /> <span>Lynda</span><br>
  <input class="radio" type="radio" name="RealName" value="Wendy" /> <span>Wendy</span><br>
  
  
</div>
<input type="submit" name="subjoin" value="Join!"><br>
<img src="http://www.world-insider.com/wp-content/uploads/2012/11/caganer.jpg" alt="Bad ass caganer" width="189" height="338" title="Bad ass caganer">
</form>
</div>
</body>
</html>


<?php
}
?>
