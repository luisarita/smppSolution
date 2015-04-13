<?php

class rifa {
 private $templateLogin     = "";
 private $templateHTML      = "rifas/html.html";
 private $templateResponse  = "rifas/respuesta.html";
 private $templateHorario   = "rifas/horario.html";
 private $templateSorteo    = "rifas/sorteo.html";
 private $templateHistorial = "rifas/historial.html";
 
 private $title       = "Rifas";
 private $table       = "rifas";
 private $tableDetail = "rifas_participantes";
 private $sessionVar  = "idRifa";
 private $url         = "";
 private $params      = array();
 private $pagina      = 0; 
 private $limit       = 25;
 
 private $desde;
 private $hasta;
 private $numero;
 private $mensaje;
 
 private $enableChat = 0;
 private $playSounds = 0;
 
 private $idRifa;
 private $cntParticipantes = 0;
 private $selfService;
 private $mensaje_a;
 private $mensaje_b;
 private $autoRefresh;

 function rifa(){
  session_start();
  if (!isset($_SESSION[$this->sessionVar])){
      if (!$this->doLogin()){
          $this->printLogin();
      }
  }
  $this->constructor();
 }
 function extend($templateLogin, $templateHTML, $templateResponse, $templateHorario, $templateSorteo = "", $title = ""){
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
 function action(){
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
   case "reiniciarRifa":
    $this->reiniciarRifa();
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
   case "historialSorteo":
    $this->printHistorialSorteo();
    break;			
   default:
    $this->printDetail();
    break;
  }
 }
 function constructor(){
  global $conexion, $database_conexion;
  
  $this->idRifa = $_SESSION[$this->sessionVar];
  $this->pagina = ( isset($_GET['pagina'])) ? $_GET['pagina'] : 0;
  mysql_select_db($database_conexion, $conexion);

  $query_cnt = sprintf("SELECT DATE_FORMAT(MIN(fecha), '%%Y-%%m-%%d %%H:00') AS desde, MAX(id) AS lastID FROM rifas_participantes WHERE estado=1 AND idRifa=%s;", GetSQLValueString($this->idRifa, "int"));
  $rs        = mysql_query( $query_cnt, $conexion ) or die(register_mysql_error("EE001", mysql_error()));
  $row       = mysql_fetch_array($rs);
  $this->desde   = $row['desde'];
  $this->lastID  =  $row['lastID'];

  $this->desde     = (isset($_POST['desde' ])) ? $_POST['desde' ] : (isset($_GET['desde' ]) ? $_GET['desde' ] : (($this->desde) ? $this->desde : strftime( "%Y-%m-%d %H:00", time() - (strftime("%H", time()) * 60 * 60 )))); 
  $this->hasta     = (isset($_POST['hasta' ])) ? $_POST['hasta' ] : (isset($_GET['hasta' ]) ? $_GET['hasta' ] : strftime( "%Y-%m-%d %H:59", time() + 3600));
  $this->numero    = (isset($_POST['numero']))  ? $_POST['numero']  : (isset($_GET['numero'])  ? $_GET['numero']  : "");
  $this->mensaje   = (isset($_POST['mensaje'])) ? $_POST['mensaje'] : (isset($_GET['mensaje']) ? $_GET['mensaje'] : "");
      
  $this->url       = sprintf("%s", $_SERVER['PHP_SELF']);
  
  if (!empty($_SERVER['QUERY_STRING'])) {
   $params = explode("&", $_SERVER['QUERY_STRING']);
   foreach ($params as $param) {
    array_push($this->params, $param);
   }
  }
 }

 function doLogOut(){
  unset($_SESSION['su']);
  unset($_SESSION[$this->sessionVar]);
  session_destroy();
 }
 function doLogin (){
  global $conexion, $database_conexion;
  
  $this->doLogout();
  session_start();	 
  
  if (!isset($_POST['username']) || !isset($_POST['password'])){
      return false;
  }
  $usuario = (get_magic_quotes_gpc()) ? $_POST['username'] : addslashes($_POST['username']);
  $clave = (get_magic_quotes_gpc()) ? $_POST['password'] : addslashes($_POST['password']);
  
  mysql_select_db($database_conexion, $conexion);
  $sql = sprintf("SELECT id FROM %s WHERE estado=1 AND usuario='%s' AND claveAdmin='%s'", $this->table, $usuario, $clave);
  $rs  = mysql_query($sql, $conexion) or die(register_mysql_error("EL001", mysql_error()));
  $row = mysql_fetch_assoc($rs);

  if ( mysql_num_rows($rs) > 0 ){
   $_SESSION['su'] = 1;
  } else {
   mysql_free_result($rs);
   $sql = sprintf("SELECT id FROM %s WHERE estado=1 AND usuario='%s' AND clave='%s'", $this->table, $usuario, $clave);
   $rs = mysql_query($sql, $conexion) or die(register_mysql_error("EL002", mysql_error()));
   if ( mysql_num_rows($rs) == 0 ){
       return false;
   }
   $row = mysql_fetch_assoc($rs);
  }
  $_SESSION[$this->sessionVar] = $row['id'];
  $_SESSION['username'] = $_POST['username'];
  header("Location: " . $_SERVER['PHP_SELF']);
  exit();
 } 
 
