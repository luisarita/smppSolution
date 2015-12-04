<?php
ini_set("display_errors", 1);

require_once('conf.php');
require_once('connections/conexion.php'); 
require_once('functions/functions.php');
require_once('functions/db.php');
require_once('functions/date.php');
require_once('functions/horarios.php');
require_once('pines/pines.php'); 
require_once('lib/jpgraph/src/jpgraph.php');
require_once('lib/jpgraph/src/jpgraph_bar.php');

if (!isset($extended) || !$extended){
 $r = new envioPines();
 $r->action();
}