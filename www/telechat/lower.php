<?php
 // Parametros
 $color = "000000";
 $text  = "FFFFFF"; 
 
 require_once('../connections/conexion.php');
 session_start();
 if (!isset($_SESSION['idTelechat'])) exit();
 $idTelechat = $_SESSION['idTelechat'];

 $desde    = (isset($_GET['desde']))    ? $_GET['desde']    : strftime("%Y-%m-%d %H:%M:%S",time() - 60 * 60); 
 $hastaStr = (isset($_GET['hasta']))    ? $_GET['hasta']    : strftime("%Y-%m-%d %H:%M:%S",time() + 60 * 60); 
 $hasta    = (isset($_GET['hasta']))    ? $_GET['hasta']    : strftime("%Y-%m-%d %H:%M:%S",time() + 60 * 60);
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

 mysql_select_db($database_conexion, $conexion);

 if ( isset($_GET['submit']) && $_GET['submit']=='Agregar'){ 
  $query = sprintf("INSERT INTO telechats_mensajes (idTelechat,fecha,mensaje) VALUES (%s,'" . strftime("%Y-%m-%d %H:%M:%S", time()) . "','%s');",$idTelechat,$texto);
  mysql_query($query, $conexion) or die();
  header("Location: lower.php$fullURL"); exit();
 }

 $_REFRESCAR           = 120; //segundos entre cada generacion de la cola
 $_CANTIDAD_MAXIMA     = 20;    //mensajes por cola
 $_SEPARACION_MENSAJES = " - ";

 $_WORDS = mysql_query("SELECT expresion FROM telechats_invalidos ORDER BY expresion;",$conexion) or die();
 $_IGNORED_WORDS = "";
 while ( $word = mysql_fetch_assoc( $_WORDS )){
  $_IGNORED_WORDS .= " OR mensaje LIKE '%" . $word['expresion'] . "%' ";
 }

 if ( $_IGNORED_WORDS != "" ){
  $_QUERYI = sprintf("INSERT INTO mensajes_pendientes (numero,numero_salida,mensaje,prioridad,fecha_salida) SELECT m.numero, t.numero, 'Tu mensaje fue omitido por contener expresiones no v�lidas',2,NOW() FROM telechats_mensajes m, telechats t WHERE t.id=m.idTelechat AND m.estado IS NULL AND idTelechat=%s AND fecha >='%s' AND fecha <= '%s' AND (1=0 %s) ORDER BY fecha DESC;",  $idTelechat, $desde, $hasta ,$_IGNORED_WORDS);
  mysql_query($_QUERYI, $conexion) or die();
  $_QUERYU = sprintf("UPDATE telechats_mensajes SET estado=1 WHERE estado IS NULL AND idTelechat=%s AND fecha>='%s' AND fecha <='%s' AND (1=0 %s) ORDER BY fecha DESC;",  $idTelechat, $desde, $hasta ,$_IGNORED_WORDS);
  mysql_query($_QUERYU, $conexion) or die();
 }

 $_QUERY = sprintf("SELECT mensaje FROM telechats_mensajes WHERE (estado IS NULL) AND idTelechat=%s AND fecha>='%s' AND fecha <='%s' ORDER BY fecha DESC LIMIT %s;",  $idTelechat, $desde, $hasta, $_CANTIDAD_MAXIMA);
 $rs = mysql_query($_QUERY, $conexion) or die();

 $nextStr = "";
 while ($row_Recordset1 = mysql_fetch_assoc($rs) ){ 
  $nextStr = $nextStr . $row_Recordset1['mensaje'] . $_SEPARACION_MENSAJES;
 } 
 $nextStr = str_replace("\r","",str_replace("\n","",$nextStr)); 
 
 ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  <meta http-equiv="Refresh" content="<?php echo "$_REFRESCAR;lower.php$fullURL" ?>">
  <script language="javascript" src="scripts/colorpicker2.js"></script>
  <script language="javascript">
   var cp = new ColorPicker(); // DIV style
   var number;

   function cpicker( num ){
    number = num;
    cp.select( document.getElementById("colorsgraph" + number ) , 'palleteviewer' + num );
   }

   function pickColor( color ){
    document.getElementById("palleteviewer" + number ).style.backgroundColor = color;
    var a;
    a = document.getElementById("colorsgraph" + number );
    a.value = color;
   }
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
   function popUp( URL ){
    URL = URL + '?desde=<?php echo $desde ?>&hasta=<?php echo $hastaStr ?>';   
    window.open(URL, 'sorteo','toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=0,width=1200,height=600,left=20,top=50');
   }
   function popUpTicker( ) {
    document.getElementById("popup").value = 1;
	window.open('popup.php<?php echo $fullURL ?>','tickerPopUp','toolbar=0, scrollbars=1, location=0, statusbar=0, menubar=0, resizable=1, width=1040, height=125, left=10, top=10');
   }
   
   fillInputBoxes(parent.frames[3]);
  </script>
  <script type="text/javascript" src="../lib/calendar/calendar.js"></script>
  <script type="text/javascript" src="../lib/calendar/lang/calendar-es.js"></script>
  <script type="text/javascript" src="../lib/calendar/calendar-setup.js"></script>
  <style type="text/css">
   @import url(../lib/calendar/calendar-green.css);
  </style>
  <style>
   body { font-family: Arial, Helvetica, sans-serif; font-size: 13px }
   a    { text-decoration: none; color: #FFFFFF }
   th   { color: #FFFFFF; background-color: #003399; border: 1px solid #FFFFFF }
   td   { text-align: left }
  </style>
 </head>
 <body bgcolor="#<?php echo $color ?>" text="#<?php echo $text ?>">
 <form action="lower.php" method="GET" name="form1" id="form1">
  <table width="100%" border="0" cellspacing="2" cellpadding="0">
   <tr>
    <th scope="col" colspan="2">Parametros</th>
    <td scope="col" rowspan="10">&nbsp;</td>
    <th scope="col">Inyectar Mensaje</th>
    </tr>
   <tr>
    <td width="220">Fecha Desde:</td>
    <td width="200"><input id="desde" name="desde" value="<?php echo $desde ?>"><button id="desde_btn">...</button></td>
    <td width="600" rowspan="8"><textarea name="texto" style="width: 99%" rows="11"></textarea></td>
   </tr><tr>
    <td>Fecha Hasta:</td>
    <td><input id="hasta" name="hasta" type="text" value="<?php echo $hastaStr ?>"><button id="hasta_btn">...</button></td>
   </tr><tr>
    <td>Mensaje: </td>
    <td><input type="text" name="mensaje" id="mensaje" value="<?php echo $mensaje ?>" /></td>
   </tr><tr>
    <td>Tipo de Letra Inferior:</td>
    <td style="padding-top: 2px; padding-bottom: 2px">
     <select name="tkr_font" id="font">
      <option <?php if ($tfont == "Halloween") echo "selected" ?> value="Halloween">Halloween</option>
      <option <?php if ($tfont == "BahamasLight") echo "selected" ?> value="BahamasLight">BahamasLight</option>
      <option <?php if ($tfont == "Digiface") echo "selected" ?> value="Digiface">Digiface</option>
      <option <?php if ($tfont == "Hollywood") echo "selected" ?> value="Hollywood">Hollywood</option>
      <option <?php if ($tfont == "Write Off") echo "selected" ?> value="Write Off">Write Off</option>
      <option <?php if ($tfont == "Maraca") echo "selected" ?> value="Maraca">Maraca</option>
      <option <?php if ($tfont == "Arial") echo "selected" ?> value="Arial">Arial</option>
     </select>
     <select name='tkr_size'>
      <option <?php if ($tsize == 10) echo "selected" ?>>10</option>
      <option <?php if ($tsize == 12) echo "selected" ?>>12</option>
      <option <?php if ($tsize == 14) echo "selected" ?>>14</option>
      <option <?php if ($tsize == 16) echo "selected" ?>>16</option>
      <option <?php if ($tsize == 18) echo "selected" ?>>18</option>
      <option <?php if ($tsize == 20) echo "selected" ?>>20</option>
      <option <?php if ($tsize == 24) echo "selected" ?>>24</option>
      <option <?php if ($tsize == 30) echo "selected" ?>>30</option>
      <option <?php if ($tsize == 36) echo "selected" ?>>36</option>
      <option <?php if ($tsize == 40) echo "selected" ?>>40</option>
      <option <?php if ($tsize == 48) echo "selected" ?>>48</option>
     </select>
    </td>
   </tr><tr>
    <td>Tipo de Letra Superior:</td>
    <td style="padding-top: 2px; padding-bottom: 2px">
     <select name="msg_font" id="font">
      <option <?php if ($mfont == "Halloween") echo "selected" ?> value="Halloween">Halloween</option>
      <option <?php if ($mfont == "BahamasLight") echo "selected" ?> value="BahamasLight">BahamasLight</option>
      <option <?php if ($mfont == "Digiface") echo "selected" ?> value="Digiface">Digiface</option>
      <option <?php if ($mfont == "Hollywood") echo "selected" ?> value="Hollywood">Hollywood</option>
      <option <?php if ($mfont == "Write Off") echo "selected" ?> value="Write Off">Write Off</option>
      <option <?php if ($mfont == "Maraca") echo "selected" ?> value="Maraca">Maraca</option>
      <option <?php if ($mfont == "Arial") echo "selected" ?> value="Arial">Arial</option>
     </select>
     <select name='msg_size'>
      <option <?php if ($msize == 10) echo "selected" ?>>10</option>
      <option <?php if ($msize == 12) echo "selected" ?>>12</option>
      <option <?php if ($msize == 14) echo "selected" ?>>14</option>
      <option <?php if ($msize == 16) echo "selected" ?>>16</option>
      <option <?php if ($msize == 18) echo "selected" ?>>18</option>
      <option <?php if ($msize == 20) echo "selected" ?>>20</option>
      <option <?php if ($msize == 24) echo "selected" ?>>24</option>
      <option <?php if ($msize == 30) echo "selected" ?>>30</option>
      <option <?php if ($msize == 36) echo "selected" ?>>36</option>
      <option <?php if ($msize == 40) echo "selected" ?>>40</option>
      <option <?php if ($msize == 48) echo "selected" ?>>48</option>
     </select>
    </td>
   </tr>
   <tr>
    <td>Color de Letra:</td>
    <td style="padding-top: 2px; padding-bottom: 2px">
     <input type="hidden" id="colorsgraph1" name="tfcolor" value="#<?php echo $tfcolor ?>">
     <input type="hidden" id="colorsgraph2" name="mfcolor" value="#<?php echo $mfcolor ?>">
     <span style="cursor: pointer; background: #<?php echo $tfcolor ?>; border: 1px solid #FFFFFF" id='palleteviewer1' name='palleteviewer1' onClick="cpicker('1'); return false;">
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
     </span>
     <span style="cursor: pointer; background: #<?php echo $mfcolor ?>; border: 1px solid #FFFFFF" id='palleteviewer2' name='palleteviewer2' onClick="cpicker('2'); return false;">
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
     </span>
    </td>
   </tr><tr>
    <td>Color de Fondo:</td>
    <td style="padding-top: 2px; padding-bottom: 2px">
     <input type="hidden" id="colorsgraph3" name="tbcolor" value="#<?php echo $tbcolor ?>">
     <input type="hidden" id="colorsgraph4" name="mbcolor" value="#<?php echo $mbcolor ?>">
     <span style="cursor: pointer; background: #<?php echo $tbcolor ?>; border: 1px solid #FFFFFF" id='palleteviewer3' name='palleteviewer3' onClick="cpicker('3'); return false;">
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
     </span>
     <span style="cursor: pointer; background: #<?php echo $mbcolor ?>; border: 1px solid #FFFFFF" id='palleteviewer4' name='palleteviewer4' onClick="cpicker('4'); return false;">
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
     </span>
    </td>
   </tr><tr>
    <th colspan="2">
     <a href="list.php?desde=<?php echo $desde ?>&hasta=<?php echo $hastaStr ?>" target="listFrame">Lista</a> - 
     <a href='#' onClick="javascript: popUp('sorteo.php')">Sorteo</a> - 
     <a href="#" onClick="javascript: popUpTicker()">PopUp</a> -
     <a href="eliminados.php?desde=<?php echo $desde ?>&hasta=<?php echo $hastaStr ?>" target="listFrame">Eliminados</a> -
     <a href="reporte.php?desde=<?php echo $desde ?>&hasta=<?php echo $hastaStr ?>" target="listFrame">Reporte</a> -
     <a href="fonts.zip" target="_parent">Letras</a> - 
     <a href="../telechat.php?MM_ACTION=logout" target="_parent">Log-Out</a>
    </th>
   </tr><tr>
    <th colspan="2"><div align="right">
     <input type="hidden" id="popup" name="popup" value="<?php echo $popup ?>">
     <input type="submit" name="submit2" value="Mostrar"></div>
    </th>
    <th>
     <script language="javascript1.2">
      cp.writeDiv()
     </script>
     <div align="right"><input type="submit" name="submit" value="Agregar"></div></th>
   </tr>
  </table>
 </form>
 </body> 
 <script type="text/javascript">
  Calendar.setup({
   inputField     :    "desde",             // id of the input field
   ifFormat       :    "%Y-%m-%d %H:%M:%S", // format of the input field
   showsTime      :    false,               // will display a time selector
   button         :    "desde_btn",         // trigger for the calendar (button ID)
   singleClick    :    true,                // double-click mode
   step           :    1                    // show all years in drop-down boxes (instead of every other year as default)
  });
 
  Calendar.setup({
   date           :    "2001/01/01",
   inputField     :    "hasta",             // id of the input field
   ifFormat       :    "%Y-%m-%d %H:%M:%S", // format of the input field
   showsTime      :    false,               // will display a time selector
   button         :    "hasta_btn",         // trigger for the calendar (button ID)
   singleClick    :    true,                // double-click mode
   step           :    1                    // show all years in drop-down boxes (instead of every other year as default)
  });
 </script>
</html>