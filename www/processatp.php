<?php

header("Content-Type: text/plain");
ini_set("display_errors", 1);

require_once("connections/conexion.php");
require_once('lib/nusoap.php');
require_once('functions/db.php');
require_once('functions/functions.php');
require_once('conf.php');

global $conexion;

$sql = array();
$sql[] = "CALL sp_suscripciones_distribucion();";

foreach ($sql as $key => $query) {
    $rs = mysql_query($query) or die(mysql_error());
}

require_once('webservices/atp/index.php');
$c = new consumer();
$c->processOutMsg();
