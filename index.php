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
echo "      <div class=\"container text-center\" style=\"padding: 45px\">";

echo "<param id='curr2' value='EUR'>";

// TABLE CODE HERE

require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

$client = new rabbitMQClient("testRabbitMQ.ini","testServer");
$homeReq = array();

$page = "<head>
<script src='http://code.jquery.com/jquery-3.1.1.min.js'></script>
<script type='text/javascript' src='http://www.gstatic.com/charts/loader.js'></script>
<script type='text/javascript' 
        src='http://www.google.com/jsapi'>
</script>

<script>

function postValue() {

  	var val = document.getElementById('mySelect').options[document.getElementById('mySelect').selectedIndex].text;

 	var ajax_post = {baseVal:val};

	var jqxhr = $.ajax({
    type: 'POST',
    url: 'setSessBase.php',
    data: ajax_post,
	})
	.done(function() {
	    window.location.reload();
	});	

}
</script>

<script>

google.charts.load('current', {packages: ['corechart', 'line']});
google.charts.setOnLoadCallback(drawTrendlines);

function drawTrendlines() {

	var val = document.getElementById('curr2').value;
	var ajax_post = {curr2: val};

	//alert(val);

	var json = $.ajax({
    type: 'POST',
    url: 'getHist.php',
    data: ajax_post,
    dataType: 'JSON',
	success: function(chart_values) {
	        console.log(chart_values);  
	        draw_chart(chart_values); 
	    }
	});
}

function draw_chart(chartVals) {

	var data = new google.visualization.arrayToDataTable((chartVals));

	var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
	var options = {
		title: 'USD / EUR',

       'width': 650,
       'height': 400,
       'chartArea': {'width': '75%', 'height': '75%'},
       'legend': {'position': 'top', 'alignment' : 'center'},

	};

	chart.draw(data, options);
}

</script>

</head>";



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


$client = new rabbitMQClient("testRabbitMQ.ini","testServer");
$listReq = array();
$listReq['type'] = "get_curr_list";
$list = $client->send_request($listReq);

$page.= "<select id='mySelect'>";
$page.= "<option selected disabled>Select Base Currency</option>";
$ind = 0;
foreach($list as $arr)
{
	$page.= "<option>$arr</option>";
	$ind++;
}

echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
$page.= "</select>
<br><br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
echo "<input type='button' onclick='postValue()' style='float:right' value='APPLY'>";

$page.= "<h4>Base: " . $base . "</h4><br><br><br>";
echo $page;

//$page.= $table;


echo "<div id='table'>$table</div>";

//echo "<br><br>";
 echo "<div id='chart_div' style='position:relative;left:600px;top:150px;'></div>";

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

echo "
	
	
<script>

  var table = document.getElementById('exRates');
    if (table != null) {
        for (var i = 0; i < table.rows.length; i++) {
            for (var j = 0; j < table.rows[i].cells.length; j++)
            table.rows[i].cells[j].onclick = function () {
                tableText(this);
            };
        }
    }

    function tableText(tableCell) {

    	if(isNaN(tableCell.innerHTML))
    	{
    		document.getElementById('curr2').value = tableCell.innerHTML;
    		drawTrendlines();
    	}
    	
    }

</script>

		";

#--------------------------------------------

// require_once('path.inc');
// require_once('get_host_info.inc');
// require_once('rabbitMQLib.inc');

// $client = new rabbitMQClient("testRabbitMQ.ini","testServer");
// $homeReq = array();

// $page = "<head>
// <script src='http://code.jquery.com/jquery-1.11.0.min.js'></script>
// <script>

// function postValue() {

//   	var val = document.getElementById('mySelect').options[document.getElementById('mySelect').selectedIndex].text;


//  	var ajax_post = {baseVal:val};

// 	var jqxhr = $.ajax({
//     type: 'POST',
//     url: 'setSessBase.php',
//     data: ajax_post,
// 	})
// 	.done(function() {
// 	    window.location.reload();
// 	});	

// }
// </script>
// </head>";

// // $page .= "<h1> HOMEPAGE </h1> <br>";
// session_start();

// if(!isset($_SESSION["BASE"]))
// {
// 	$_SESSION["BASE"] = "USD";
// }


// $base = $_SESSION["BASE"];
// $homeReq['type'] = "get_ex_for_base";
// $homeReq['base'] = $base;
// $table = $client->send_request($homeReq);
// $page.= "<hr>";


// $client = new rabbitMQClient("testRabbitMQ.ini","testServer");
// $listReq = array();
// $listReq['type'] = "get_curr_list";
// $list = $client->send_request($listReq);

// $page.= "<select id='mySelect'>";
// $page.= "<option selected disabled>Select Base Currency</option>";
// $ind = 0;
// foreach($list as $arr)
// {
// 	$page.= "<option>$arr</option>";
// 	$ind++;
// }
// $page.= "</select>
// <br><br>
// <input type='button' onclick='postValue()' value='APPLY'>";

// $page.= "<br><h3>Base: " . $base . "</h3><br>";
// $page.= $table;
// $page.= " <br><button onclick=\"location.href='login.html'\">LOGIN</button> ";
// $page.= " <br><button onclick=\"location.href='profile.php'\">PROFILE</button> ";
// $page.= " <br><button onclick=\"location.href='trade.html'\">TRADE</button> ";

// echo $page;

?>