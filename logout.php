<?php
session_start();

if (isset($_SESSION["USER"]))
{
	session_unset($_SESSION["USER"]);
}
print($_SESSION["USER"]);
header('Location: login.html');

?>