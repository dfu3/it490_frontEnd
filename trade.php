<?php
session_start();
echo '<title>Spafin</title>';

// FONTS

echo '<script src="js/jquery-2.1.3.min.js"></script>';
echo '<script src="js/bootstrap.min.js"></script>';
echo '<script src="js/myScripts.js"></script>';

// CSS

echo '<link rel="stylesheet" href="login.css">';
echo '<link rel="icon" href="/favicon.ico?v=2" type="image/x-icon"/>';
echo '<meta charset="utf-8">';
echo '<link rel="stylesheet" href="css/normalize.css">';
echo '<link rel="stylesheet" href="css/bootstrap.min.css">';
echo '<link rel="stylesheet" href="css/mystyle.css">';

// HEADER

echo "  <nav id=\"header\" class=\"navbar nav-blue navbar-fixed-top\">";
echo "    <div class=\"container-fluid\">";
echo "      <div class=\"navbar-header\">";
echo "        <button type=\"button\" class=\"navbar-toggle\" data-toggle=\"collapse\" data-target=\"#myNavbar\">";
echo "          <span class=\"underline-bar\"></span>";
echo "          <span class=\"underline-bar\"></span>";
echo "          <span class=\"underline-bar\"></span>";
echo "        </button>";
echo "        <a class=\"navbar-brand spafin theFade \" href=\"http://somethingpatheticallyawful.com/\">Spafin</a>";
echo "      </div>";
echo "      <div class=\"collapse navbar-collapse navbar-right allowUnderline\" id=\"myNavbar\">";
echo "        <ul class=\"nav navbar-nav\">";
echo "          <li class=\"active\"><a target=\"_blank\" class=\"theFade\" href=\"aboutus.html\">About Us</a></li>";
echo "          <li><a target=\"_blank\" class=\"theFade\" href=\"http://www.dowjones.com/\">Dow Jones</a></li>";
echo "          <li><a target=\"_blank\" class=\"theFade\" href=\"http://www.nasdaq.com/\">NASDAQ</a></li>";
echo "          <li><a target=\"_blank\" class=\"theFade\" href=\"http://money.cnn.com/data/markets/sandp/\">S&P 500</a></li>";
echo "          <li><a class=\"theFade\" href=\"login.html\">Log Out</a></li>";
echo "        </ul>";
echo "      </div>";
echo "    </div>";
echo "  </nav>";

// START PAGE LAYOUT

echo "  <div class=\"skewContainer\">";
echo "    <div class=\"body-container\">";
echo "";
echo "      <div class=\"container text-center\">";
echo "        <h1>Spafin</h1><br><br>";
if (isset($_SESSION["USER"]))
	{
	$user = $_SESSION["USER"];
	echo "<h3> Welcome " . $user . "</h3>";
	}
echo "      </div>";
echo "    </div>";
echo "";
echo "    <div class=\"body-background\">";
echo "      <div class=\"container text-center\" style=\"padding: 100px\">";

// TABLE CODE HERE

require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');
$client = new rabbitMQClient("testRabbitMQ.ini","testServer");
$curr1 = substr($_GET["from"], 0, 3);
$amount = $_GET["amount"];
$curr2 = substr($_GET["to"], 0, 3);

if(isset($_SESSION["USER"]))
{
	$user = $_SESSION["USER"];
}
else
{
	header("location: login.html");
	exit("no user");
}
$request = array();
$request['type'] = "make_trade";
$request['username'] = $user;
$request['curr1'] = $curr1;
$request['amount'] = $amount;
$request['curr2'] = $curr2;
$response = $client->send_request($request);
if($response == "SUCC")
{
	header("location: profile.php");
	exit();
}
elseif($response == "IF")
{
	$out = "<h1>INSUFFICIENT FUNDS</h1><br><br>";
	echo $out;
}


//////////////////////////////////////////////////////////

echo "      </div>";
echo "    </div>";
echo "    <div class=\"info-container\">";
echo "        <div class=\"container text-center\">";
echo "          <div class=\"row\">";
echo "            <div class=\"col-md-4\">";
echo " <a href=\"http://somethingpatheticallyawful.com/login.html\" style=\"color:#ffffff\"><h3>Login</h3></a>";
echo "              <br/><br/>";
echo "            </div>";
echo "            <div class=\"col-md-4\">";
echo " <a href=\"profile.php\" style=\"color:#ffffff\"><h3>Profile</h3></a>";
echo "             <br/><br/>";
echo "             <p>";
echo "             </p>";
echo "            </div>";
echo "            <div class=\"col-md-4\">";
echo " <a href=\"tradeform.php\" style=\"color:#ffffff\"><h3>Trade</h3></a>";
echo "              <br/><br/>";
echo "            </div>";
echo "          </div>";
echo "        </div>";
echo "    </div>";
echo "";
echo "    <div style=\"padding-top: 200px\">";
echo "        <div class=\"container text-center\">";
echo "          <h2>";
echo "            Contact Us";
echo "          </h2>";
echo "";
echo "          <p>";
echo "            If you would like to get in touch regarding any inquiries that you may have, please fill out the form below and one of our representatives will be in touch with you soon.";
echo "          </p>";
echo "          <div class=\"inputarea\">";
echo "            <p>Email:</p>";
echo "            <input type=\"textfield\" class=\"custominputtext theFade\">";
echo "          </div>";
echo "          <div class=\"inputarea\">";
echo "            <p>Subject:</p>";
echo "            <input type=\"textfield\" class=\"custominputtext theFade\">";
echo "          </div>";
echo "          <div class=\"inputarea\">";
echo "            <p>Message:</p>";
echo "            <textarea class=\"custominputtext theFade descriptioninput\"></textarea>";
echo "          </div>";
echo "          <div class=\"inputarea\">";
echo "            <a href=\"\" onclick=\"\" class=\"inputsubmit\">Submit</a>";
echo "          </div>";
echo "        </div>";
echo "    </div>";
echo "  </div>";

?>

