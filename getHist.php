<?php
session_start();

$curr1 = 'USD';
if(isset($_SESSION["BASE"]))
{
	$curr1 = $_SESSION["BASE"];
}

$curr2 = trim($_POST['curr2']);

require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

$client = new rabbitMQClient("testRabbitMQ.ini","testServer");
$listReq = array();
$listReq['type'] = "get_history";
$listReq['curr1'] = $curr1;
$listReq['curr2'] = $curr2;
$list = $client->send_request($listReq);

$values = array(
    array($curr1, $curr2),
    array('1', 0),
    array('2', 0),
    array('3', 0),
    array('4', 0),
    array('5', 0),
    array('6', 0),
    array('7', 0),
    array('8', 0),
    array('9', 0),
    array('10', 0)
);

for ($i = 0; $i < count($values); $i++) {

	if($i !== 0)
	{
		$values[$i][1] = floatval($list[$i-1]);
	}

} 

echo json_encode($values);
exit;

/*
	$values = array(
        array($curr1, $curr2),
        array('1'),
        array('2'),
        array('3'),
        array('4'),
        array('5'),
        array('6'),
        array('7'),
        array('8'),
        array('9'),
        array('10')
    );

	$first = True;
	$i = 0;
	foreach ($values as $point)
	{
		if(!$first)
		{
			array_push($point, floatval($list[$i]));
			//$value[1] = floatval($list[$i]);
			$i++; 
		}
		$first = False;
	}*/

?>