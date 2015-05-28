<?php
session_start();
if (!isset($_SESSION['idTelechat'])) exit();
$idTelechat = $_SESSION['idTelechat'];

require_once('../connections/conexion.php'); 
require_once('../functions/functions.php'); 
require_once('../functions/db.php'); 
mysql_select_db($database_conexion, $conexion);

$g   = getdate(); 
$cnt = 0;

if( isset( $_GET['mes'] )){
 $mes = $_GET['mes'];
 $anio = $_GET['anio'];
 
 $mes2 = $mes % 12 + 1;
 $anio2 = $mes < 12 ? $anio : $anio + 1;	

 $query_cnt   = sprintf("SELECT COUNT(*) AS conteo FROM telechats_mensajes WHERE fecha >= '$anio-$mes-01' AND fecha < '$anio2-$mes2-01' AND numero <> '5049999999' AND idTelechat=%s", GetSQLValueString($idTelechat, "int"));
 $rs          = mysql_query( $query_cnt, $conexion ) or die(register_mysql_error("TR001", mysql_error()));
 $cnt         = ( $row = mysql_fetch_assoc( $rs )) ? $row[ 'conteo' ] : 0;
}

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  <link type="text/css" rel="stylesheet" href="../telechat/telechat.css"  />
 </head>
 <body>
  <form id="form" name="form" method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>">  
   <table border="0" cellspacing="0" cellpadding="0">
    <tr><th colspan="2">Conteo de Mes: <?php echo $cnt ?></th></tr>
    <tr><td colspan="2">&nbsp;</td></tr>
    <tr><th colspan="2">Reporte</th></tr>
    <tr><td colspan="2">&nbsp;</td></tr>
    <tr>
     <td width="80">Mes:</td>
     <td width="190">
      <select name="mes" style="width: 180px">
       <option value="1"  <?php echo  1 == $g['mon'] ? "selected" : "" ?>>Enero</option>
       <option value="2"  <?php echo  2 == $g['mon'] ? "selected" : "" ?>>Febrero</option>
       <option value="3"  <?php echo  3 == $g['mon'] ? "selected" : "" ?>>Marzo</option>
       <option value="4"  <?php echo  4 == $g['mon'] ? "selected" : "" ?>>Abril</option>
       <option value="5"  <?php echo  5 == $g['mon'] ? "selected" : "" ?>>Mayo</option>
       <option value="6"  <?php echo  6 == $g['mon'] ? "selected" : "" ?>>Junio</option>
       <option value="7"  <?php echo  7 == $g['mon'] ? "selected" : "" ?>>Julio</option>
       <option value="8"  <?php echo  8 == $g['mon'] ? "selected" : "" ?>>Agosto</option>
       <option value="9"  <?php echo  9 == $g['mon'] ? "selected" : "" ?>>Septiembre</option>
       <option value="10" <?php echo 10 == $g['mon'] ? "selected" : "" ?>>Octubre</option>
       <option value="11" <?php echo 11 == $g['mon'] ? "selected" : "" ?>>Noviembre</option>
       <option value="12" <?php echo 12 == $g['mon'] ? "selected" : "" ?>>Diciembre</option>
      </select>
     </td>
    </tr>
    <tr><td colspan="2">&nbsp;</td></tr>
    <tr>
     <td>A&ntilde;o:</td>
     <td>
      <select name="anio" style="width: 180px" ><?php
       for( $i = 2004; $i < $g['year'] + 1; ++$i){
	?><option value="<?php echo $i ?>" <?php echo $i == $g['year'] ? "selected" : ""  ?> ><?php echo $i; ?></option><?php
       } ?>
      </select>
     </td>
    </tr>
    <tr><td colspan="2">&nbsp;</td></tr>
    <tr>
     <td colspan="2">
      <div align="right"><input class="button" type="Submit" value="Mostrar" /></div>
     </td>
    </tr>
   </table>
  </form>

  <?php
   if( isset( $_GET['mes'] )){

	$m = $_GET['mes'];
	$y = $_GET['anio']

	?><img src="grafica.php?mes=<?php echo $m ?>&anio=<?php echo $y ?>" border="1" /><?php
   }
  ?>
 </body>
</html>