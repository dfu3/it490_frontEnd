<?php
session_start();


if(!isset($_SESSION["BASE"]))
{
	$_SESSION["BASE"] = "USD";
}
if(!isset($_SESSION["curr2"]))
{
	$_SESSION["curr2"] = "EUR";
}


$base = $_SESSION["BASE"];
$homeReq['type'] = "get_ex_for_base";
$homeReq['base'] = $base;
$table = $client->send_request($homeReq);

echo $table;

?>