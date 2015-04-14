<?php
 require_once('../connections/conexion.php');
 mssql_select_db($database_conexion, $conexion);

 $ahorita = strftime("%Y-%m-%d",time()); 
 $desde   = (isset($_GET['desde'])) ? $_GET['desde'] : strftime("%Y-%m-%d",time()); 
 $hasta   = (isset($_GET['hasta'])) ? $_GET['hasta'] : strftime("%Y-%m-%d",time()); 
 $hasta   = $hasta . " 23:59:59";
 $medio   = (isset($_GET['medio'])) ? $_GET['medio'] : "00"; 
 $texto   = (isset($_GET['texto'])) ? $_GET['texto'] : ""; 

if ( isset($_GET['id'] )){
  $ids = $_GET['id'];
  for( $i = 0; $i < count( $ids ); ++$i ){
   $id = $ids[$i];
   $query = sprintf("UPDATE RESPUESTAS_RECIBIDAS_TABLA_%s SET ESTADO=1 WHERE  ID= %s", $medio,  $id);
   if (!isset($_GET["aceptar" . $id ]) || $_GET["aceptar" . $id ] != 1 ) $query = sprintf("UPDATE RESPUESTAS_RECIBIDAS_TABLA_%s SET ESTADO=2 WHERE  ID= %s", $medio,  $id);
   mssql_query($query, $conexion) or die(mssql_error());
 }
}
 
 $_WORDS = mssql_query("SELECT palabra FROM EXPRESIONES_INVALIDAS") or die(mssql_query());
 $_IGNORED_WORDS = "";
 while ( $word = mssql_fetch_assoc( $_WORDS )){
  $_IGNORED_WORDS .= " AND TEXTO_DE_RESPUESTA NOT LIKE '%" . $word['palabra'] . "%' ";
 }
 $_QUERY = sprintf("SELECT * FROM RESPUESTAS_RECIBIDAS_TABLA_%s WHERE (FECHA_DE_RESPUESTA_RECIBI >= CONVERT(DATETIME, '%s', 102) AND FECHA_DE_RESPUESTA_RECIBI <= CONVERT(DATETIME, '%s', 102) AND ESTADO IS NULL) %s ORDER BY FECHA_DE_RESPUESTA_RECIBI DESC", $medio, $desde, $hasta,$_IGNORED_WORDS);

$Recordset1 = mssql_query($_QUERY, $conexion) or die(mssql_error());
$totalRows_Recordset1 = mssql_num_rows($Recordset1);
?><head>
<body bgcolor="#00ff00">
Total: <?php echo $totalRows_Recordset1 ?><form method=get>
      <input type="hidden" name=medio value=<?php echo $medio ?> />
      <input type="hidden" name=desde value=<?php echo $desde ?> />
      <input type="hidden" name=hasta value=<?php echo $hasta ?> />
<table width="100%" border="1" cellspacing="0" cellpadding="0"><?php $i = 0;
  while ($row_Recordset1 = mssql_fetch_assoc($Recordset1)) { ?><tr>
    <td><?php $i++; echo $i; ?></td>
    <td width="45%" scope="col"><?php echo $row_Recordset1['TEXTO_DE_RESPUESTA'] ?></td>
    <td width="15%" scope="col"><?php echo $row_Recordset1['NUMERO_DE_RESPUESTA'] ?></td>
    <td width="30%" scope="col"><?php echo $row_Recordset1['FECHA_DE_RESPUESTA_RECIBI'] ?></td>
    <td width="10%" scope="col">
      <input type="checkbox"  checked name=aceptar<?php echo $row_Recordset1['ID'] ?> value=1 />Aceptar

      <input type="hidden" name=id[] value=<?php echo $row_Recordset1['ID'] ?> />
    </td>
  </tr><?php }  ?>
</table>

<input type=submit value="Enviar"></form>

</body>
<?php
mssql_free_result($Recordset1);
?>