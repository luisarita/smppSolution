<?php 

require_once('conf.php');
require_once('connections/conexion.php'); 
require_once('functions/functions.php');
require_once('functions/db.php');
require_once('functions/date.php');
require_once('recargas/recargas.php'); 

if (!isset($extended) || !$extended){
    $r = new recarga();
    $r->action();
}