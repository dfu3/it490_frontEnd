
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');
include('home.php');

$client = new rabbitMQClient("testRabbitMQ.ini","testServer");

$user = $_GET["username"];
$pass = $_GET["password"];

$request = array();
$request['type'] = "login";
$request['username'] = $user;
$request['password'] = $pass;
$response = $client->send_request($request);

if($response == 'SUCC')
{
	session_start();
	$_SESSION["USER"] = $user;
	header("location: index.php");
}

elseif($response == 'FAIL')
{
	echo "<h2>User or Password Incorrect :(</h2>";
}
else
{
	echo $response;
}
