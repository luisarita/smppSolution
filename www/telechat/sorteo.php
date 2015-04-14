<?php 
 $_CONF = array (
  'tabla_participantes' => 'telechats_mensajes',
  'campo_llave'         => 'idTelechat',
  'condicion_adicional' => 'AND estado IS NULL'
 );
 
 require_once('../Connections/conexion.php'); 
 require_once('../functions/db.php'); 
 require_once('../functions/functions.php'); 
 mysql_select_db($database_conexion, $conexion);

 session_start();
 if (!isset($_SESSION[$_CONF['campo_llave']])) exit();
 $id = $_SESSION[$_CONF['campo_llave']];

 $desde  = (isset($_GET['desde'])) ? ((get_magic_quotes_gpc()) ? $_GET['desde'] : addslashes($_GET['desde'])) : "";
 $hasta  = (isset($_GET['hasta'])) ? ((get_magic_quotes_gpc()) ? $_GET['hasta'] : addslashes($_GET['hasta'])) : "";
 $numero = "No Hay";

 if ( isset($_POST['limpiar'])){
  $sql = sprintf("UPDATE %s SET seleccionado=0 WHERE %s=%s", $_CONF['tabla_participantes'], $_CONF['campo_llave'], $id); echo $sql;
  mysql_query($sql, $conexion) or die(register_mysql_error("TS001", mysql_error()));
  header(sprintf("Location: %s", $_SERVER['PHP_SELF']));
 } elseif (isset($_GET['desde']) && isset($_GET['hasta'])){
  $query_rsPosible = sprintf("SELECT id, numero FROM %s WHERE fecha>=%s AND fecha<=%s AND %s=%s AND seleccionado=0 AND numero NOT IN ('50499999999', '9999999') AND numero NOT IN (SELECT DISTINCT numero FROM %s WHERE %s=%s AND seleccionado=1) %s ORDER BY RAND()", $_CONF['tabla_participantes'], GetSQLValueString($desde, "date"), GetSQLValueString($hasta . " 23:59:59", "date"), $_CONF['campo_llave'], $id, $_CONF['tabla_participantes'], $_CONF['campo_llave'], $id, $_CONF['condicion_adicional']);
  $rsPosible           = mysql_query($query_rsPosible, $conexion) or die(register_mysql_error("TS002", mysql_error()));
  $row_rsPosible       = mysql_fetch_assoc($rsPosible);
  $totalRows_rsPosible = mysql_num_rows($rsPosible);
  if ( $totalRows_rsPosible > 0 ){ 
   $idGanador = $row_rsPosible[ 'id' ];
   $numero    = substr($row_rsPosible[ 'numero' ], 3);
   
   $sql = sprintf("UPDATE %s SET seleccionado=1 WHERE id=%s", $_CONF['tabla_participantes'], $idGanador);
   mysql_query($sql, $conexion) or die(register_mysql_error("TS003", mysql_error()));
   $numero = substr($numero,0,strlen($numero)-2) . "??";
  }
 }

 $query_rsGanadores = sprintf( "SELECT numero FROM %s WHERE seleccionado=1 AND %s=%s ORDER BY numero", $_CONF['tabla_participantes'], $_CONF['campo_llave'], $id);
 $rsGanadores           = mysql_query($query_rsGanadores, $conexion) or die(register_mysql_error("TS004", mysql_error()));

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  <meta http-equiv="cache-control" content="no-cache">
  <title>Sorteo</title>
  <link type="text/css" rel="stylesheet" href="../css/sorteo.css" />
  <script type="text/javascript" language="JavaScript" src="../scripts/matrix.js"></script>
 </head>
 <body>
  <div id="matrix"><?php echo $numero; ?></div><br/>
  <table width="100%">
   <tr><th>Ganadores</th></tr><?php
   while ($row_rsGanadores = mysql_fetch_array($rsGanadores)){
    ?><tr><td align="center"><?php echo $row_rsGanadores['numero']; ?></td></tr><?php
   }
   ?><tr><td style="text-align: right">
    <form method="post"><input class="button" name="limpiar" id="limpiar" type="submit" value="Limpiar" /></form>
   </td></tr>
  </table>
 </body>
</html>