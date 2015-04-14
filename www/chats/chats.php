<?php

class chat {
 private $templateLogin    = "";
 private $templateHTML     = "chats/html.html";
 private $templateResponse = "chats/respuesta.html";
 private $templateHorario  = "chats/horario.html";
 private $templateSorteo   = "chats/sorteo.html";
 
 private $title       = "Chats";
 private $table       = "chats";
 private $tableDetail = "chats_participantes";
 private $sessionVar  = "idChat";
 private $url         = "";
 private $pagina      = 0; 
 private $limit       = 25;
 private $desde;
 private $hasta;
 private $enableChat = 0;
 
 private $mensaje_respuesta;

 /*private $idChat;
 private $cntParticipantes = 0;
 private $selfService;
 private $mensaje_b;*/

 function chat                      (){
  session_start();
  if (!isset($_SESSION[$this->sessionVar])) if (!$this->doLogin()) $this->printLogin();
  $this->constructor();
 }
 
 function extend                    ($templateLogin, $templateHTML, $templateResponse, $templateHorario, $templateSorteo = "", $title = ""){
  $this->templateHTML     = $templateHTML;
  $this->templateLogin    = $templateLogin;
  $this->templateResponse = $templateResponse;
  $this->templateHorario  = $templateHorario;
  if ($templateSorteo != ""){
      $this->templateSorteo = $templateSorteo;
  }
  if ($title != ""){
      $this->title = $title;
  }
 }
 function action                    (){
  $action = isset($_POST['MM_ACTION']) ? $_POST['MM_ACTION'] : (isset($_GET['MM_ACTION']) ? $_GET['MM_ACTION'] : "");
  switch ($action){
   case "actualizarDatos":
    $this->actualizarDatos();
    break;
   case "logout":
    $this->doLogout();
    $this->printLogin();
    break;
   case "cancelarGanadores":
    $this->cancelarGanadores();
    break;
   case "marcarContestado":
    $this->marcarContestado();
	break;
   case "reiniciarChat":
    $this->reiniciarchat();
	break;
   case "imagen":
    $this->printImagen();
	break;
   case "grafico":
    $this->printGrafico();
	break;
   case "graficoHorario":
    $this->printGraficoHorario();
	break;
   case "graficoMensual":
    $this->printGraficoMensual();
	break;
   case "insertarRespuesta":
    $this->insertarRespuesta();
	break;	
   case "respuestaMensaje":
    $this->printFormularioRespuesta();
	break;	
   case "sorteo":
    $this->printSorteo();
	break;	
   case "manejarHorario":
    $this->printManejarHorario();
	break;	
   case "actualizarHorarios":
    $this->actualizarHorarios();
	break;		
   case "eliminarHorarios":
    $this->eliminarHorarios();
	break;			
   default:
    $this->printDetail();
	break;
  }
 }
 function constructor               (){
  global $conexion;
  global $database_conexion;
  
  $this->idChat = $_SESSION[$this->sessionVar];
  $this->pagina = ( isset($_GET['pagina'])) ? $_GET['pagina'] : 0;
  mysql_select_db($database_conexion, $conexion);

  $query_cnt     = sprintf("SELECT DATE_FORMAT(MIN(fecha), '%%Y-%%m-%%d %%H:00') AS desde, IFNULL(MAX(id),0) AS lastID FROM chats_participantes WHERE estado=1 AND idChat=%s;", GetSQLValueString($this->idChat, "int"));
  $rs            = mysql_query( $query_cnt, $conexion ) or die(register_mysql_error("EE001", mysql_error()));
  $row           = mysql_fetch_array($rs);
  $this->desde   = $row['desde'];
  $this->lastID  =  $row['lastID'];

  $this->desde     = (isset($_POST['desde' ])) ? $_POST['desde' ] : (isset($_GET['desde' ]) ? $_GET['desde' ] : (($this->desde) ? $this->desde : strftime( "%Y-%m-%d %H:00", time() - (strftime("%H", time()) * 60 * 60 )))); 
  $this->hasta     = (isset($_POST['hasta' ])) ? $_POST['hasta' ] : (isset($_GET['hasta' ]) ? $_GET['hasta' ] : strftime( "%Y-%m-%d %H:59", time() + 3600));
  $this->numero    = (isset($_POST['numero'])) ? $_POST['numero'] : (isset($_GET['numero']) ? $_GET['numero'] : "");
  
  $this->url       = $_SERVER['PHP_SELF'];
 }

 function doLogOut                  (){
  unset($_SESSION['su']);
  unset($_SESSION[$this->sessionVar]);
  session_destroy();
 }
 function doLogin                   (){
  global $conexion;
  global $database_conexion;
  
  $this->doLogout();
  session_start();	 
  
  if (!isset($_POST['username']) || !isset($_POST['password'])){
      return false;
  }
  $usuario = (get_magic_quotes_gpc()) ? $_POST['username'] : addslashes($_POST['username']);
  $clave = (get_magic_quotes_gpc()) ? $_POST['password'] : addslashes($_POST['password']);
  
  mysql_select_db($database_conexion, $conexion) or die(mysql_error());
  $sql = sprintf("SELECT id FROM %s WHERE estado=1 AND usuario='%s' AND clave='%s'", $this->table, $usuario, $clave);
  $rs  = mysql_query($sql, $conexion) or die(mysql_error()); //register_mysql_error("EL001", mysql_error()));
  $row = mysql_fetch_assoc($rs);

  /*if ( mysql_num_rows($rs) == 0 ){
   mysql_free_result($rs);
   $SQL = sprintf("SELECT id FROM %s WHERE estado=1 AND usuario='%s' AND claveAdmin='%s'", $this->table, $usuario, $clave);
   $rs = mysql_query($SQL, $conexion) or die(register_mysql_error("EL002", mysql_error()));
   $row = mysql_fetch_assoc($rs);
   
   $_SESSION['su'] = 1;
  }*/
  if ( mysql_num_rows($rs) == 0 ){
      return false;
  }
  $_SESSION[$this->sessionVar] = $row['id'];
  $_SESSION['username'] = $_POST['username'];
  header("Location: " . $_SERVER['PHP_SELF']);
  exit();
 } 
 
