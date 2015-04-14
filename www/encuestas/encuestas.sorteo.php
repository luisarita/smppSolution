<?php
 session_start();
 if (!isset($_SESSION['idEncuesta'])){
  $insertGoTo = "encuestas.php";
  header(sprintf("Location: %s", $insertGoTo));
  exit();
 } else {
  $idEncuesta=$_SESSION['idEncuesta'];
 }

 require_once('../connections/conexion.php'); 
 require_once('../functions/functions.php');
 require_once('../functions/db.php');

 mysql_select_db($database_conexion, $conexion);

 $numero = "";
 $desde     = (isset($_GET['desde' ]) ? $_GET['desde' ] : -1); 
 $hasta     = (isset($_GET['hasta' ]) ? $_GET['hasta' ] : -1);

 if ( isset($_POST['limpiar'])){
  $sql = sprintf("UPDATE encuestas_participantes SET seleccionado=0 WHERE idEncuesta=%s", $idEncuesta);
  mysql_query($sql, $conexion) or die(register_mysql_error("ER001", mysql_error()));
 } else {
  if (isset($_GET['desde']) && isset($_GET['hasta'])){
   $sql = sprintf("SELECT id, numero FROM encuestas_participantes WHERE idEncuesta=%s AND seleccionado=0 AND fecha >=%s AND fecha <=%s ORDER BY RAND() LIMIT 1;", $idEncuesta, GetSQLValueString($desde, "date"), GetSQLValueString($hasta, "date"));

   $rsPosible           = mysql_query($sql, $conexion) or die(register_mysql_error("ER002", mysql_error()));
   $row_rsPosible       = mysql_fetch_assoc($rsPosible);
   $totalRows_rsPosible = mysql_num_rows($rsPosible);
   if ( $totalRows_rsPosible > 0 ){ 
    $id = $row_rsPosible[ 'id' ];
    $numero = $row_rsPosible[ 'numero' ];

    $sql = sprintf("UPDATE encuestas_participantes SET seleccionado=0 WHERE numero=%s AND idEncuesta=%s;", GetSQLValueString($numero, "text"), $idEncuesta);
    mysql_query($sql, $conexion) or die(register_mysql_error("ER003", mysql_error()));
    $sql = sprintf("UPDATE encuestas_participantes SET seleccionado=1 WHERE id=%s;", $id);
    mysql_query($sql, $conexion) or die(register_mysql_error("ER004", mysql_error()));
    $numero = substr($numero,0,strlen($numero)-2) . "??";
   } else {
    $numero = "    NO HAY ";
   }
  }
 }

 $sql = sprintf("SELECT numero FROM encuestas_participantes WHERE idEncuesta=%s AND seleccionado=1", $idEncuesta);
 $rs  = mysql_query($sql, $conexion) or die(register_mysql_error("ER005", mysql_error()));

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  <meta http-equiv="cache-control" content="no-cache">
  <title>Sorteo</title>
  <link rel="stylesheet" type="text/css" href="../css/sorteo.css" />
  <script type="text/javascript" language="JavaScript" src="../scripts/matrix.js"></script>
 </head>
 <body>
  <div id="matrix"><?php echo substr($numero,3); ?></div><br/>
  <table width="100%">
   <tr><th>Ganadores</th></tr><?php
   while ($row = mysql_fetch_array($rs)){
    ?><tr><td align="center"><?php echo $row['numero']; ?></td></tr><?php
   }
   ?><tr><td align="right">
    <form method="post"><input name="limpiar" type="submit" value="Limpiar" /></form>
   </td></tr>
  </table>
 </body>
</html>