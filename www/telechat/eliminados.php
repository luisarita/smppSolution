<?php
// Parametros
$color = "#000000";
$text  = "#FFFFFF";

require_once('../connections/conexion.php');
session_start();
if (!isset($_SESSION['idTelechat'])) exit();
$idTelechat=$_SESSION['idTelechat'];

 mysql_select_db($database_conexion, $conexion);

 $ahorita = strftime("%Y-%m-%d",time()); 
 $desde =  (isset($_GET['desde'])) ? $_GET['desde'] : strftime("%Y-%m-%d",time()); 
 $hasta =  (isset($_GET['hasta'])) ? $_GET['hasta'] : strftime("%Y-%m-%d",time()); 
$hasta = $hasta . " 23:59:59";
 $texto =  (isset($_GET['texto'])) ? $_GET['texto'] : ""; 

 $_QUERY = sprintf("SELECT * FROM telechats_mensajes WHERE (estado=0 OR estado=1) AND idTelechat=%s AND fecha >='%s' AND fecha <= '%s'  ORDER BY fecha DESC", $idTelechat, $desde, $hasta);

$Recordset1 = mysql_query($_QUERY, $conexion) or die(mysql_error());
$totalRows_Recordset1 = mysql_num_rows($Recordset1);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  <style>
   body { font-family: Arial, Helvetica, sans-serif; font-size: 13px; }
   a    { text-decoration: none; }
   th   { color: #FFFFFF; background-color:#003399; border: 1px solid #FFFFFF }
   td   { text-align: center }
  </style>
 </head>
 <body bgcolor="<?php echo $color ?>" text="<?php echo $text ?>">
  <table width="100%" border="0" cellspacing="2" cellpadding="0">
   <tr><th colspan="5">Conteo de Mensajes: <?php echo $totalRows_Recordset1 ?></th></tr>
   <tr>
    <th width="50px">&nbsp;</th>
    <th width="550px">Mensaje</th>
    <th width="100px">Número</th>
    <th width="150px">Fecha</th>
    <th width="150px">Eliminó</th>
   </tr><?php
   $i = 0;
   while ($row_Recordset1=mysql_fetch_assoc($Recordset1)){
    ?><tr>
     <td><?php $i++; echo $i; ?></td>
     <td scope="col" style='text-align: left'><?php echo $row_Recordset1['mensaje'] ?></td>
     <td scope="col"><?php echo $row_Recordset1['numero'] ?></td>
     <td scope="col"><?php echo $row_Recordset1['fecha'] ?></td>
     <td scope="col"><?php
	  echo ( $row_Recordset1['estado'] == 0) ? $row_Recordset1['elimino'] : '**OMITIDO**' ;
	 ?></td>
   </tr><?php
   } ?>
 </table>

</body>
<?php
mysql_free_result($Recordset1);
?>