 function actualizarDatos           (){ /* Actualizar datos de la actividad          */ 
  global $conexion;
  if ((isset($_POST["MM_ACTION"])) && ($_POST["MM_ACTION"] == "actualizarDatos")){
   $SQL = array();

   $SQL[sizeof($SQL)] = sprintf("UPDATE chats SET mensaje_respuesta=%s WHERE id=%s;", GetSQLValueString($_POST['mensaje_respuesta'], "text"), GetSQLValueString($this->idChat, "int"));
   foreach ($SQL as $key => $value){
    $rs = mysql_query($value, $conexion) or die(register_mysql_error("ERD00" . $key, mysql_error()));
   }
   header("Location: " . $_SERVER['PHP_SELF']);
   exit();
  }
 }
 function cancelarGanadores         (){ /* Cancelar a todos los ganadores            */ 
  global $conexion;
  
  if ((isset($_POST["MM_ACTION"])) && ($_POST["MM_ACTION"] == "cancelarGanadores")) {
   $SQL = array();
   
   $SQL[sizeof($SQL)] = sprintf("UPDATE %s SET seleccionado=0 WHERE idChat=%s;", $this->tableDetail, GetSQLValueString($this->idChat, "int"));
   foreach ($SQL as $key => $value){ 
    $rs = mysql_query($value, $conexion) or die(register_mysql_error("RCG00" . $key, mysql_error()));
   }
  }
   
  header("Location: " . $_SERVER['PHP_SELF']);
  exit();
 }
 function marcarContestado          (){ /* Marcar como contestado un mensaje         */ 
  global $conexion;
  
  $SQL = array();
  
  $SQL[sizeof($SQL)] = sprintf("UPDATE chats_participantes SET contestado=1 WHERE id=%s;", GetSQLValueString($_GET['idM'], "int"));
  foreach ($SQL as $key => $value){
   $rs = mysql_query($value, $conexion) or die(register_mysql_error("RMC00" . $key, mysql_error()));
  }
   
  header("Location: " . $_SERVER['PHP_SELF']);
  exit();
 }
 function reiniciarchat             (){ /* Reiniciar la chat                         */ 
  global $conexion;
  
  $SQL = array();
  $SQL[sizeof($SQL)] = sprintf("UPDATE chats SET estado=1 WHERE id=%s", GetSQLValueString($this->idChat, "int"));
  $SQL[sizeof($SQL)] = sprintf("UPDATE chats_participantes SET estado=0, fecha_reinicio=NOW() WHERE estado=1 AND idChat=%s", GetSQLValueString($this->idChat, "int"));
   
  foreach ($SQL as $key => $value){
   $rs = mysql_query($value, $conexion) or die(register_mysql_error("RR00" . $key, mysql_error()));
  } 
   
  header("Location: " . $_SERVER['PHP_SELF']);
  exit();
 }
 function insertarRespuesta         (){ /* Insertar Respuesta de un mensaje recibido */ 
  global $conexion;

  if (isset($_POST['mensaje']) && isset($_POST['numero'])){
   $SQL = array();
   $mensaje = $_POST['mensaje'];
   
   $SQL[] = sprintf("INSERT INTO chats_numeros (numero, nombre) VALUES (%s,%s) ON DUPLICATE KEY UPDATE nombre=%s;", GetSQLValueString($_POST['numero'], "text"), GetSQLValueString($_POST['nombre'], "text"), GetSQLValueString($_POST['nombre'], "text"));
   if ($mensaje != "") 
    $SQL[] = sprintf("INSERT INTO chats_respuestas (idChat,numero,mensaje,fecha,estado) VALUES (%s,%s,%s,NOW(),1);", $this->idChat, GetSQLValueString($_POST['numero'], "text"), GetSQLValueString($mensaje, "text"));
   if (isset($_POST['finalizado']) && $_POST['finalizado'])
    $SQL[] = sprintf("UPDATE chats_participantes SET finalizado=1 WHERE idChat=%s AND numero=%s;", $this->idChat, GetSQLValueString($_POST['numero'], "text"));
   foreach ($SQL as $key => $value){
    $rs = mysql_query($value, $conexion) or die(register_mysql_error("CIR00" . $key, mysql_error()));
   }
  }
  header(sprintf("Location: %s?MM_ACTION=respuestaMensaje&contestado&numero=%s", $_SERVER['PHP_SELF'], $_POST['numero']));
  exit();
 }
 function insertarGanador           (){ /* Insertar un nuevo ganador a la chat       */ 
  global $conexion;
  $numero = "    NO HAY ";
  
  $sql = sprintf("SELECT id, numero FROM %s WHERE estado=1 AND seleccionado=0 AND fecha>=%s AND fecha<=%s AND numero NOT IN (SELECT DISTINCT numero FROM %s WHERE estado=1 AND idChat=%s AND seleccionado=1) AND idChat=%s ORDER BY RAND() LIMIT 1;", $this->tableDetail, GetSQLValueString($this->desde, "date"), GetSQLValueString($this->hasta, "date"), $this->tableDetail, $this->idChat, $this->idChat);
  $rsJ = mysql_query($sql, $conexion) or die(register_mysql_error("RR003", mysql_error()));
  if ( mysql_num_rows($rsJ) > 0 ){
   while ( $rowJ = mysql_fetch_array( $rsJ )){
    $numero = $rowJ[ 'numero' ];
    $insertSQL = sprintf("UPDATE %s SET seleccionado=1 WHERE id=%s;", $this->tableDetail, GetSQLValueString($rowJ['id'], "int"));
    $numero = substr($numero,0,strlen($numero)-2) . "??";
    mysql_query($insertSQL, $conexion) or die(register_mysql_error("RR004", mysql_error()));
   }
  }
  return $numero;
 }
 function eliminarHorarios          (){ /* Eliminar uno o m�s horario de una chat    */ 
  global $conexion;
  
  foreach( $_POST['eliminar'] as $id => $value ){
   $sql = sprintf("DELETE FROM chats_horario WHERE idChat=%s AND id=%s;", $this->idChat, $value);
   mysql_query($sql, $conexion)  or die ();
  }
  header("location: ?MM_ACTION=manejarHorario");
  exit(); 
 }
 function actualizarHorarios        (){ /* Actualiza un horario de una chat          */  
  global $conexion;
  
  $dia_lunes     = (isset($_POST['dia_l'])) ? 1 : 0;
  $dia_martes    = (isset($_POST['dia_ma'])) ? 1 : 0;
  $dia_miercoles = (isset($_POST['dia_mi'])) ? 1 : 0;
  $dia_jueves    = (isset($_POST['dia_j'])) ? 1 : 0;
  $dia_viernes   = (isset($_POST['dia_v'])) ? 1 : 0;
  $dia_sabado    = (isset($_POST['dia_s'])) ? 1 : 0;
  $dia_domingo   = (isset($_POST['dia_d'])) ? 1 : 0;
  $hora_inicio  = intval($_POST['hora_inicio']);
  $hora_final    = intval($_POST['hora_final']);
  
  $nombre = $_POST['nombre']; 

  if ($this->verificacionHorario($_POST['hora_inicio'], $_POST['hora_final'], $dia_lunes, $dia_martes, $dia_miercoles, $dia_jueves, $dia_viernes, $dia_sabado, $dia_domingo) > 0){
   header(sprintf("Location: %s?MM_ACTION=manejarHorario&encontrado", $_SERVER['PHP_SELF']));
   exit();
  } 

  if (!isset($_POST['id']) || $_POST['id'] == -1){
   $sql = sprintf("INSERT INTO chats_horario (idChat,nombre,hora_inicio,hora_final,dia_lunes,dia_martes,dia_miercoles,dia_jueves,dia_viernes,dia_sabado,dia_domingo) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s);", $this->idChat, GetSQLValueString($nombre, "text"), $hora_inicio, $hora_final, $dia_lunes, $dia_martes, $dia_miercoles, $dia_jueves, $dia_viernes, $dia_sabado, $dia_domingo);
  } else {
   $sql = sprintf("UPDATE chats_horario SET nombre=%s, hora_inicio=%s, hora_final=%s, dia_lunes=%s, dia_martes=%s, dia_miercoles=%s, dia_jueves=%s, dia_viernes=%s, dia_sabado=%s, dia_domingo=%s WHERE id=%s AND idChat=%s;", GetSQLValueString($nombre, "text"), $hora_inicio, $hora_final, $dia_lunes, $dia_martes, $dia_miercoles, $dia_jueves, $dia_viernes, $dia_sabado, $dia_domingo, $_POST["id"], $this->idChat);
  }
  mysql_query($sql, $conexion)  or die ($sql . mysql_error()); 
  header("Location: ?MM_ACTION=manejarHorario");
  exit();
 }
 
