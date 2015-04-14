<?php
require_once('conf.php');
require_once('connections/conexion.php'); 
require_once('functions/functions.php');
require_once('functions/db.php');
require_once('functions/date.php');
require_once('functions/horarios.php');
require_once('jpgraph/src/jpgraph.php');
require_once('jpgraph/src/jpgraph_bar.php');
require_once('telechat/telechat.php'); 

if (!isset($extended) || !$extended){
 $r = new telechat();
 $r->action();
}
?>