 function actualizarDatos         (){ // Actualizar datos de la actividad          */ 
  global $conexion;
  if ((isset($_POST["MM_ACTION"])) && ($_POST["MM_ACTION"] == "actualizarDatos")){
   $SQL = array();

   $SQL[] = sprintf("UPDATE rifas SET mensaje_participante_a=%s, mensaje_participante_b=%s WHERE id=%s;", GetSQLValueString($_POST['mensaje_a'], "text"), GetSQLValueString($_POST['mensaje_b'], "text"), GetSQLValueString($this->idRifa, "int"));
   foreach ($SQL as $key => $value){
    $rs = mysql_query($value, $conexion) or die(register_mysql_error("ERD00" . $key, mysql_error()));
   }
   header("Location: " . $_SERVER['PHP_SELF']);
   exit();
  }
 }
 function cancelarGanadores       (){ // Cancelar a todos los ganadores            */ 
  global $conexion;
  
  if ((isset($_POST["MM_ACTION"])) && ($_POST["MM_ACTION"] == "cancelarGanadores")) {
   $SQL = sprintf("CALL sp_rifas_cancelar_ganadores(%s)", GetSQLValueString($this->idRifa, "int"));   
   mysql_query($SQL, $conexion) or die(register_mysql_error("RCG00" . $key, mysql_error()));
   
   /*$SQL[sizeof($SQL)] = sprintf("UPDATE rifas_ganadores SET estado=0 WHERE idRifa=%s;", GetSQLValueString($this->idRifa, "int"));
   $SQL[sizeof($SQL)] = sprintf("UPDATE rifas SET estado=1 WHERE id=%s;", GetSQLValueString($this->idRifa, "int"));
   foreach ($SQL as $key => $value){
    $rs = mysql_query($value, $conexion) or die(register_mysql_error("RCG00" . $key, mysql_error()));
   }*/
  }
   
  header("Location: " . $_SERVER['PHP_SELF']);
  exit();
 }
 function marcarContestado        (){ // Marcar como contestado un mensaje         */ 
  global $conexion;
  
  $SQL = array();
  
  $SQL[] = sprintf("UPDATE rifas_participantes SET contestado=1 WHERE id=%s;", GetSQLValueString($_GET['idM'], "int"));
  foreach ($SQL as $key => $value){
   $rs = mysql_query($value, $conexion) or die(register_mysql_error("RMC00" . $key, mysql_error()));
  }
   
  header("Location: " . $_SERVER['PHP_SELF']);
  exit();
 }
 function reiniciarRifa           (){ // Reiniciar la Rifa                         */ 
  global $conexion;
  
  /*$SQL = array();
  $SQL[sizeof($SQL)] = sprintf("UPDATE rifas_ganadores SET estado=0 WHERE idRifa=%s;", GetSQLValueString($this->idRifa, "int"));
  $SQL[sizeof($SQL)] = sprintf("UPDATE rifas SET estado=1 WHERE id=%s", GetSQLValueString($this->idRifa, "int"));
  $SQL[sizeof($SQL)] = sprintf("UPDATE rifas_participantes SET estado=0, fecha_reinicio=NOW() WHERE estado=1 AND idRifa=%s", GetSQLValueString($this->idRifa, "int"));
   
  foreach ($SQL as $key => $value){
   $rs = mysql_query($value, $conexion) or die(register_mysql_error("RR00" . $key, mysql_error()));
  }*/
  
  $SQL = sprintf("CALL sp_rifas_reiniciar(%s)", GetSQLValueString($this->idRifa, "int"));  
  mysql_query($SQL, $conexion) or die(register_mysql_error("RR00" . $key, mysql_error()));
   
  header("Location: " . $_SERVER['PHP_SELF']);
  exit();
 }
 function insertarRespuesta       (){ // Insertar Respuesta de un mensaje recibido */ 
  global $conexion;

  if (isset($_POST['mensaje']) && isset($_POST['id'])){
   $SQL = array();

   $SQL[sizeof($SQL)] = sprintf("UPDATE rifas_participantes SET contestado=1 WHERE id=%s;", GetSQLValueString($_POST['id'], "int"));   
   $SQL[sizeof($SQL)] = sprintf("INSERT INTO rifas_respuestas (idParticipante,mensaje,estado) VALUES (%s,%s,1);", GetSQLValueString($_POST['id'], "int"), GetSQLValueString($_POST['mensaje'], "text"));
   $SQL[sizeof($SQL)] = sprintf("INSERT INTO mensajes_pendientes (numero,numero_salida,mensaje,fecha_salida,prioridad,tipo_servicio) SELECT p.numero, r.numero, rr.mensaje, NOW(), 2, '' FROM rifas r, rifas_respuestas rr, rifas_participantes p WHERE p.idrifa=r.id AND idParticipante=p.id AND rr.id=@@LASTID@@;");
   
   foreach ($SQL as $key => $value){
	$value = str_replace("@@LASTID@@", mysql_insert_id(), $value);
    $rs = mysql_query($value, $conexion) or die(register_mysql_error("RR00" . $key, mysql_error()));
   }
  }
  header(sprintf("Location: %s?MM_ACTION=respuestaMensaje&contestado&id=%s", $_SERVER['PHP_SELF'], GetSQLValueString($_POST['id'], "int")));
  exit();
 }
 function insertarGanador         (){ // Insertar un nuevo ganador a la rifa       */ 
  global $conexion;
  $numero = "    NO HAY ";
  
  $sql = sprintf("SELECT estado, cantidad_ganadores FROM rifas WHERE id=%s;", $this->idRifa);
  $rsR = mysql_query($sql, $conexion) or die(register_mysql_error("RR002", mysql_error()));
  $rowR = mysql_fetch_array( $rsR );
  if ( $rowR[ 'estado' ] == 1 ){
   $cantidad = $rowR[ 'cantidad_ganadores' ];
   //$sql = sprintf("SELECT numero FROM rifas_participantes WHERE estado=1 AND fecha>=%s AND fecha<=%s AND numero NOT IN (SELECT DISTINCT numero FROM rifas_ganadores WHERE estado=1 AND idRifa=%s) AND idRifa=%s AND mensaje LIKE %s ORDER BY RAND() LIMIT %s;", GetSQLValueString($this->desde, "date"), GetSQLValueString($this->hasta, "date"), $this->idRifa, $this->idRifa, GetSQLValueString($this->mensaje . "%", "text"), $cantidad); 
   $sql = sprintf("SELECT numero FROM rifas_participantes WHERE estado=1 AND fecha>=%s AND fecha<=%s AND idRifa=%s AND mensaje LIKE %s ORDER BY RAND() LIMIT %s;", GetSQLValueString($this->desde, "date"), GetSQLValueString($this->hasta, "date"), $this->idRifa, GetSQLValueString($this->mensaje . "%", "text"), $cantidad); 
   //$sql = sprintf("SELECT p.numero FROM rifas_participantes p LEFT OUTER JOIN rifas_ganadores g ON g.idRifa=p.idRifa WHERE p.estado=1 AND p.fecha>=%s AND p.fecha<=%s AND p.idRifa=%s AND p.mensaje LIKE %s ORDER BY RAND() LIMIT %s;", GetSQLValueString($this->desde, "date"), GetSQLValueString($this->hasta, "date"), $this->idRifa, GetSQLValueString($this->mensaje . "%", "text"), $cantidad); 
   //echo $sql;
   $rsJ = mysql_query($sql, $conexion) or die(register_mysql_error("RR003", mysql_error()));
   if ( mysql_num_rows($rsJ) > 0 ){
    while ( $rowJ = mysql_fetch_array( $rsJ )){
     $numero = $rowJ[ 'numero' ];
     $insertSQL = sprintf("INSERT INTO rifas_ganadores (idRifa, numero, estado, fecha, desde, hasta, mensaje) VALUES (%s, %s, 1, NOW(), %s, %s, '%s');", GetSQLValueString($this->idRifa, "int"), GetSQLValueString($numero, "text"), GetSQLValueString($this->desde, "date"), GetSQLValueString($this->hasta, "date"), $this->mensaje);
     $numero = substr($numero,0,strlen($numero)-2) . "??";
     mysql_query($insertSQL, $conexion) or die(register_mysql_error("RR004", mysql_error()));
    }
   }
  }
  return $numero;
 } 
 function eliminarHorarios          (){ // Eliminar uno o m�s horario de una rifa    */ 
  global $conexion;
  
  foreach( $_POST['eliminar'] as $id => $value ){
   $sql = sprintf("DELETE FROM rifas_horario WHERE idRifa=%s AND id=%s;", $this->idRifa, $value);
   mysql_query($sql, $conexion)  or die ();
  }
  header("location: ?MM_ACTION=manejarHorario");
  exit(); 
 }
 function actualizarHorarios        (){ // Actualiza un horario de una rifa          */ 
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
   $sql = sprintf("INSERT INTO rifas_horario (idRifa,nombre,hora_inicio,hora_final,dia_lunes,dia_martes,dia_miercoles,dia_jueves,dia_viernes,dia_sabado,dia_domingo) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s);", $this->idRifa, GetSQLValueString($nombre, "text"), $hora_inicio, $hora_final, $dia_lunes, $dia_martes, $dia_miercoles, $dia_jueves, $dia_viernes, $dia_sabado, $dia_domingo);
  } else {
   $sql = sprintf("UPDATE rifas_horario SET nombre=%s, hora_inicio=%s, hora_final=%s, dia_lunes=%s, dia_martes=%s, dia_miercoles=%s, dia_jueves=%s, dia_viernes=%s, dia_sabado=%s, dia_domingo=%s WHERE id=%s AND idRifa=%s;", GetSQLValueString($nombre, "text"), $hora_inicio, $hora_final, $dia_lunes, $dia_martes, $dia_miercoles, $dia_jueves, $dia_viernes, $dia_sabado, $dia_domingo, $_POST["id"], $this->idRifa);
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
  
  $sql = sprintf("SELECT COUNT(*) AS encontro FROM rifas_horario WHERE idRifa=%s AND ((hora_inicio=$hora_i OR hora_final=$hora_f) OR (hora_inicio<=$hora_i AND hora_final>$hora_i) OR (hora_inicio<$hora_f AND hora_final>=$hora_f)) AND (dia_lunes=$dia_l OR dia_martes=$dia_ma OR dia_miercoles=$dia_mi OR dia_jueves=$dia_j OR dia_viernes=$dia_v OR dia_sabado=$dia_s OR dia_domingo=$dia_d);", $this->idRifa);
  $res_ficharep = mysql_query($sql,$conexion);
  $row_ficharep = mysql_fetch_array($res_ficharep); 
  $encontro =$row_ficharep['encontro']; 
  return $encontro;
 }
  