 function verificacionHorario       (){
  global $conexion;

  $encontro = 0;
  $hora_i = intval($_POST['hora_inicio']);
  $hora_f = intval($_POST['hora_final']);
  $dia_l  = intval((!isset($_POST['dia_l']) || $_POST['dia_l']  == 0) ? -1 : 1);
  $dia_ma = intval((!isset($_POST['dia_ma']) || $_POST['dia_ma'] == 0) ? -1 : 1);
  $dia_mi = intval((!isset($_POST['dia_mi']) || $_POST['dia_mi'] == 0) ? -1 : 1);
  $dia_j  = intval((!isset($_POST['dia_j']) || $_POST['dia_j']  == 0) ? -1 : 1);
  $dia_v  = intval((!isset($_POST['dia_v']) || $_POST['dia_v']  == 0) ? -1 : 1);
  $dia_s  = intval((!isset($_POST['dia_s']) || $_POST['dia_s']  == 0) ? -1 : 1);
  $dia_d  = intval((!isset($_POST['dia_d']) || $_POST['dia_d']  == 0) ? -1 : 1); 
  
  $sql = sprintf("SELECT COUNT(*) AS encontro FROM chats_horario WHERE idChat=%s AND ((hora_inicio=$hora_i OR hora_final=$hora_f) OR (hora_inicio<=$hora_i AND hora_final>$hora_i) OR (hora_inicio<$hora_f AND hora_final>=$hora_f)) AND (dia_lunes=$dia_l OR dia_martes=$dia_ma OR dia_miercoles=$dia_mi OR dia_jueves=$dia_j OR dia_viernes=$dia_v OR dia_sabado=$dia_s OR dia_domingo=$dia_d);", $this->idChat);
  $res_ficharep = mysql_query($sql,$conexion);
  $row_ficharep = mysql_fetch_array($res_ficharep); 
  $encontro =$row_ficharep['encontro']; 
  return $encontro;
 }
  
