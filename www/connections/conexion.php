<?php
date_default_timezone_set('America/Tegucigalpa');

$hostname_conexion = "server.local";
$database_conexion = "smpp";
$username_conexion = "root";
$password_conexion = "root";
$conexion = mysql_connect($hostname_conexion, $username_conexion, $password_conexion,false,128) or trigger_error(mysql_error(),E_USER_ERROR); 
mysql_select_db($database_conexion) or die(mysql_error());