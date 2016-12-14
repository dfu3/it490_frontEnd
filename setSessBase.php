<?php
session_start();
$base = substr($_POST['baseVal'], 0, 3);
$_SESSION['BASE'] = $base;
?>