<?php
ini_set("display_errors", 1);

require_once('conf.php');
require_once('connections/conexion.php'); 
require_once('functions/functions.php');
require_once('functions/db.php');
require_once('functions/date.php');
require_once('functions/horarios.php');
require_once('rifas/rifas.php'); 
require_once('lib/jpgraph/src/jpgraph.php');
require_once('lib/jpgraph/src/jpgraph_bar.php');
require_once('lib/smarty/Smarty.class.php');

if (!isset($extended) || !$extended){
    $r = new rifa();
    $r->action();
}