 function printMensajeRespuesta     (){
  $html = "";
  if ( true || $this->selfService ){ 
   $html = "<tr><td colspan='3'>&nbsp;</td></tr>
    <tr><th colspan='3' style='text-align: center'>Mensaje(s) de Respuesta</th></tr>
    <tr><td colspan='3'>&nbsp;</td></tr>
     <form method='post'>
     <tr>
	  <td>Respuesta:</td>
      <td colspan='2'><input class='textbox-large' type='text' name='mensaje_respuesta' value='" . $this->mensaje_respuesta . "' maxlength='254' /></td>
	 </tr><tr><td colspan='3'>&nbsp;</td></tr><tr>
	  <td colspan='3' style='text-align: right'>
       <input type='hidden' name='MM_ACTION' value='actualizarDatos' />
       <input type='submit' class='button' value='Actualizar'>
      </td>
	 </tr>
    </form>";
   }
   return $html;
 }
 function printConteoParticipantes  (){
  $html = "";
  $html = "<tr>
   <td>Cantidad de Participantes:</td>
   <td colspan='2' style='text-align: right'>" . $this->cntParticipantes . "</td>
  </tr>";
  return $html;  
 }
 function printManejarHorarios      (){
  $html = "";
  if ( isset( $_SESSION['su'] )){ 
   $html .= "<tr><th colspan='3' style='text-align: right'><a href='?MM_ACTION=manejarHorario'>Manejar Horarios</a></th></tr>";
  }
  return $html;
 }
 function printGanadores            (){
  global $conexion;
 
  $SQL       = sprintf("SELECT DISTINCT numero FROM %s WHERE seleccionado=1 AND idChat=%s;", $this->tableDetail, GetSQLValueString($this->idChat, "int"));
  $rsNumeros = mysql_query($SQL, $conexion) or die(register_mysql_error("RPG001", mysql_error())); 
  
  $html = "";
  while( $row_rsNumeros = mysql_fetch_assoc($rsNumeros) ){ 
   $html .= "<tr><td style='text-align: center' colspan='3'>" . $row_rsNumeros['numero'] . "</td></tr>";
  } 
  return $html;
 }
 function printListadoChats         (){
  global $conexion;
 
  $SQL       = sprintf("SELECT DISTINCT N.numero, IFNULL(C.nombre,'') AS nombre FROM %s N LEFT OUTER JOIN chats_numeros C ON C.numero = N.numero WHERE estado=1 AND idChat=%s AND fecha>=DATE_ADD(NOW(), INTERVAL -15 DAY) AND finalizado=0;", $this->tableDetail, GetSQLValueString($this->idChat, "int"));
  $rsNumeros = mysql_query($SQL, $conexion) or die(register_mysql_error("RPG001", mysql_error())); 
  
  $html1 = ""; $html2 = ""; $contestado = "";
  while( $row = mysql_fetch_assoc($rsNumeros) ){ 
   $nombre = ($row['nombre'] != '') ? $row['nombre'] : $row['numero'];
   $linea = "<tr><td style='text-align: center; width: 100%'><input type='button' class='msg-button' value='" . $nombre . "' onclick=\"javascript: popUpChat('?MM_ACTION=respuestaMensaje&numero=" . $row['numero'] . "')\" /></td></tr>";
   if (intval(substr(strrev($row['numero']),0,1)) % 2 == 0)
    $html1 .= $linea;
   else 
    $html2 .= $linea;
  } 
  $html = sprintf("<tr><td colspan='3'><table style='width: 100%%'><tr><td style='align: center; width: 48%%'><table style='text-align: center; width: 100%%'>%s</table></td><td>&nbsp;</td><td style='align: center; width: 48%%'><table style='text-align: center; width: 100%%'>%s</table></td></tr></table></td></tr>", $html1, $html2);
  return $html;
 }
 function printMayoresParticipantes (){
  global $conexion;
 
  $SQL       = sprintf("SELECT numero, conteo FROM (SELECT numero, COUNT(*) AS conteo FROM %s WHERE estado=1 AND %s=%s GROUP BY numero) DT ORDER BY conteo DESC LIMIT 5;", $this->tableDetail, $this->sessionVar, GetSQLValueString($_SESSION[$this->sessionVar], "int"));
  $rsNumeros = mysql_query($SQL, $conexion) or die(register_mysql_error("RPG001", mysql_error())); 
  
  $html = "";
  while( $row_rsNumeros = mysql_fetch_assoc($rsNumeros) ){ 
   $html .= sprintf("<tr><td style='text-align: center' colspan='4'>%s (%s)</td></tr>", $row_rsNumeros['numero'], $row_rsNumeros['conteo'] );
  } 
  return $html;
 }
 function printDetalleGanadores     (){
  global $conexion;
  $sql = sprintf( "SELECT numero FROM %s WHERE idChat=%s AND seleccionado=1;", $this->tableDetail, $this->idChat );
  $rs = mysql_query($sql, $conexion) or die(register_mysql_error("RR005", mysql_error()));
  
  $html = "";
  while ($row = mysql_fetch_assoc($rs)){
   $html .= sprintf("<tr><td align='center' colspan='4'>%s</td></tr>", $row['numero'] );
  }
  return $html;
 }
 function printReporteMensual       (){
  $html = "";	 
  //if ( !isset( $_SESSION['su'] )) return $html;
  
  $g = getdate(); 
  $html = "
   <tr><th colspan='3'>Reporte Mensual</th></tr>
   <tr><td colspan='3'>&nbsp;</td></tr>
   <tr>
    <td style='text-align: center' colspan='3'>
     <table border='0' cellspacing='0' cellpadding='0' align='center'>
      <form id='form' name='form' method='post' action='" . $_SERVER['PHP_SELF'] . "'>
       <tr>
        <td>Mes:</td>
        <td>
         <select name='month' style='width: 100%'>";
   foreach (monthArray() as $key => $name ){
    $selected = ($key == $g['mon']) ? "selected" : "";
    $html .= "<option value='$key' $selected>$name</option>";
   }
   $html .="</select>
        </td>
       </tr><tr>
      <td>A&ntilde;o:</td>
      <td>
       <select name='anio' style='width: 100%'>";
   for( $i = $g['year']; $i > 2004; --$i){
    $selected = ($i == $g['year']) ? "selected" : "";
    $html .= "<option value='$i' $selected >$i</option>";
   } 
   $html .="</select>
      </td>
     </tr>
     <tr><td colspan='2'>&nbsp;</td></tr>
     <tr><td colspan='2' style='text-align: right'><input type='button' class='button' onClick='this.form.submit()' value='Mostrar' /></td></tr>
     <tr><td colspan='2' >&nbsp;</td></tr>
    </form>";
   if ( isset( $_POST['month'] ) && isset( $_POST['anio'] )){
    $m = $_POST['month'];
    $y = $_POST['anio'];
    $html .= "<tr><td colspan='2'><a href='?MM_ACTION=graficoMensual&mes=$m&anio=$y&width=900&height=300'><img src='?MM_ACTION=graficoMensual&mes=$m&anio=$y&width=450&height=150' border='1' /></a></td></tr>";
   }
   $html .= "</table>
     </td>
    </tr>";
  return $html;
 }
 function printParticipantes        (){
  global $conexion;
  
  $condition = ($this->numero == "" ) ? "" : sprintf("AND rp.numero=%s", GetSQLValueString($this->numero,"text"));
  $SQL = sprintf("SELECT rp.id, rp.numero, rp.fecha, rp.mensaje, rp.contestado FROM chats r, chats_participantes rp WHERE rp.estado=1 AND r.id=rp.idChat AND rp.idChat=%s AND fecha>=%s AND fecha<=%s %s ORDER BY rp.id DESC", GetSQLValueString($this->idChat, "int"), GetSQLValueString($this->desde, "date"), GetSQLValueString($this->hasta, "date"), $condition);
  $cntSQL = sprintf ("SELECT COUNT(*) AS conteo FROM (%s) dt", $SQL);
  $rs = mysql_query($cntSQL, $conexion) or die(register_mysql_error("RR001", mysql_error()));
  $row = mysql_fetch_array($rs);
  $this->cntParticipantes = $row['conteo'];
  
  $SQL = sprintf("%s LIMIT %s, %s", $SQL, $this->pagina * $this->limit, $this->limit );
  $rs = mysql_query($SQL, $conexion) or die(register_mysql_error("RR002", mysql_error()));
  
  $html = "";
  $i = $this->pagina * $this->limit;
  while ($row = mysql_fetch_array( $rs )){
   $contestado = ($row['contestado'] == 1) ? "checked disabled" : "";
   $style      = ($row['contestado'] == 1) ? "" : "color: #000000";
   $html .= "<tr>
    <td scope='row' class='content'>";
    $html .="<input type='button' class='msg-button' value='" . $row['numero'] . "' onclick=\"javascript: popUpChat('?MM_ACTION=respuestaMensaje&numero=" . $row['numero'] . "')\" /><!--<input type='checkbox' name='chkMarca' class='checkbox' onClick=\"javascript: if ( confirm('�Desea marcar como contestada?')) window.location='" . $this->url ."?MM_ACTION=marcarContestado&idM=" . $row['id'] . "'\" " . $contestado . " />-->";
   $html .= "</td>
    <td scope='row' class='content' style='max-width: 250px; overflow: hidden; " . $style . "'>" . $row['mensaje'] . "</td>
    <td scope='row' class='content'>" . $row['fecha'] . "</td>
   </tr>";
   ++$i;
  }
	  
  $anterior  = (!$this->pagina == 0) ? "<a href='$this->url?pagina=" . ($this->pagina - 1) . "'>&laquo; Anterior</a>" : "";
  $siguiente = "<a href='$this->url?pagina=" . ($this->pagina + 1) . "'>Siguiente &raquo;</a>";

  $html .= "<tr><td colspan='4'>&nbsp;</td></tr>";
  $html .= sprintf("<tr><th colspan='4' style='text-align: right'>%s <a>-</a> %s</th></tr>", $anterior, $siguiente);
  return $html;
 }
 function printGraficos             (){
  $html = "";
  $g = getdate();
  if ( isset( $_GET['month'] ) && isset( $_GET['anio'] )){
   $m = $_GET['month'];
   $y = $_GET['anio'];
  } elseif ( isset( $_POST['month'] ) && isset( $_POST['anio'] )){
   $m = $_POST['month'];
   $y = $_POST['anio'];
  } else {
   $m = $g['mon'];
   $y = $g['year'];
  }
  $html = "
   <tr><td colspan='3'>&nbsp;</td></tr>
    <tr>
	 <td style='text-align: center;' colspan='3'>
     <table cellpadding='0' cellspacing='0' align='center'>
      <form id='form' name='form' method='get' action='?MM_ACTION=grafico'>
       <tr>
        <td>Mes:</td>
        <td>
         <select name='month' style='width: 100%'>";
  foreach (monthArray() as $key => $name ){
   $selected = ($key == $m) ? "selected" : "";
   $html .= "<option value='$key' $selected>$name</option>";
  }
  $html .="</select>
        </td>
       </tr><tr>
        <td>A&ntilde;o:</td>
        <td>
         <select name='anio' style='width: 100%'>";
  for( $i = $g['year']; $i > 2004; --$i){
   $selected = ($i == $y) ? "selected" : "";
   $html .= "<option value='$i' $selected >$i</option>";
  } 
  $html .="</select>
        </td>
       </tr>
       <tr><td colspan='2'>&nbsp;</td></tr>
       <tr><td colspan='2' style='text-align: right'>
        <input type='hidden' name='height' value='480' />
        <input type='hidden' name='width' value='640' />
        <input class='button' type='button' onClick='this.form.submit()' value='Mostrar' />
       </td></tr>
       <tr><td colspan='2'>&nbsp;</td></tr>
      </form>
     <tr><td colspan='2'>
      <a href='?MM_ACTION=grafico&height=480&width=640&month=$m&anio=$y'>
       <img src='?MM_ACTION=grafico&height=240&width=320&month=$m&anio=$y' border='1' align='top'/>
      </a>
     </td></tr>
    </table>          
   </td></tr>";
  return $html;
 }
 
