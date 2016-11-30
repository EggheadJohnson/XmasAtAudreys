<?php

$myurl = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=37.766837,-122.248952&destinations=37.507111,-122.247761&key=AIzaSyDymBv45P1YZEhHB-lY8OkfRMmY1tdnlTY";

$response = file_get_contents($myurl);

$response = json_decode($response, true);

$time = $response["rows"][0]["elements"][0]["duration"]["text"];
//var_dump($response);
$time = explode(" ", $time);
settype($time[0], "int");
$time = $time[0];
echo $time;
var_dump($time);


?>
