<?php
include("database.php");
//include("login.php");



function sigOMaker(){ 
return array(
    'Christina' => array('Paul', 'Christina','Amy'),
    'Paul' => array('Christina', 'Paul','Jackie'),
    'Divah' => array('David', 'Divah','Wendy'),
    'David' => array('Divah', 'David','Paul'),
    'Amy' => array('Jackie', 'Amy','Courtney'),
    'Jackie' => array('Amy', 'Jackie','David'),
    'Courtney' => array('Lee', 'Courtney','Elizabeth'),
    'Lee' => array('Courtney', 'Lee','Divah'),
    'Wayne' => array('Elizabeth', 'Amy', 'Christina', 'David', 'Jackie', 'Paul', 'Divah', 'Wayne','Lynda'),
    'Elizabeth' => array('Wayne', 'Amy', 'Christina', 'David', 'Jackie', 'Paul', 'Divah', 'Elizabeth','Lee'),
    'Wendy' => array('Lee', 'Courtney', 'Wendy','Wayne'),
    'Lynda' => array('Lynda','Christina')
);
}
/*
function sigOMaker(){ 
return array(
    array('Christina', 'Paul'),
    array('Divah', 'David'),
    array('Amy', 'Jackie'),
    array('Courtney', 'Lee'),
    array('Wayne', 'Elizabeth'));
}*/

/*
*  The function blankSlate is to deliver a new list of people. 
*  This way I don't have to worry about accidentally ruining my original list since I will be deleting people
*  As they are selected for gift reception.
*/
/*$sigOPairs = sigOMaker();
for ($x = 0; $x<4; $x++){
    echo $sigOPairs[$x][0]." is with ".$sigOPairs[$x][1]."\n";
}
*/
function blankSlate(){
    return array('Christina', 'Paul', 'Divah', 'David', 'Amy', 'Jackie', 'Courtney', 'Lee', 'Lynda', 'Wendy', 'Wayne', 'Elizabeth');
}

function checker(){
    global $conn;
    $q = "select * from giveceivers where Giver='Paul';";
    $val = mysql_query($q, $conn);
    //$val = mysql_fetch_row($val)[0];
    if($val == FALSE){
        $q = "CREATE TABLE giveceivers (Giver varchar(20), Receiver varchar(32));";
        $val = mysql_query($q, $conn);
        
        foreach ( blankSlate() as $person ){
            $q = "INSERT INTO giveceivers VALUES ('$person',NULL)";
            mysql_query($q, $conn);
        }
    run();
    }
}





/*
*  The function check is to... check... if anyone is giving to their own significant other
*  in violation of the secret santa.
*  A return of true means the pairs will need to be created and a return of false means no problems were found.
*/
function check($assocArr){
    $sigOPairs = sigOMaker();
    foreach($assocArr as $giver => $receiver){
        if($receiver == NULL || $giver == $receiver){ return TRUE; }
    }
    foreach($assocArr as $giver => $receiver){
        $excluded = $sigOPairs[$giver];
        foreach($excluded as $test){
            if ($test == $receiver){ return TRUE; }
        }

    }

    return FALSE;
}

/*
function check($assocArr){
    $sigOPairs = sigOMaker();
    foreach($assocArr as $giver => $receiver){
        if($receiver == NULL || $giver == $receiver){ return TRUE; }
    }
    foreach($assocArr as $giver => $receiver){
        //echo "Checking ".$giver."\n";
        for ($x = 0; $x < 4; $x++){
            //echo "   Comparing to ".$sigOPairs[$x][0]."\n";
            if ($giver == $sigOPairs[$x][0] || $giver == $sigOPairs[$x][1]){
                //echo "I found ".$giver."\n";
                if ($receiver == $sigOPairs[$x][0] || $receiver == $sigOPairs[$x][1]){ return TRUE; }
            }
        }

    }

    return FALSE;
}
*/


/*
*  The function generateList creates an associative array of the pairs giver => receiver
*  The way I did this, I generate a potential list not worrying about violations.
*  Then I check the list for violations.  If a violation occurred, it scraps the list and starts over.
*  This can technically lead to an infinite loop.  The highest I have seen is 82.
*/

function generateList(){
    $giftPairs = array_fill_keys( blankSlate(), NULL );
    $counter = 1;
    while (check($giftPairs)){
        //echo $counter++." ";
        $peopleGiving = blankSlate();
        $peopleToDrawFrom = blankSlate();
        $peoplecount = 8;
        
        foreach ($peopleGiving as $giver){
            //echo count($peopleToDrawFrom);
            //echo "<br>";
            $receiverIndex = rand(0,count($peopleToDrawFrom)-1);
            //$giftPairs[$giver] = $peopleToDrawFrom[0];
            $giftPairs[$giver] = $peopleToDrawFrom[$receiverIndex];
            unset($peopleToDrawFrom[$receiverIndex]);
            
            
            $peopleToDrawFrom = array_values($peopleToDrawFrom);
        }
    }
    return $giftPairs;
}

/*
*  The function encodeList is designed to... encode the list... so that anyone with access to the list won't be 
*  able to read it and ruin the fun!
*  It only encodes the recipient for ease of use.
*/

function encodeList($assocArr){
    foreach ($assocArr as $giver => $receiver){
        $encodedReceiver = md5($receiver);
        $assocArr[$giver] = $encodedReceiver;
    }
    return $assocArr;
}

function decodeList($assocArr){
    foreach ( blankSlate() as $name){
        $hash[md5($name)] = $name;
    }
    foreach ($assocArr as $giver => $receiver){
        $decodedReceiver = $hash[$receiver];
        $assocArr[$giver] = $decodedReceiver;
    }
    return $assocArr;
}

function decoder($hashPerson){
    foreach ( blankSlate() as $name){
        if (md5($name) == $hashPerson){ return $name; }
    }
    return FALSE;
}

function writeToDB($assocArr){
    global $conn;
    foreach ($assocArr as $giver => $receiver){
        $q = "update giveceivers set Receiver='$receiver' where Giver='$giver'";
        mysql_query($q, $conn);
    }
}

function run(){
    $pairs = generateList();
    $pairs = encodeList($pairs);
    writeToDB($pairs);
}

/*
$pairs = generateList();
echo "\n";
foreach ($pairs as $giver => $receiver){
    echo $giver . " gives to " . $receiver . "\n";
}
$pairs = encodeList($pairs);
echo "\n";
foreach ($pairs as $giver => $receiver){
    echo $giver . " gives to " . $receiver . "\n";
}
$pairs = decodeList($pairs);
echo "\n";
foreach ($pairs as $giver => $receiver){
    echo $giver . " gives to " . $receiver . "\n";
}
*/

?>