 function printLogin                (){
  global $_CONF;
  if ($this->templateLogin  == ""){ /* No se ha configurado que plantilla de inicio de sesion utilizar */
   $this->templateLogin = "templates/login.html";
   if (isset($_CONF['skin']) && $_CONF['skin'] != "") $this->templateLogin = "skins/" . $_CONF['skin'] . "/login.html";
  }

  $html = file_get_contents($this->templateLogin);
  $html = str_replace("@@TITLE@@",$this->title,$html);
  echo $html;
  exit();
 }
 function printImagen               (){
  global $conexion;
  $rs = mysql_query(sprintf("SELECT logo_archivo, logo_tipo FROM chats WHERE id=%s;", GetSQLValueString($this->idChat,"int")), $conexion) or die(register_mysql_error("RI001", mysql_error()));
  $row    = mysql_fetch_assoc($rs);
  $data   = $row[ "logo_archivo" ];
  $type   = $row[ "logo_tipo" ];
  header( "Content-type: $type");
  echo $data;
 }
 function printGrafico              (){
  global $conexion;
  global $_CONF;
  
  $title = ""; 
  $this->desde  = (isset($_GET['month']) && isset($_GET['anio'])) ? $_GET['anio'] . "-" . $_GET['month'] . "-01" : date("Y-m-01");
  $this->hasta  = (isset($_GET['month']) && isset($_GET['anio'])) ? date('Y-m-d 23:59:59', strtotime('-1 second', strtotime('+1 month', strtotime($_GET['month'] . '/01/' . $_GET['anio'] .' 00:00:00')))) : date('Y-m-d 23:59:59', strtotime('-1 second', strtotime('+1 month', strtotime(date('m') . '/01/' . date('Y') .' 00:00:00'))));
  
  $sql   = sprintf("SELECT DayOfMonth(fecha) AS descripcion, COUNT(*) AS cantidad, '%s' AS color FROM chats_participantes WHERE estado=1 AND idChat=%s AND fecha>=%s AND fecha<%s GROUP BY DayOfMonth(fecha)", $_CONF['bargraph-color'], $this->idChat, GetSQLValueString($this->desde, "date"), GetSQLValueString($this->hasta, "date"));
  $result = mysql_query($sql, $conexion) or die();

  $i = 0;
  $colors = array(); $datax = array(); $datay = array();
  while ($row = mysql_fetch_assoc($result)){
   $datax [$i] = $row['descripcion'];
   $datay [$i] = $row['cantidad'];
   $colors[$i] = "#" . trim( $row['color'] );
   ++$i;
  }

  if ( $i == 0){
   $datax [$i] = "";
   $datay [$i] = 0;
  }

  $dataxR = array(); $datayR = array();
  foreach ( $datax as $key => $index )
   $dataxR[sizeof($dataxR)] = $index; 

  foreach ( $datay as $key => $index )
   $datayR[sizeof($datayR)] = $index;

  $width  = $_GET['width'];
  $height = $_GET['height'];
  $font = $width / 320 * 5;

  $graph = new Graph($width,$height,"auto");     
  $graph->SetScale("textlin");
  $graph->img->SetMargin(20,20,20,75);
  $graph->SetMarginColor('white');
  $graph->xaxis->SetFont(FF_VERDANA,FS_NORMAL,$font);
  $graph->yaxis->SetFont(FF_VERDANA,FS_NORMAL,$font);
  $graph->yaxis->SetLabelMargin(25);
  $graph->xaxis->SetLabelMargin(25);
  $graph->xaxis->SetLabelAngle(90);

  $graph->title->Set($title);
  $graph->title->SetFont(FF_VERDANA,FS_NORMAL,$font);
  $graph->subtitle->Set(" ");
  $graph->xaxis->SetTickLabels($dataxR);

  $graph->xaxis->title->SetFont(FF_VERDANA,FS_NORMAL,$font);
  $graph->yaxis->title->SetFont(FF_VERDANA,FS_NORMAL,$font);
  $graph->xaxis->title->Set("");
  $graph->yaxis->title->Set("");

  $bplot = new BarPlot($datay);

  $bplot->value->Show();
  $bplot->value->SetFormat('%d');
  $bplot->value->SetFont(FF_FONT1,FS_NORMAL);

  $bplot->SetValuePos('top');
  $bplot->SetWidth(0.7);

  $bplot->SetFillColor( $colors );
  $bplot->SetColor("white");

  $graph->Add($bplot);
  $graph->Stroke();
 }
 function printGraficoHorario       (){
  global $conexion;
  global $_CONF;

  $colors = array();
  $datax = array(); $datay = array();

  $i = 0;
  $num_chats_h = 0;
  $title       = "";

  $this->desde  = (isset($_GET['month']) && isset($_GET['anio'])) ? $_GET['anio'] . "-" . $_GET['month'] . "-01" : date("Y-m-01");
  $this->hasta  = (isset($_GET['month']) && isset($_GET['anio'])) ? date('Y-m-d 23:59:59', strtotime('-1 second', strtotime('+1 month', strtotime($_GET['month'] . '/01/' . $_GET['anio'] .' 00:00:00')))) : date('Y-m-d 23:59:59', strtotime('-1 second', strtotime('+1 month', strtotime(date('m') . '/01/' . date('Y') .' 00:00:00'))));

  $sql   = sprintf( "SELECT a.id, a.nombre AS descripcion, SUM(CASE WHEN (dia_lunes=1 AND dia=1) OR (dia_martes=1 AND dia=2) OR (dia_miercoles=1 AND dia=3) OR (dia_jueves=1 AND dia=4) OR (dia_viernes=1 AND dia=5) OR (dia_sabado=1 AND dia=6) OR
(dia_domingo=1 AND dia=0) THEN b.conteo ELSE 0 END) AS cantidad, '#%s' AS color FROM chats_horario a LEFT OUTER JOIN (SELECT DATE_FORMAT(fecha,'%%w') as dia, DATE_FORMAT(fecha,'%%k') AS hora,COUNT(*) AS conteo FROM chats_participantes WHERE estado=1 AND idChat=%s AND fecha>=%s AND fecha<%s GROUP BY DATE_FORMAT(fecha,'%%w'), DATE_FORMAT(fecha,'%%k')) b ON a.hora_inicio <= b.hora AND IF(a.hora_final=0,24,a.hora_final) > b.hora WHERE idChat=%s GROUP BY a.id, a.nombre ORDER BY a.nombre;", $_CONF['bargraph-color'], $this->idChat, GetSQLValueString($this->desde, "date"), GetSQLValueString($this->hasta, "date"), $this->idChat); 
  $result = mysql_query($sql, $conexion) or die();
  while ($row = mysql_fetch_assoc($result)){
   $datax[$i]   = $row['descripcion'];
   $datay[$i]   = $row['cantidad'];
   $colors[$i]  = $row['color'];
   $num_chats_h = $num_chats_h  + $row['cantidad'];
   ++$i;
  }
 
  $sql = sprintf("SELECT COUNT(*) AS conteo, '#%s' AS color FROM chats_participantes WHERE estado=1 AND idChat=%s AND fecha>=%s AND fecha<%s", $_CONF['bargraph-color'], $this->idChat, GetSQLValueString($this->desde, "date"), GetSQLValueString($this->hasta, "date"));
  $result = mysql_query($sql, $conexion) or die();
  $row = mysql_fetch_assoc($result);
  if ($row['conteo'] > 0 && $row['conteo'] > $num_chats_h){
   $datax [$i] = 'Otros';
   $datay [$i] = $row['conteo'] - $num_chats_h;
   $colors[$i] = $row['color'];
   ++$i;
  }

  if ( $i == 0){
   $datax [$i] = "";
   $datay [$i] = 0;
  }

  $dataxR=array(); $datayR=array();
  foreach ( $datax as $key => $index )
   $dataxR[sizeof($dataxR)] = $index; 
  
  foreach ( $datay as $key => $index )
   $datayR[sizeof($datayR)] = $index;

  $width  = ( isset( $_GET['width'] )) ? $_GET['width'] : 640;
  $height = ( isset( $_GET['height'] )) ? $_GET['height'] : 480;
  $font = $width / 320 * 5;

  $graph = new Graph($width,$height,"auto");     
  $graph->SetScale("textlin");
  $graph->img->SetMargin(20, 20, 20, intval($height / 2.5));
  $graph->SetMarginColor('white');
  $graph->xaxis->SetFont(FF_VERDANA,FS_NORMAL,$font);
  $graph->yaxis->SetFont(FF_VERDANA,FS_NORMAL,$font);
  $graph->yaxis->SetLabelMargin(25);
  $graph->xaxis->SetLabelMargin(25);
  $graph->xaxis->SetLabelAngle(90);

  $graph->title->Set($title);
  $graph->title->SetFont(FF_VERDANA,FS_NORMAL,$font);
  $graph->subtitle->Set(" ");
  $graph->xaxis->SetTickLabels($dataxR);

  $graph->xaxis->title->SetFont(FF_VERDANA,FS_NORMAL,$font);
  $graph->yaxis->title->SetFont(FF_VERDANA,FS_NORMAL,$font);
  $graph->xaxis->title->Set("");
  $graph->yaxis->title->Set("");

  $bplot = new BarPlot($datay);

  $bplot->value->Show();
  $bplot->value->SetFormat('%d');
  $bplot->value->SetFont(FF_FONT1,FS_NORMAL);

  $bplot->SetValuePos('top');
  $bplot->SetWidth(0.7);

  $bplot->SetFillColor( $colors );
  $bplot->SetColor("white");

  $graph->Add($bplot);
  $graph->Stroke(); 
 }
 function printGraficoMensual       (){
  global $conexion;
  global $_CONF;
  $mes  = $_GET['mes'];
  $anio = $_GET['anio'];

  $mes2  = $mes % 12 + 1;
  $anio2 = $mes < 12 ? $anio : $anio + 1;     

  $sql = sprintf("SELECT DAY(fecha) AS fecha, COUNT(*) AS conteo FROM chats_participantes WHERE estado=1 AND fecha>='$anio-$mes-01' AND fecha < '$anio2-$mes2-01' AND idChat=%s GROUP BY DAY(fecha) ORDER BY DAY(fecha)", $this->idChat); 
  $rs = mysql_query($sql, $conexion) or die( register_mysql_error("RG001", mysql_error()));
  $num = mysql_num_rows($rs);

  $dx = array();
  $dy = array();

  $dm = DaysInMonth( $anio, $mes );

  for( $i = 0; $i <= $dm+1 ; $i++ ){
   $dx[$i] = ($i%2 == 1) ? "" : $i+1;
   $dy[$i] = 0;
  }

  $sum = 0;
  for( $i = 0; $i < $num; ++$i){
   $r = mysql_fetch_array($rs);
   $dy[$r[0]-1] = $r[1];
   $sum += $r[1];
  }

  $datay = array ( 12, 8, 1, 3, 10, 5);

  $width  = ( isset( $_GET['width'] ))  ? $_GET['width' ] : 450;
  $height = ( isset( $_GET['height'] )) ? $_GET['height'] : 150;

  $graph = new Graph( $width, $height, "auto");    
  $graph->SetScale("textlin");

  $graph->SetShadow();
  $graph->xaxis->SetTickLabels($dx);
  $graph->img->SetMargin( 45, 85, 20, 40);

  $bplot = new BarPlot($dy);
  $bplot->SetFillColor('orange');
  $graph->Add($bplot);
  $bplot->value->Show();
  $bplot->value->SetFormat('%d');
  $bplot->value->SetFont(FF_FONT1,FS_BOLD);
  $bplot->SetWidth(0.7);

  $graph->title->Set("Grafica para Mes de " . getMes($mes));
  $graph->xaxis->title->Set("Dia");
  $graph->yaxis->title->Set("Mensajes");

  $graph->title->SetFont(FF_FONT1,FS_BOLD);
  $graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD);
  $graph->xaxis->title->SetFont(FF_FONT1,FS_BOLD);

