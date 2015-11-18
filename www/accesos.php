<?php 
require_once('conf.php');
require_once('connections/conexion.php'); 
require_once('lib/phpframework/strings/strings.php');
require_once('functions/functions.php');
require_once('functions/db.php');
require_once('functions/date.php');
require_once('functions/horarios.php');
require_once('accesos/accesos.php'); 

if (!isset($extended) || !$extended){
 $r = new accesos();
 $r->action();
}