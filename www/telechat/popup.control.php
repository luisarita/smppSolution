<?php
 // Parametros
 $color = "000000";
 $text  = "FFFFFF";
 
 
 require_once('../connections/conexion.php');
 session_start();
 if (!isset($_SESSION['idTelechat'])) exit();
 $idTelechat = $_SESSION['idTelechat'];

 $desde    = (isset($_GET['desde']))    ? $_GET['desde']    : strftime("%Y-%m-%d %H:%M:%S",time() - 60 * 60); 
 $hastaStr = (isset($_GET['hasta']))    ? $_GET['hasta']    : strftime("%Y-%m-%d %H:%M:%S",time()); 
 $hasta    = (isset($_GET['hasta']))    ? $_GET['hasta']    : strftime("%Y-%m-%d %H:%M:%S",time());
 $texto    = (isset($_GET['texto']))    ? $_GET['texto']    : ""; 
 $mensaje  = (isset($_GET['mensaje']))  ? $_GET['mensaje']  : ""; 
 $mbcolor  = (isset($_GET['mbcolor']))  ? $_GET['mbcolor']  : $color;
 $mfcolor  = (isset($_GET['mfcolor']))  ? $_GET['mfcolor']  : $text;
 $mfont    = (isset($_GET['msg_font'])) ? $_GET['msg_font'] : "Arial";
 $msize    = (isset($_GET['msg_size'])) ? $_GET['msg_size'] : "30";
 $tbcolor  = (isset($_GET['tbcolor']))  ? $_GET['tbcolor']  : $color;
 $tfcolor  = (isset($_GET['tfcolor']))  ? $_GET['tfcolor']  : $text;
 $tfont    = (isset($_GET['tkr_font'])) ? $_GET['tkr_font'] : "Arial";
 $tsize    = (isset($_GET['tkr_size'])) ? $_GET['tkr_size'] : "40";
 $popup    = (isset($_GET['popup']))    ? $_GET['popup']    : 0;

 $mbcolor  = str_replace("#", "", $mbcolor );
 $mfcolor  = str_replace("#", "", $mfcolor );
 $tbcolor  = str_replace("#", "", $tbcolor );
 $tfcolor  = str_replace("#", "", $tfcolor );

 $fullURL  = "?desde=$desde&hasta=$hastaStr&mensaje=$mensaje&mbcolor=$mbcolor&mfcolor=$mfcolor&msg_font=$mfont&msg_size=$msize&tbcolor=$tbcolor&tfcolor=$tfcolor&tkr_font=$tfont&tkr_size=$tsize&popup=$popup";

 if ( isset($_GET['submit']) && $_GET['submit']=='Agregar'){ 
  $query = sprintf("INSERT INTO telechats_mensajes (idTelechat,fecha,mensaje) VALUES (%s,'" . strftime("%Y-%m-%d %H:%M:%S", time()) . "','%s')",$idTelechat,$texto);
  mysql_query($query, $conexion) or die();
  header("Location: popup.control.php$fullURL"); exit();
 }

 $_REFRESCAR           = 120; //segundos entre cada generacion de la cola
 $_CANTIDAD_MAXIMA     = 20;    //mensajes por cola
 $_SEPARACION_MENSAJES = " - ";

 $_WORDS = mysql_query("SELECT expresion FROM telechats_invalidos ORDER BY expresion",$conexion) or die();
 $_IGNORED_WORDS = "";
 while ( $word = mysql_fetch_assoc( $_WORDS )){
  $_IGNORED_WORDS .= " OR mensaje LIKE '%" . $word['expresion'] . "%' ";
 }

 if ( $_IGNORED_WORDS != "" ){
  $_QUERYI = sprintf("INSERT INTO mensajes_pendientes (numero,numero_salida,mensaje,prioridad,fecha_salida) SELECT m.numero, t.numero, 'Tu mensaje fue omitido por contener expresiones no v�lidas',2,NOW() FROM telechats_mensajes m, telechats t WHERE t.id=m.idTelechat AND m.estado IS NULL AND idTelechat=%s AND fecha >='%s' AND fecha <= '%s' AND (1=0 %s) ORDER BY fecha DESC",  $idTelechat, $desde, $hasta ,$_IGNORED_WORDS);
  mysql_query($_QUERYI, $conexion) or die();
  $_QUERYU = sprintf("UPDATE telechats_mensajes SET estado=1 WHERE estado IS NULL AND idTelechat=%s AND fecha>='%s' AND fecha <= '%s' AND (1=0 %s) ORDER BY fecha DESC",  $idTelechat, $desde, $hasta ,$_IGNORED_WORDS);
  mysql_query($_QUERYU, $conexion) or die();
 }

 $_QUERY = sprintf("SELECT mensaje FROM telechats_mensajes WHERE (estado IS NULL) AND idTelechat=%s AND fecha>='%s' AND fecha <= '%s' ORDER BY fecha DESC",  $idTelechat, $desde, $hasta);
 $Recordset1 = mysql_query($_QUERY, $conexion) or die();
 $row_Recordset1 = mysql_fetch_assoc($Recordset1);
 $totalRows_Recordset1 = mysql_num_rows($Recordset1);

 $nextStr = ""; $i = 0;
 do { 
  $nextStr = $nextStr . $row_Recordset1['mensaje'] . $_SEPARACION_MENSAJES;
  ++$i;
 } while (($row_Recordset1 = mysql_fetch_assoc($Recordset1)) && $i < $_CANTIDAD_MAXIMA );
 $nextStr = str_replace("\r","",str_replace("\n","",$nextStr)); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  <meta http-equiv="Refresh" content="<?php echo "$_REFRESCAR;popup.control.php$fullURL" ?>">
  <script language="javascript">
   function fillInputBoxes (theFrame){
    inputBox = theFrame.document.getElementById("TICKER2");
    if ( inputBox ) inputBox.value = "<?php echo str_replace( "\"", "", $nextStr) ?>";

    inputBox2 = theFrame.document.getElementById("MENSAJE2");
    if ( inputBox2 ) inputBox2.value = "<?php echo (isset($_GET['mensaje'])) ? $_GET['mensaje'] : "" ?>";

    inputBox3 = theFrame.document.getElementById("TKR_BCOLOR");
    if ( inputBox3 ) inputBox3.value = "#<?php echo $tbcolor ?>";

    inputBox4 = theFrame.document.getElementById("TKR_FCOLOR");
    if ( inputBox4 ) inputBox4.value = "#<?php echo $tfcolor ?>";

    inputBox5 = theFrame.document.getElementById("TKR_FONT");
    if ( inputBox5 ) inputBox5.value = "<?php echo $tfont ?>";

    inputBox6 = theFrame.document.getElementById("TKR_SIZE");
    if ( inputBox6 ) inputBox6.value = "<?php echo $tsize ?>";

    inputBox7 = theFrame.document.getElementById("MSG_BCOLOR");
    if ( inputBox7 ) inputBox7.value = "#<?php echo $mbcolor ?>";

    inputBox8 = theFrame.document.getElementById("MSG_FCOLOR");
    if ( inputBox8 ) inputBox8.value = "#<?php echo $mfcolor ?>";

    inputBox9 = theFrame.document.getElementById("MSG_FONT");
    if ( inputBox9 ) inputBox9.value = "<?php echo $mfont ?>";

    inputBox10 = theFrame.document.getElementById("MSG_SIZE");
    if ( inputBox10 ) inputBox10.value = "<?php echo $msize ?>";
   }
   fillInputBoxes(parent.frames[2]);
 </script>
</head>