  $bplot->SetLegend("Mes: " . $sum);
  $graph->legend->Pos( 0.02, 0.2, "right", "center");
  $graph->legend->SetLayout(LEGEND_VERT);

  $graph->Stroke();
 }
 
 function printFormularioRespuesta  (){
  global $conexion;
  global $_CONF;
  
  $sql = sprintf("SELECT dt.*, IFNULL(n.nombre, '') AS nombre, finalizado FROM (SELECT 1 AS fuente, numero, mensaje, fecha, finalizado FROM chats_participantes WHERE idChat=%s AND numero=%s UNION SELECT 2 AS fuente, numero, mensaje, fecha, 0 AS finalizado FROM chats_respuestas r WHERE idChat=%s AND numero=%s) dt LEFT OUTER JOIN chats_numeros n ON dt.numero=n.numero ORDER BY fecha;", $this->idChat, GetSQLValueString($_GET['numero'], "text"), $this->idChat, GetSQLValueString($_GET['numero'], "text"));
  $rs  = mysql_query($sql, $conexion) or die(register_mysql_error("EL001", mysql_error())); //echo $sql;
  $response  = ""; $nombre = ""; $finalizado = false;
  while ($row = mysql_fetch_assoc($rs)){
   if ($row['fuente'] == 1){
    if ($row['nombre'] != ""){
     $usuario    = $row['nombre'];
	 $nombre     = $row['nombre'];
	} else {
     $usuario = $row['numero'];
	 $nombre  = $row['numero'];
	}
    $finalizado = $row['finalizado'];
   } else {
	$usuario = 'Yo';
   }
   $color   = ($row['fuente'] == 1)  ? '#000' : '#FFF';
   $response .= sprintf("<tr><td style='color: %s' colspan='2'><b>%s:</b> %s</td></tr>", $color, $usuario, $row['mensaje']);
  }
  
  $checked = ($finalizado) ? "checked" : ""; 
  $response .= sprintf("<tr><td colspan='2'><textarea rows='5' name='mensaje' onkeyup='maxsize(this)' style='width: 95%%'></textarea></td></tr>
   <tr>
    <td scope='row'>
     <input type='textbox' class='textbox-small' name='nombre' value='%s'>
    </td>
    <td scope='row'><div align='right'>
     <input type='checkbox' name='finalizado' alt='Marcar como finalizado' style='width: 30px' %s />
     <input type='button' class='button' onclick='javascript: if (validateTextArea(this.form.mensaje)){ this.form.submit(); }' name='Submit' value='Enviar'>
     <input type='hidden' name='numero' value='%s' />
     <input type='hidden' name='MM_ACTION' value='insertarRespuesta' />
   </div></td></tr>", $nombre, $checked, $_GET['numero']);

  if (isset($_CONF['skin']) && $_CONF['skin'] != "") $this->templateResponse = "skins/" . $_CONF['skin'] . "/respuesta.html";
  $html = file_get_contents($this->templateResponse);
  $html = str_replace("@@CONTENIDO@@", $response, $html);
  $html = str_replace("@@TITLE@@", $this->title . ": " . $nombre, $html);
  if (isset($_CONF['skin']) && $_CONF['skin'] != "") $html = str_replace("@@SKIN@@", $_CONF['skin'], $html);
  echo $html;
 }
 function printSorteo               (){
  $numero = $this->insertarGanador();

  $html = file_get_contents($this->templateSorteo);
  $html = str_replace("@@NUMERO@@", $numero, $html);
  $html = str_replace("@@GANADORES@@", $this->printGanadores(), $html);
  $html = str_replace("@@DETALLE@@" , $this->printDetalleGanadores(), $html);
  echo $html;
 }
 function printManejarHorario       (){
  global $_CONF;
  global $conexion;
  
  if(isset($_GET["id"])){ 
   $sql = sprintf("SELECT id,nombre, hora_inicio, hora_final, dia_lunes AS dia_l, dia_martes AS dia_ma, dia_miercoles AS dia_mi, dia_jueves AS dia_j, dia_viernes AS dia_v, dia_sabado AS dia_s, dia_domingo AS dia_d FROM chats_horario WHERE id=%s;", GetSQLValueString($_GET["id"], "int"));
   $res_fichash = mysql_query($sql, $conexion); 
   $row_fichash = mysql_fetch_array($res_fichash);
  }
  
  $sql = sprintf("SELECT id, nombre, hora_inicio, hora_final, dia_lunes, dia_martes, dia_miercoles, dia_jueves, dia_viernes, dia_sabado, dia_domingo FROM chats_horario WHERE idChat=%s ORDER BY hora_inicio;", $this->idChat);
  $res_chats = mysql_query($sql, $conexion);
  $detalle = "";
  while( $row_chats = mysql_fetch_array($res_chats) ){ 
   $detalle .= sprintf("<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td>
    <td rowspan='1' style='text-align: center; vertical-align: middle'><input name='eliminar[]' type='checkbox' value='%s' enabled></td>
	<td rowspan='1' style='text-align: center; vertical-align: middle'><a href='?MM_ACTION=manejarHorario&id=%s'>Modificar</a></td>
   </tr>", $row_chats['nombre'], abreviaturaDias($row_chats), formatearHora($row_chats['hora_inicio']), formatearHora($row_chats['hora_final']), $row_chats['id'], $row_chats['id']);
  } 
  
  $alerta = (isset($_GET['encontrado']))    ? "onLoad='javascript: alert(\"Esta configuraci�n de horario hace conflicto con uno ya existente\")'" : "";
  $nombre = (isset($row_fichash['nombre'])) ? $row_fichash['nombre'] : "";
  $id     = (isset($_GET["id"])) ? $_GET["id"] : -1;
  
  $comparador   = (isset($row_fichash['hora_inicio'])) ? $row_fichash['hora_inicio'] : -1;
  $selectInicio = selectHoras($comparador, "hora_inicio");
  $comparador   = (isset($row_fichash['hora_final'])) ? $row_fichash['hora_final'] : -1;
  $selectFinal  = selectHoras($comparador, "hora_final");
  
  $row_fichash = (isset($row_fichash) ? $row_fichash : array());
  $dias        = inputsDias($row_fichash);
  
  if (isset($_CONF['skin']) && $_CONF['skin'] != "") $this->templateHorario = "skins/" . $_CONF['skin'] . "/horario.html";
  $html = file_get_contents($this->templateHorario);
  $html = str_replace("@@ALERTA@@",$alerta, $html);
  $html = str_replace("@@NOMBRE@@",$nombre, $html);
  $html = str_replace("@@SELECTINICIO@@", $selectInicio, $html);
  $html = str_replace("@@SELECTFINAL@@" , $selectFinal,  $html);
  $html = str_replace("@@DIAS@@" , $dias,  $html);
  $html = str_replace("@@ID@@"   , $id,  $html);
  $html = str_replace("@@DETALLE@@"   , $detalle,  $html);
  if (isset($_CONF['skin']) && $_CONF['skin'] != "") $html = str_replace("@@SKIN@@", $_CONF['skin'], $html);
  
  echo $html;
  exit();
 }
 function printNotification         (){
  $html = ""; $js = "";
  $array = $this->newMessages();
  if (sizeof($array)>0){
   foreach( $array as $key => $value){
    $js   .= sprintf("HTMLnotification('?MM_ACTION=respuestaMensaje&numero=%s'); ", $value);
   }
   $html = sprintf("onLoad=\"%s\"", $js); //'http://www.google.com'
  }
  return $html;
 }

 function newMessages            (){
  global $conexion;
  
  $retVal = array();
  $SQL = sprintf("SELECT DISTINCT numero FROM chats_participantes WHERE estado=1 AND idChat=%s AND id>%s;", GetSQLValueString($this->idChat, "int"), $this->lastID);
  $rs  = mysql_query($SQL, $conexion) or die(register_mysql_error("RDG001", mysql_error()));
  while ($row = mysql_fetch_array( $rs )){
   $retVal[sizeof($retVal)] = $row['numero'];
  }
  $_SESSION['lastID'] = $this->lastID;
  return $retVal;
 }
 
 function printDetail             (){
  global $_CONF;
  global $conexion;

  /* Datos Generales */
  $SQL      = sprintf( "SELECT mensaje_respuesta FROM %s WHERE id=%s;", $this->table, GetSQLValueString($this->idChat, "int"));
  $rsp      = mysql_query($SQL, $conexion) or die(register_mysql_error("RDG001", mysql_error()));
  $row      = mysql_fetch_array( $rsp );            

  $this->mensaje_respuesta     = $row['mensaje_respuesta'];
  /*$this->mensaje_b     = $row['mensaje_participante_b'];
  $this->enableChat    = $row['chat'];
  $this->selfService   = ($row['selfservice'] == 1);*/
  
  if (isset($_CONF['skin']) && $_CONF['skin'] != "") $this->templateHTML = "skins/" . $_CONF['skin'] . "/chats.html";
  $html = file_get_contents($this->templateHTML);
  
  $html = str_replace("@@URL@@",                  "60;$this->url",                    $html);
  $html = str_replace("@@TITLE@@",                $this->title,                       $html);
  $html = str_replace("@@NOTIF@@",                $this->printNotification(),         $html);
  $html = str_replace("@@RESPUESTA@@",            $this->printMensajeRespuesta(),     $html);
  $html = str_replace("@@PARTICIPANTES@@",        $this->printParticipantes(),        $html);
  $html = str_replace("@@CONTEOPARTICIPANTES@@",  $this->printConteoParticipantes(),  $html);
  $html = str_replace("@@HORARIOS@@",             $this->printManejarHorarios(),      $html);
  $html = str_replace("@@GANADORES@@",            $this->printGanadores(),            $html);
  $html = str_replace("@@CHATS@@",                $this->printListadoChats(),         $html);
  $html = str_replace("@@MAYORESPARTICIPANTES@@", $this->printMayoresParticipantes(), $html);
  $html = str_replace("@@REPORTEMENSUAL@@",       $this->printReporteMensual(),       $html);
  $html = str_replace("@@GRAFICOS@@",             $this->printGraficos(),             $html);
  $html = str_replace("@@DESDE@@",                $this->desde,                       $html);
  $html = str_replace("@@HASTA@@",                $this->hasta,                       $html);
  $html = str_replace("@@NUMERO@@",               $this->numero,                      $html);
  
  if (isset($_CONF['skin']) && $_CONF['skin'] != "") $html = str_replace("@@SKIN@@", $_CONF['skin'], $html);
  echo $html;
 }
}