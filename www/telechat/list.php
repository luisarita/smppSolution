<?php
 $color = "#000000";
 $text  = "#FFFFFF";
 $initPage = "/telechat.html";

 session_start();
 if (!isset($_SESSION['idTelechat'])){
  header("Location: " . $initPage); exit();
 }

 $idTelechat = intval($_SESSION['idTelechat']);
 $desde    = (isset($_GET['desde'])) ? $_GET['desde'] : strftime("%Y-%m-%d",time()); 
 $hasta    = (isset($_GET['hasta'])) ? $_GET['hasta'] : strftime("%Y-%m-%d",time()); 

 $url = "list.php?desde=" . $desde . "&hasta=" . $hasta;
 
 require_once('../connections/conexion.php'); 
 require_once('../functions/functions.php'); 
 require_once('../functions/db.php'); 
 mysql_select_db($database_conexion, $conexion);

 if ( isset($_GET['id'] )){
  $sql = sprintf("UPDATE telechats_mensajes SET estado=0, elimino=%s WHERE idTelechat=%s AND id=%s", GetSQLValueString($_SESSION['usuario'], "text"), GetSQLValueString($idTelechat, "int"), GetSQLValueString($_GET['id'], "int"));
  mysql_query($sql, $conexion) or die( register_mysql_error( "TL001", mysql_error()) );
  header("Location: " . $url);
  exit();
 } elseif ( isset($_POST['reiniciar'])){
  $sql = sprintf("UPDATE telechats_mensajes SET estado=3, elimino=%s, fecha_reinicio=NOW() WHERE idTelechat=%s", GetSQLValueString($_SESSION['usuario'], "text"), GetSQLValueString($idTelechat, "int"));
  mysql_query($sql, $conexion) or die( register_mysql_error( "TL002", mysql_error()) );
  header("Location: " . $url);
  exit();
 }
 
 $sql = sprintf("SELECT numero FROM telechats_mensajes WHERE (estado IS NULL) AND idTelechat=%s AND seleccionado = 1 ORDER BY fecha DESC",  GetSQLValueString($idTelechat, "int"));
 $rsGanadores = mysql_query($sql, $conexion) or die( register_mysql_error( "TL003", mysql_error()) );

 $sql = sprintf("SELECT id, mensaje, numero, fecha FROM telechats_mensajes WHERE (estado IS NULL) AND idTelechat=%s AND fecha>=%s AND fecha<=%s AND numero <> '5049999999' ORDER BY fecha DESC",  GetSQLValueString($idTelechat, "int"), GetSQLValueString($desde, "date"), GetSQLValueString($hasta, "date"));
 $rs = mysql_query($sql, $conexion) or die( register_mysql_error( "TL003", mysql_error()) );
 $totalRows_rs = mysql_num_rows($rs);

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
  <title></title>
  <meta http-equiv="Refresh" content="60;<?php echo $url; ?>" />
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  <link type="text/css" rel="stylesheet" href="../css/telechat.css"  />
  <script type="text/javascript" language="javascript" src="../scripts/popup.js"></script>
 </head>
 <body>
  <table width="100%" border="0" cellspacing="2" cellpadding="0">
   <tr><th colspan="5">Ganadores</th></tr>
   <tr>
    <th>&nbsp;</th>
    <th colspan='3'>N�mero</th>
    <th colspan='1'>&nbsp;</th>
   </tr><?php
    $i = 0;
    while ($row_rs = mysql_fetch_assoc($rsGanadores)) { 
     ?><tr>
      <td ><?php $i++; echo $i; ?></td>
      <td colspan='3' scope="col"><?php echo $row_rs['numero'] ?></td>
      <td colspan='1' scope="col">&nbsp;</td>
     </tr><?php
    } ?>
   <tr><td colspan="5">&nbsp;</td></tr>
   <tr><th colspan="5">Conteo de Mensajes: <?php echo $totalRows_rs ?></th></tr>
   <tr>
    <th style="width: 20px">&nbsp;</th>
    <th>Mensaje</th>
    <th style="width: 80px">N�mero</th>
    <th style="width: 130px">Fecha</th>
    <th style="width: 205px">&nbsp;</th>
   </tr><?php
    $i = 0;
    while ($row_rs = mysql_fetch_assoc($rs)) { 
     ?><tr>
      <td><?php $i++; echo $i; ?></td>
      <td><?php echo $row_rs['mensaje'] ?></td>
      <td><?php echo $row_rs['numero'] ?></td>
      <td><?php echo $row_rs['fecha'] ?></td>
      <td>
       <input type="button" class="msg-button" value="Responder" onclick="javascript: popUpChat('chat.php?id=<?php echo $row_rs['id']; ?>')" />
       <input type="button" class="warning-button" onclick="javascript: if (confirm('¿Desea eliminar este mensaje?')) window.location='<?php echo $url ?>&id=<?php echo $row_rs['id'] ?>'" value='Eliminar' /a>
      </td>
     </tr><?php
    } ?>
   <tr><td colspan="5">&nbsp;</td></tr>
   <tr><td colspan="5" style="text-align: right">
    <form method="post">
     <input id='reiniciar' name='reiniciar' type="button" onclick="javascript: if(confirm('¿Desea eliminar a todos los participantes? Al aceptar, la actividad quedar� vacia.')) this.form.submit()" class="warning-button" value='Reiniciar' />
    </form>
   </td></tr>
  </table>
 </body>
</html>
<?php mysql_free_result($rs); ?>