 function printMensajeRespuesta     (){
  $html = "";
  if ( $this->selfService ){ 
   $html = "<tr><td colspan='3'>&nbsp;</td></tr>
    <tr><th colspan='3' style='text-align: center'>Mensaje(s) de Respuesta</th></tr>
    <tr><td colspan='3'>&nbsp;</td></tr>
     <form method='post'>
     <tr>
	  <td>Respuesta #1:</td>
      <td colspan='2'><input class='textbox-half' type='text' name='mensaje_a' value='" . $this->mensaje_a . "' maxlength='254' /></td>
     </tr><tr>
	  <td>Respuesta #2:</td>
	  <td><input type='text' class='textbox-half'  name='mensaje_b' value='" . $this->mensaje_b . "' maxlength='254' /></td>
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
  if ( isset( $_SESSION['su'] )){
   $html = "<tr>
    <td>Cantidad de Participantes:</td>
    <td colspan='2' style='text-align: right'>" . $this->cntParticipantes . "</td>
   </tr>";
  }
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
 
  $SQL       = sprintf("SELECT g.numero FROM rifas r, rifas_ganadores g WHERE g.estado=1 AND r.id=g.idRifa AND g.idRifa=%s;", GetSQLValueString($this->idRifa, "int"));
  $rsNumeros = mysql_query($SQL, $conexion) or die(register_mysql_error("RPG001", mysql_error())); 
  
  $html = "<tr><th colspan='4'>Ganadores</th></tr>";
  while( $row_rsNumeros = mysql_fetch_assoc($rsNumeros) ){ 
   $html .= "<tr><td style='text-align: center' colspan='4'>" . $row_rsNumeros['numero'] . "</td></tr>";
  } 
  return $html;
 }
 function printHistorialSorteo      (){
  global $conexion;
  global $_CONF;
  
  $sql = sprintf( "SELECT numero, fecha, desde, hasta FROM rifas_ganadores WHERE idRifa=%s ORDER BY fecha DESC;", $this->idRifa );
  $rs = mysql_query($sql, $conexion) or die(register_mysql_error("RR005", mysql_error()));
  
  $contents = "";
  while ($row = mysql_fetch_assoc($rs)){
   $contents .= sprintf("<tr><td align='center'>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>", $row['numero'], $row['fecha'], $row['desde'], $row['hasta'] );
  }
  
  if (isset($_CONF['skin']) && $_CONF['skin'] != "") $this->templateHistorial = "skins/" . $_CONF['skin'] . "/rifas/historial.html";

  $html = file_get_contents($this->templateHistorial);
  $html = str_replace("@@TITLE@@",$this->title,$html);
  $html = str_replace("@@CONTENIDO@@",$contents,$html);
  if (isset($_CONF['skin']) && $_CONF['skin'] != "") $html = str_replace("@@SKIN@@", $_CONF['skin'], $html);
  
  echo $html;
  exit();
 }
 
 function printMayoresParticipantes (){
  global $conexion;
 
  $SQL       = sprintf("SELECT numero, conteo FROM (SELECT numero, COUNT(*) AS conteo FROM %s WHERE estado=1 AND %s=%s GROUP BY numero) DT ORDER BY conteo DESC LIMIT 5;", $this->tableDetail, $this->sessionVar, GetSQLValueString($_SESSION[$this->sessionVar], "int"));
  $rsNumeros = mysql_query($SQL, $conexion) or die(register_mysql_error("RPG001", mysql_error())); 
  
  $html = "";
  while( $row_rsNumeros = mysql_fetch_assoc($rsNumeros) ){ 
   $html .= sprintf("<tr><td style='text-align: center' colspan='3'>%s (%s)</td></tr>", $row_rsNumeros['numero'], $row_rsNumeros['conteo'] );
  }
  return $html;
 }
 function printDetalleGanadores     (){
  global $conexion;
  $sql = sprintf( "SELECT numero, fecha, desde, hasta FROM rifas_ganadores WHERE idRifa=%s AND estado=1 ORDER BY fecha DESC;", $this->idRifa );
  $rs = mysql_query($sql, $conexion) or die(register_mysql_error("RR005", mysql_error()));
  
  $html = "<tr><th colspan='4'>Historial</th></tr>
   <tr><th>Numero</th><th>Fecha</th><th>Desde</th><th>Hasta</th></tr>";
  while ($row = mysql_fetch_assoc($rs)){
   $html .= sprintf("<tr><td align='center'>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>", $row['numero'], $row['fecha'], $row['desde'], $row['hasta'] );
  }
  return $html;
 }
 function printReporteMensual       (){
  $html = "";	 
  if ( !isset( $_SESSION['su'] )) return $html;
  
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
  
  $condition = ($this->numero  == "" ) ? "" : sprintf("AND rp.numero=%s", GetSQLValueString($this->numero,"text") );
  $condition .= ($this->mensaje == "" ) ? "" : sprintf("AND rp.mensaje LIKE %s", GetSQLValueString($this->mensaje . "%","text") );
  $SQL = sprintf("SELECT rp.id, rp.numero, rp.fecha, rp.mensaje, rp.contestado, rp.variable1, rp.variable2, rp.variable3, rp.variable4, rp.variable5 FROM rifas r, rifas_participantes rp WHERE rp.estado=1 AND r.id=rp.idRifa AND rp.idRifa=%s AND fecha>=%s AND fecha<=%s %s ORDER BY rp.id DESC", GetSQLValueString($this->idRifa, "int"), GetSQLValueString($this->desde, "date"), GetSQLValueString($this->hasta, "date"), $condition);
  $cntSQL = sprintf ("SELECT COUNT(*) AS conteo FROM (%s) dt", $SQL);
  $rs = mysql_query($cntSQL, $conexion) or die(register_mysql_error("RR001", mysql_error()));
  $row = mysql_fetch_array($rs);
  $this->cntParticipantes = $row['conteo'];
  
  $SQL2 = sprintf("%s LIMIT %s, %s", $SQL, $this->pagina * $this->limit, $this->limit );
  $rs2 = mysql_query($SQL2, $conexion) or die(register_mysql_error("RR002", mysql_error()));
  
  $html = "";
  $i = $this->pagina * $this->limit;
  while ($row = mysql_fetch_array( $rs2 )){
   $contestado = ($row['contestado'] == 1) ? "checked disabled" : "";
   $style      = ($row['contestado'] == 1) ? "" : "color: #000000";
   $html .= "<tr>
    <td scope='row' class='content'>";
   if ( $this->enableChat ){
    $html .="<input type='button' class='msg-button' value='" . $row['numero'] . "' onclick=\"javascript: popUpChat('?MM_ACTION=respuestaMensaje&id=" . $row['id'] . "')\" /><input type='checkbox' name='chkMarca' class='checkbox' onClick=\"javascript: if ( confirm('�Desea marcar como contestada?')) window.location='" . $this->url ."?MM_ACTION=marcarContestado&idM=" . $row['id'] . "'\" " . $contestado . " />";
   } else {
    $html .= $row['numero'];
   }
   $html .= "</td>
    <td scope='row' class='content' style='max-width: 250px; overflow: hidden; " . $style . "'>" . $row['mensaje'] . "</td>
    <td scope='row' class='content'>" . $row['fecha'] . "</td>
    <td scope='row' class='content'>" . $row['variable1'] . "</td>
    <td scope='row' class='content'>" . $row['variable2'] . "</td>
    <td scope='row' class='content'>" . $row['variable3'] . "</td>
    <td scope='row' class='content'>" . $row['variable4'] . "</td>
    <td scope='row' class='content'>" . $row['variable5'] . "</td>
   </tr>";
   ++$i;
  }

  $params = array();
  foreach ($this->params as $param){
   if (stristr($param, "pagina") == false){
    array_push($params, $param);
   }
  }

  $anterior  = (!$this->pagina == 0) ? "<a href='" . sprintf("%s?%s", $this->url, htmlentities(implode("&", $params))) . "&pagina=" . ($this->pagina - 1) . "'>&laquo; Anterior</a>" : "";
  $siguiente = "<a href='" . sprintf("%s?%s", $this->url, htmlentities(implode("&", $params))) . "&pagina=" . ($this->pagina + 1) . "'>Siguiente &raquo;</a>";

  $html .= "<tr><td colspan='8'>&nbsp;</td></tr>";
  $html .= sprintf("<tr><th colspan='8' style='text-align: right'>%s <a>-</a> %s</th></tr>", $anterior, $siguiente);
  return $html;
 }
 function printGraficos             (){
  $html = "";
  if ( isset( $_SESSION['su'] )){
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
    <form id='form' name='form' method='get' action='?MM_ACTION=grafico'>
    <tr><td style='text-align: center'>
     <a href='?MM_ACTION=grafico&height=480&width=640&month=$m&anio=$y'>
      <img src='?MM_ACTION=grafico&height=240&width=320&month=$m&anio=$y' border='1' align='top'/>
     </a>
    </td><td style='text-align: center'>
     <a href='?MM_ACTION=graficoHorario&height=480&width=640&month=$m&anio=$y'>
      <img src='?MM_ACTION=graficoHorario&height=240&width=320&month=$m&anio=$y' border='1' align='top'/>
     </a>
    </td></tr>
    <tr><td colspan='2'>&nbsp;</td></tr>
    <tr>
	 <td colspan='2' style='text-align: right'>Mes:
      <select name='month' style='width: 220px'>";
   foreach (monthArray() as $key => $name ){
    $selected = ($key == $m) ? "selected" : "";
    $html .= "<option value='$key' $selected>$name</option>";
   }
   $html .="</select>
     </td>
    </tr><tr>
     <td colspan='2' style='text-align: right'>A&ntilde;o:
      <select name='anio' style='width: 220px'>";
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
    </form>";
  }
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
  $rs = mysql_query(sprintf("SELECT logo_archivo, logo_tipo FROM rifas WHERE id=%s;", GetSQLValueString($this->idRifa,"int")), $conexion) or die(register_mysql_error("RI001", mysql_error()));
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
  
  $sql   = sprintf("SELECT DayOfMonth(fecha) AS descripcion, COUNT(*) AS cantidad, '%s' AS color FROM rifas_participantes WHERE estado=1 AND idRifa=%s AND fecha>=%s AND fecha<%s GROUP BY DayOfMonth(fecha)", $_CONF['bargraph-color'], $this->idRifa, GetSQLValueString($this->desde, "date"), GetSQLValueString($this->hasta, "date"));
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
  foreach ( $datax as $key => $index ){
   $dataxR[sizeof($dataxR)] = $index; 
  }

  foreach ( $datay as $key => $index ){
   $datayR[sizeof($datayR)] = $index;
  }

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
  $bplot->value->SetFont(FF_VERDANA,FS_NORMAL,$font);

  $bplot->SetValuePos('top');
  $bplot->SetWidth(0.7);

  $bplot->SetFillColor( $colors );
  $bplot->SetColor("white");

  $graph->Add($bplot);
  $graph->Stroke();
 }
 function printGraficoHorario       (){
  global $conexion, $_CONF;

  $colors = array();
  $datax = array(); $datay = array();

  $i = 0;
  $num_rifas_h = 0;
  $title       = "";

  $this->desde  = (isset($_GET['month']) && isset($_GET['anio'])) ? $_GET['anio'] . "-" . $_GET['month'] . "-01" : date("Y-m-01");
  $this->hasta  = (isset($_GET['month']) && isset($_GET['anio'])) ? date('Y-m-d 23:59:59', strtotime('-1 second', strtotime('+1 month', strtotime($_GET['month'] . '/01/' . $_GET['anio'] .' 00:00:00')))) : date('Y-m-d 23:59:59', strtotime('-1 second', strtotime('+1 month', strtotime(date('m') . '/01/' . date('Y') .' 00:00:00'))));

  $sql   = sprintf( "SELECT a.id, a.nombre AS descripcion, SUM(CASE WHEN (dia_lunes=1 AND dia=1) OR (dia_martes=1 AND dia=2) OR (dia_miercoles=1 AND dia=3) OR (dia_jueves=1 AND dia=4) OR (dia_viernes=1 AND dia=5) OR (dia_sabado=1 AND dia=6) OR
(dia_domingo=1 AND dia=0) THEN b.conteo ELSE 0 END) AS cantidad, '#%s' AS color FROM rifas_horario a LEFT OUTER JOIN (SELECT DATE_FORMAT(fecha,'%%w') as dia, DATE_FORMAT(fecha,'%%k') AS hora,COUNT(*) AS conteo FROM rifas_participantes WHERE estado=1 AND idRifa=%s AND fecha>=%s AND fecha<%s GROUP BY DATE_FORMAT(fecha,'%%w'), DATE_FORMAT(fecha,'%%k')) b ON a.hora_inicio <= b.hora AND IF(a.hora_final=0,24,a.hora_final) > b.hora WHERE idRifa=%s GROUP BY a.id, a.nombre ORDER BY a.nombre;", $_CONF['bargraph-color'], $this->idRifa, GetSQLValueString($this->desde, "date"), GetSQLValueString($this->hasta, "date"), $this->idRifa); 
  $result = mysql_query($sql, $conexion) or die();
  while ($row = mysql_fetch_assoc($result)){
   $datax[$i]   = $row['descripcion'];
   $datay[$i]   = $row['cantidad'];
   $colors[$i]  = $row['color'];
   $num_rifas_h = $num_rifas_h  + $row['cantidad'];
   ++$i;
  }
 
  $sql = sprintf("SELECT COUNT(*) AS conteo, '#%s' AS color FROM rifas_participantes WHERE estado=1 AND idRifa=%s AND fecha>=%s AND fecha<%s", $_CONF['bargraph-color'], $this->idRifa, GetSQLValueString($this->desde, "date"), GetSQLValueString($this->hasta, "date"));
  $result = mysql_query($sql, $conexion) or die();
  $row = mysql_fetch_assoc($result);
  if ($row['conteo'] > 0 && $row['conteo'] > $num_rifas_h){
   $datax [$i] = 'Otros';
   $datay [$i] = $row['conteo'] - $num_rifas_h;
   $colors[$i] = $row['color'];
   ++$i;
  }

  if ( $i == 0){
   $datax [$i] = "";
   $datay [$i] = 0;
  }

  $dataxR=array(); $datayR=array();
  foreach ( $datax as $key => $index ){
   $dataxR[sizeof($dataxR)] = $index; 
  }
  
  foreach ( $datay as $key => $index ){
   $datayR[sizeof($datayR)] = $index;
  }

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
  $mes  = $_GET['mes'];
  $anio = $_GET['anio'];

  $mes2  = $mes % 12 + 1;
  $anio2 = $mes < 12 ? $anio : $anio + 1;     

  $sql = sprintf("SELECT DAY(fecha) AS fecha, COUNT(*) AS conteo FROM rifas_participantes WHERE estado=1 AND fecha>='$anio-$mes-01' AND fecha < '$anio2-$mes2-01' AND idRifa=%s GROUP BY DAY(fecha) ORDER BY DAY(fecha)", $this->idRifa); 
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
  
  $sql = sprintf("SELECT p.contestado, COUNT(*) AS conteo FROM rifas_participantes p LEFT OUTER JOIN rifas_respuestas r ON p.id=r.idParticipante WHERE p.id=%s;", GetSQLValueString($_GET['id'], "int"));
  $rs  = mysql_query($sql, $conexion) or die(register_mysql_error("EL001", mysql_error()));
  $row = mysql_fetch_assoc($rs);
	 
  if (isset($_GET['contestado'])){
   $response  = "<tr><td style='text-align: center'><br/>La respuesta ha sido enviada</td></tr>";
   $response .= "<script type='text/javascript'>
     setTimeout('delayedClose()',2000);
    </script>";
  } else if ($row['contestado'] == 1 && $row['conteo'] >= 3) {
   $response = sprintf("<tr><td style='text-align: center'><br/>El mensaje ya ha sido respondido</td></tr>");
  } else {
   $response = sprintf("<tr><td><textarea class='textbox-large' rows='13' cols='41' name='mensaje' onkeyup='maxsize(this)'></textarea></td></tr>
    <tr><td scope='row'><div align='right'>
     <input type='button' class='button' onclick='javascript: if (validateTextArea(this.form.mensaje)){ this.form.submit(); }' name='Submit' value='Enviar'>
     <input type='hidden' name='id' value='%s' />
     <input type='hidden' name='MM_ACTION' value='insertarRespuesta' />
    </div></td></tr>", $_GET['id']);
  }
  $html = file_get_contents($this->templateResponse);
  $html = str_replace("@@CONTENIDO@@", $response, $html);
  echo $html;
 }
 function printSorteo               (){
  $html = file_get_contents($this->templateSorteo);
  if (!isset($_GET['detalle'])){
   $numero = $this->insertarGanador();
   $link = sprintf("<tr><td colsapn='4'><a href='?MM_ACTION=sorteo&detalle'>Detalle de Ganadores</a></td></tr>");
   $html = str_replace("@@NUMERO@@", $numero, $html);
   $html = str_replace("@@GANADORES@@", $link, $html);
   $html = str_replace("@@DETALLE@@" , "", $html);
  } else {
   $html = str_replace("@@NUMERO@@", "", $html);
   $html = str_replace("@@GANADORES@@", $this->printGanadores(), $html);
   $html = str_replace("@@DETALLE@@" , $this->printDetalleGanadores(), $html);
  }
  echo $html;
 }
 function printManejarHorario      (){
  global $_CONF;
  global $conexion;
  
  if(isset($_GET["id"])){ 
   $sql = sprintf("SELECT id,nombre, hora_inicio, hora_final, dia_lunes AS dia_l, dia_martes AS dia_ma, dia_miercoles AS dia_mi, dia_jueves AS dia_j, dia_viernes AS dia_v, dia_sabado AS dia_s, dia_domingo AS dia_d FROM rifas_horario WHERE id=%s;", GetSQLValueString($_GET["id"], "int"));
   $res_fichash = mysql_query($sql, $conexion); 
   $row_fichash = mysql_fetch_array($res_fichash);
  }
  
  $sql = sprintf("SELECT id, nombre, hora_inicio, hora_final, dia_lunes, dia_martes, dia_miercoles, dia_jueves, dia_viernes, dia_sabado, dia_domingo FROM rifas_horario WHERE idRifa=%s ORDER BY hora_inicio;", $this->idRifa);
  $res_rifas = mysql_query($sql, $conexion);
  $detalle = "";
  while( $row_rifas = mysql_fetch_array($res_rifas) ){ 
   $detalle .= sprintf("<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td>
    <td rowspan='1' style='text-align: center; vertical-align: middle'><input name='eliminar[]' type='checkbox' value='%s' enabled></td>
	<td rowspan='1' style='text-align: center; vertical-align: middle'><a href='?MM_ACTION=manejarHorario&id=%s'>Modificar</a></td>
   </tr>", $row_rifas['nombre'], abreviaturaDias($row_rifas), formatearHora($row_rifas['hora_inicio']), formatearHora($row_rifas['hora_final']), $row_rifas['id'], $row_rifas['id']);
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
 function printSound               (){
  $html = "";
  if ( $this->enableChat && $this->hasNewMessages() && $this->playSounds){
      $html = "<bgsound src='snd/alarm1.wav' loop='1' delay='10'>";
  }
  return $html;
 }

 function hasNewMessages           (){
  if (!isset($_SESSION['lastID'])){
   $retVal = false;
  } else if ($_SESSION['lastID'] >= $this->lastID){
   $retVal = false;
  } else {
   $retVal = true;
  }
  $_SESSION['lastID'] = $this->lastID;
  return $retVal;
 }
 
 function printDetail              (){
  global $_CONF;
  global $conexion;

  /* Datos Generales */
  $SQL      = sprintf( "SELECT chat, mensaje_participante_a, mensaje_participante_b, selfservice, sonido, autoRefresh FROM rifas WHERE id=%s;", GetSQLValueString($this->idRifa, "int"));
  $rsp      = mysql_query($SQL, $conexion) or die(register_mysql_error("RDG001", mysql_error()));
  $row      = mysql_fetch_array( $rsp );            

  $this->mensaje_a     = $row['mensaje_participante_a'];
  $this->mensaje_b     = $row['mensaje_participante_b'];
  $this->enableChat    = $row['chat'];
  $this->playSounds    = $row['sonido'];
  $this->selfService   = ($row['selfservice'] == 1);
  $this->autoRefresh   = ($row['autoRefresh'] == 1);
  
  if (isset($_CONF['skin']) && $_CONF['skin'] != "") $this->templateHTML = "skins/" . $_CONF['skin'] . "/rifas.html";
  $html = file_get_contents($this->templateHTML);
  $html = str_replace("@@TITLE@@", $this->title,$html);
  $html = str_replace("@@WAV@@",   $this->printSound(),$html);
  if ($this->autoRefresh){   
   $html = str_replace("@@REFRESH@@", "<meta http-equiv=\"Refresh\" content=\"60;@@URL@@\" />", $html);
  } else {
   $html = str_replace("@@REFRESH@@", "", $html);
  }
  $html = str_replace("@@URL@@", sprintf("%s?%s", $this->url, htmlentities(implode("&", $this->params))), $html);
  $html = str_replace("@@RESPUESTA@@", $this->printMensajeRespuesta(),$html);
  $html = str_replace("@@PARTICIPANTES@@",$this->printParticipantes(),$html);
  $html = str_replace("@@CONTEOPARTICIPANTES@@",$this->printConteoParticipantes(),$html);
  $html = str_replace("@@HORARIOS@@",$this->printManejarHorarios(),$html);
  $html = str_replace("@@GANADORES@@",$this->printGanadores(),$html);
  $html = str_replace("@@MAYORESPARTICIPANTES@@",$this->printMayoresParticipantes(),$html);
  $html = str_replace("@@REPORTEMENSUAL@@",$this->printReporteMensual(),$html);
  $html = str_replace("@@GRAFICOS@@",$this->printGraficos(),$html);
  $html = str_replace("@@DESDE@@",$this->desde,$html);
  $html = str_replace("@@HASTA@@",$this->hasta,$html);
  $html = str_replace("@@NUMERO@@",$this->numero,$html);
  $html = str_replace("@@MENSAJE@@",$this->mensaje,$html);
  if (isset($_CONF['skin']) && $_CONF['skin'] != ""){
      $html = str_replace("@@SKIN@@", $_CONF['skin'], $html);
  }

  echo $html;
 }
}