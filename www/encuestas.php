<?php

require_once('conf.php'); 
require_once('connections/conexion.php'); 
require_once('functions/functions.php');
require_once('functions/db.php');
require_once('functions/date.php');

class encuesta {	
 private $templateLogin    = "";
 private $templateHTML     = "encuestas/html.html";
 
 private $title      = "Encuestas";
 private $table      = "encuestas";
 private $initPage   = "encuestas.php";
 private $sessionVar = "idEncuesta";
 private $url        = "";
 private $pagina     = 0; 
 private $limit      = 25;
 private $desde;
 private $hasta;
 
 private $idEncuesta;
 private $cntParticipantes;

 function encuesta(){
  session_start();
  if (!isset($_SESSION[$this->sessionVar])) if (!$this->doLogin()) $this->printLogin();
  $this->constructor();


  $action = isset($_POST['MM_ACTION']) ? $_POST['MM_ACTION'] : (isset($_GET['MM_ACTION']) ? $_GET['MM_ACTION'] : "");
  switch ($action){
   case "eliminarParticipantes":
    $this->eliminarParticipantes();
    break;
   case "reiniciarConteos":
    $this->reiniciarConteos();
    break;
   case "actualizarDatos":
    $this->actualizarDatos();
    break;
   case "cancelarGanadores":
    $this->cancelarGanadores();
    break;
   case "agregarGanador":
    $this->cancelarGanadores();
    break;
   case "imagen":
    $this->printImagen();
	break;
   case "logout":
    $this->doLogout();
    $this->printLogin();
    break;
   default:
    $this->printDetail();
	break;
  }
 }
 function constructor(){
  global $conexion;
  global $database_conexion;
  
  $this->idEncuesta = $_SESSION[$this->sessionVar];
  $this->pagina = ( isset($_GET['pagina'])) ? $_GET['pagina'] : 0;
  mysql_select_db($database_conexion, $conexion);

  $query_cnt = sprintf("SELECT DATE_FORMAT(MIN(fecha), '%%Y-%%m-%%d %%H:00') AS desde FROM encuestas_participantes WHERE estado=1 AND idEncuesta=%s;", GetSQLValueString($this->idEncuesta, "int"));
  $rs        = mysql_query( $query_cnt, $conexion ) or die(register_mysql_error("EE001", mysql_error()));
  $row       = mysql_fetch_array($rs);
  $this->desde     = $row['desde'];

  $this->desde     = (isset($_POST['desde' ])) ? $_POST['desde' ] : (isset($_GET['desde' ]) ? $_GET['desde' ] : (($this->desde) ? $this->desde : strftime( "%Y-%m-%d %H:00", time() - (strftime("%H", time()) * 60 * 60 )))); 
  $this->hasta     = (isset($_POST['hasta' ])) ? $_POST['hasta' ] : (isset($_GET['hasta' ]) ? $_GET['hasta' ] : strftime( "%Y-%m-%d %H:59", time() + 3600));
  $this->numero    = (isset($_POST['numero'])) ? $_POST['numero'] : (isset($_GET['numero']) ? $_GET['numero'] : "");
  $this->url       = $_SERVER['PHP_SELF'];
 }

 function doLogOut(){
  unset($_SESSION['su']);
  unset($_SESSION['idEncuesta']);
  session_destroy();
 }
 function doLogin (){
  global $conexion;
  global $database_conexion;
  
  $this->doLogout();
  session_start();	 
  
  if (!isset($_POST['username']) || !isset($_POST['password'])) return false;
  $usuario = (get_magic_quotes_gpc()) ? $_POST['username'] : addslashes($_POST['username']);
  $clave = (get_magic_quotes_gpc()) ? $_POST['password'] : addslashes($_POST['password']);
  
  mysql_select_db($database_conexion, $conexion);
  $sql = sprintf("SELECT id FROM %s WHERE estado=1 AND usuario='%s' AND clave='%s'", $this->table, $usuario, $clave);
  $rs  = mysql_query($sql, $conexion) or die(register_mysql_error("EL001", mysql_error()));
  $row = mysql_fetch_assoc($rs);

  if ( mysql_num_rows($rs) == 0 ){
   mysql_free_result($rs);
   $SQL = sprintf("SELECT id FROM %s WHERE estado=1 AND usuario='%s' AND claveAdmin='%s'", $this->table, $usuario, $clave);
   $rs = mysql_query($SQL, $conexion) or die(register_mysql_error("EL002", mysql_error()));
   $row = mysql_fetch_assoc($rs);
   
   if ( mysql_num_rows($rs) == 0 ) return false;
   $_SESSION['su'] = 1;
  }
  $_SESSION[$this->sessionVar] = $row['id'];
  $_SESSION['username'] = $_POST['username'];
  header("Location: " . $this->initPage);
  exit();
 }
 
 function eliminarParticipantes    (){ /* Eliminar a todos los participantes   */ 
  global $conexion;
  
  if ((isset($_POST["MM_ACTION"])) && ($_POST["MM_ACTION"] == "eliminarParticipantes") && (isset($_SESSION['su']))){
   $SQL = array();
   $SQL[0] = sprintf("UPDATE encuestas_participantes SET estado=0, fecha_reinicio=NOW() WHERE estado=1 AND idEncuesta=%s;", GetSQLValueString($this->idEncuesta, "int"));
   $SQL[sizeof($SQL)] = sprintf("INSERT INTO bitacora.encuestas_opciones SELECT * FROM encuestas_opciones WHERE idEncuesta=%s;", GetSQLValueString($this->idEncuesta, "int"));
   $SQL[sizeof($SQL)] = sprintf("UPDATE encuestas_opciones SET cantidad=0, fec_act=NOW(), usr_act=%s WHERE idEncuesta=%s;", GetSQLValueString($_SESSION['username'], "text"), GetSQLValueString($this->idEncuesta, "int"));
   foreach ($SQL as $key => $value){
    mysql_query($value, $conexion) or die(register_mysql_error("EEP00" . $key, mysql_error()));
   }
   header("Location: " . $this->initPage);
   exit();
  }
 }
 function reiniciarConteos         (){ /* Reiniciar conteos                    */                   
  global $conexion;
  
  if ((isset($_POST["MM_ACTION"])) && ($_POST["MM_ACTION"] == "reiniciarConteos") && isset( $_SESSION['su'] )){   
   $SQL = array();
   
   $SQL[sizeof($SQL)] = sprintf("INSERT INTO bitacora.encuestas_opciones SELECT * FROM encuestas_opciones WHERE idEncuesta=%s;", GetSQLValueString($this->idEncuesta, "int"));
   $SQL[sizeof($SQL)] = sprintf("UPDATE encuestas_opciones SET cantidad=0, fec_act=NOW() WHERE idEncuesta=%s", GetSQLValueString($this->idEncuesta, "int"));
   foreach ($SQL as $key => $value){
    mysql_query($value, $conexion) or die(register_mysql_error("ERC00" . $key, mysql_error()));
   }
   header("Location: " . $this->initPage);
   exit();
  }
 }
 function actualizarDatos          (){ /* Actualizar los datos de la encuesta: */             
  global $conexion;
  
  if ((isset($_POST["MM_ACTION"])) && ($_POST["MM_ACTION"] == "actualizarDatos")){
   $SQL = array();

   $ids      = $_POST['ids'];
   $edescrip = $_POST['edescrip'];
   $eclave   = $_POST['eclave'];
   $preg     = $_POST['pregunta'];
   $colors   = $_POST['colors'];

   //$SQL[sizeof($SQL)] = sprintf("BEGIN;");
   $SQL[] = sprintf("INSERT INTO bitacora.encuestas SELECT * FROM encuestas WHERE id=%s;", GetSQLValueString($this->idEncuesta, "int"));
   $SQL[] = sprintf("UPDATE encuestas SET pregunta=%s, usr_act=%s, fec_act=NOW() WHERE id=%s", GetSQLValueString($preg, "text"), GetSQLValueString($_SESSION['username'], "text"), GetSQLValueString($this->idEncuesta, "int"));
   $SQL[] = sprintf("INSERT INTO bitacora.encuestas_opciones SELECT * FROM encuestas_opciones WHERE idEncuesta=%s;", GetSQLValueString($this->idEncuesta, "int"));
   
   for( $i = 0; $i < count( $ids ); ++$i ){
    $c  = $colors[$i];
    $d  = $edescrip[$i];
    $cl = trim($eclave[$i]);
    if( $c[0] == '#' ) $c = substr( $c, 1 );
    if ( $cl != '' && $d != '')
     $SQL[sizeof($SQL)] = sprintf("UPDATE encuestas_opciones SET color=%s, descripcion=%s, clave=%s, usr_act=%s, fec_act=NOW() WHERE id=%s", GetSQLValueString($c, "text"), GetSQLValueString($d, "text"), GetSQLValueString($cl, "text"), GetSQLValueString($_SESSION['username'], "text"), GetSQLValueString($ids[$i], "int"));
   }
   $SQL[sizeof($SQL)] = sprintf("SELECT COUNT(*) AS conteo FROM (SELECT o1.id FROM encuestas e1, encuestas e2, encuestas_opciones o1, encuestas_opciones o2 WHERE e1.id=o1.idEncuesta AND e2.id=o2.idEncuesta AND o1.id<>o2.id AND INSTR(o1.clave,o2.clave)=1 AND (o1.idencuesta=o2.idencuesta OR e1.numero=e2.numero) AND e1.id=%s) dt;", GetSQLValueString($this->idEncuesta, "int"));
   
   foreach ($SQL as $key => $value){
    $rs = mysql_query($value, $conexion) or die(register_mysql_error("EAD00" . $key, mysql_error()));
   }
   
   $rs = mysql_fetch_array($rs);
   if ($rs['conteo'] == 0){
    mysql_query("COMMIT;", $conexion) or die(register_mysql_error("EAD00" . (sizeof($SQL)+1), mysql_error()));
   } else {
    mysql_query("ROLLBACK;", $conexion) or die(register_mysql_error("EAD00" . (sizeof($SQL)+1), mysql_error()));
   }
   
   header("Location: " . $this->initPage);
   exit();
  }
 }
 function cancelarGanadores        (){ /* Cancelar a todos los ganadores:      */ 
  global $conexion;
  
  if ((isset($_POST["MM_ACTION"])) && ($_POST["MM_ACTION"] == "cancelarGanadores")) {
   $SQL = array();
   
   $SQL[sizeof($SQL)] = sprintf( "UPDATE encuestas_participantes SET seleccionado=0 WHERE idEncuesta=%s", GetSQLValueString($this->idEncuesta, "int"));
   foreach ($SQL as $key => $value){
    $rs = mysql_query($value, $conexion) or die(register_mysql_error("ECG00" . $key, mysql_error()));
   }
  }
   
  header("Location: " . $this->initPage);
  exit();
 }
 function agregarGanador       (){ /* Agrear un nuevo ganador:             */ 
  global $conexion;
  
  if ((isset($_POST["MM_ACTION"])) && ($_POST["MM_ACTION"] == "agregarGanador")) {
   $SQL = array();

   $SQL[sizeof($SQL)] = sprintf("UPDATE encuestas_participantes SET seleccionado=1 WHERE idEncuesta=%s AND estado=1 AND seleccionado=0 AND fecha>=%s AND fecha<=%s ORDER BY RAND() LIMIT 1;", GetSQLValueString($this->idEncuesta, "int"), GetSQLValueString($this->desde, "date"), GetSQLValueString($this->hasta, "date"));
   foreach ($SQL as $key => $value){
    $rs = mysql_query($value, $conexion) or die(register_mysql_error("EAG00" . $key, mysql_error()));
   }
  }
   
  header("Location: " . $this->initPage);
  exit();
 }
 
 function printImagen              (){
  global $conexion;
  $rs = mysql_query(sprintf("SELECT logo_archivo, logo_tipo FROM encuestas WHERE id=%s;", GetSQLValueString($this->idEncuesta,"int")), $conexion) or die(register_mysql_error("SI001", mysql_error()));
  $row    = mysql_fetch_assoc($rs);
  $data   = $row[ "logo_archivo" ];
  $type   = $row[ "logo_tipo" ];
  header( "Content-type: $type");
  echo $data;
 }
 function printLogin               (){
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
 function printConteoParticipantes (){
  $html = "";
  if ( isset( $_SESSION['su'] )){
   $html = "<tr>
    <td>Cantidad de Participantes:</td>
    <td colspan='4'>$this->cntParticipantes</td>
   </tr>";
  }
  return $html;  
 }
 function printOpciones            (){
  global $conexion;
  $sql1 = sprintf( "SELECT descripcion, cantidad, id, clave, color FROM encuestas_opciones WHERE idEncuesta=%s ORDER BY id", GetSQLValueString($this->idEncuesta, "int"));
  $sql2 = sprintf( "SELECT IFNULL(SUM(cantidad),0) AS cantidad FROM (%s) dt;", $sql1);
  $rsO = mysql_query($sql2, $conexion) or die(register_mysql_error("EPO001", mysql_error()));
  $row = mysql_fetch_array( $rsO );
  
  $cntParticipantes = $row['cantidad'] + 0.001;

  $rsO = mysql_query($sql1, $conexion) or die(register_mysql_error("EPO002", mysql_error()));

  $html = "";
  while ( $row = mysql_fetch_array( $rsO )){
   $html .= "<tr>
    <td>
     <input type='hidden' name='ids[]' value=" . $row['id'] . " />
     <input class='textbox' id='edescrip[]' name='edescrip[]' type='text' value='". $row[ 'descripcion' ] . "' style='width: 200px' />
    </td><td>
     <input class='textbox' id='eclave[]' name='eclave[]' type='text' value='" . $row[ 'clave' ] . "' style='width: 40px' />
    </td><td class='centered'>
     <input type='hidden' id='colorsgraph" . $row['id'] . "' name='colors[]' value='" . $row['color'] . "'>
     <span style='cursor: hand; background:#" . $row['color'] . "' id='palleteviewer" . $row['id'] . "' name='palleteviewer" . $row['id'] . "' onClick=\"javascript: cpicker('" . $row['id'] . "'); return false;\">
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
     </span>
    </td>";
   if ( isset( $_SESSION['su'] )){
    $html .= sprintf("<td class='centered'>%s</td><td class='centered'>%s</td>", $row[ 'cantidad' ], number_format($row[ 'cantidad' ] / $cntParticipantes * 100, 2 ) . "%");
   } else {   
    $html .= sprintf("<td class='centered'>%s</td><td class='centered'>%s</td>", "&nbsp;", "&nbsp;");
   }  
   $html .= "</tr>";
  }
  return $html;
 }
 function printReporteMensual      (){
  $html = "";	 
  if ( !isset( $_SESSION['su'] )) return $html;
  
  $g = getdate(); 
  $html = "
   <tr><th>Reporte Mensual</th></tr>
   <tr><td>&nbsp;</td></tr>
   <tr>
    <td style='text-align: center'>
     <table border='0' cellspacing='0' cellpadding='0' align='center' width='400px'>
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
   for( $i = $g['year'] + 1; $i > 2004; --$i){
    $selected = ($i == $g['year']) ? "selected" : "";
    $html .= "<option value='$i' $selected >$i</option>";
   } 
   $html .="</select>
      </td>
     </tr>
     <tr><td colspan='2'>&nbsp;</td></tr>
     <tr><td colspan='2' style='text-align: right'><input class='button' type='button' onClick='this.form.submit()' value='Mostrar' /></td></tr>
     <tr><td colspan='2' >&nbsp;</td></tr>
    </form>";
   if ( isset( $_POST['month'] ) && isset( $_POST['anio'] )){
    $m = $_POST['month'];
    $y = $_POST['anio'];
    $html .= "<tr><td colspan='2'><a href='encuestas/encuestas.grafico.mensual.php?mes=$m&anio=$y&width=900&height=400'><img src='encuestas/encuestas.grafico.mensual.php?mes=$m&anio=$y&width=385&height=200' border='0' /></a></td></tr>";
   }
   $html .= "</table>
     </td>
    </tr>";
  return $html;
 }
 function printGanadores           (){
  global $conexion;
  
  $SQL       = sprintf( "SELECT numero FROM encuestas_participantes WHERE idEncuesta=%s AND seleccionado=1", GetSQLValueString($this->idEncuesta, "int"));
  $rsNumeros = mysql_query($SQL, $conexion) or die(register_mysql_error("EPG001", mysql_error())); 
  
  $html = "";
  while( $row_rsNumeros = mysql_fetch_assoc($rsNumeros) ){ 
   $html .= "<tr><td style='text-align: center'><font size=+1>" . $row_rsNumeros['numero'] . "</font></td></tr>";
  } 
  return $html;
 }
 function printParticipantes       (){
  global $conexion;

  $condition = ($this->numero == "" ) ? "" : sprintf("AND p.numero LIKE '%%%s%%'", GetSQLValueString($this->numero,"int") );
  $SQL = sprintf( "SELECT p.numero, p.fecha, p.mensaje FROM encuestas e, encuestas_participantes p WHERE e.id=p.idEncuesta AND p.estado=1 AND p.idEncuesta=%s AND p.fecha >=%s AND p.fecha <=%s %s ORDER BY p.id DESC", GetSQLValueString($this->idEncuesta, "int"), GetSQLValueString($this->desde, "date"), GetSQLValueString($this->hasta, "date"), $condition);
  $rs  = mysql_query( $SQL, $conexion ) or die(register_mysql_error("EE011", mysql_error()));
  $this->cntParticipantes = mysql_num_rows($rs);

  $html = "";
  if ( !isset( $_SESSION['su'] )) return $html;
  $html .= "";
  $i = 0;
  while ( $row = mysql_fetch_array( $rs )){ 
   if ($i < (($this->pagina+1) * $this->limit) && $i >= ($this->pagina * $this->limit -1)){
    $html .= "<tr>
     <td>" . ($i + 1 + $this->pagina) . "</td>
     <td>" . $row['numero'] . "</td>
     <td>" . $row['mensaje'] . "</td>
     <td>" . $row['fecha'] . "</td>
    </tr>";
   }
   ++$i;
  }
  $anterior  = (!$this->pagina == 0) ? "<a href='$this->url?pagina=" . ($this->pagina - 1) . "'>&laquo; Anterior</a>" : "";
  $siguiente = "<a href='$this->url?pagina=" . ($this->pagina + 1) . "'>Siguiente &raquo;</a>";

  $html .= "<tr><td colspan='4'>&nbsp;</td></tr>";
  $html .= sprintf("<tr><th colspan='4' style='text-align: right'>%s <a>-</a> %s</th></tr>", $anterior, $siguiente);
  return $html;
 }
 function printDetail              (){
  global $_CONF;
  global $conexion;

  /* Datos Generales */
  $SQL      = sprintf( "SELECT pregunta FROM encuestas WHERE id=%s", GetSQLValueString($this->idEncuesta, "int"));
  $rsp      = mysql_query($SQL, $conexion) or die(register_mysql_error("EE013", mysql_error()));
  $row      = mysql_fetch_array( $rsp );          
  $pregunta = $row[0];

  if (isset($_CONF['skin']) && $_CONF['skin'] != "") $this->templateHTML = "skins/" . $_CONF['skin'] . "/encuestas.html";
  $html = file_get_contents($this->templateHTML);
  $html = str_replace("@@TITLE@@",$this->title,$html);
  $html = str_replace("@@URL@@","60;$this->url",$html);
  $html = str_replace("@@PREGUNTA@@",$pregunta,$html);
  $html = str_replace("@@PARTICIPANTES@@",$this->printParticipantes(),$html);
  $html = str_replace("@@CONTEOPARTICIPANTES@@",$this->printConteoParticipantes(),$html);
  $html = str_replace("@@OPCIONES@@",$this->printOpciones(),$html);
  $html = str_replace("@@REPORTEMENSUAL@@",$this->printReporteMensual(),$html);
  $html = str_replace("@@GANADORES@@",$this->printGanadores(),$html);
  $html = str_replace("@@DESDE@@",$this->desde,$html);
  $html = str_replace("@@HASTA@@",$this->hasta,$html);
  $html = str_replace("@@NUMERO@@",$this->numero,$html);
  if (isset($_CONF['skin']) && $_CONF['skin'] != "") $html = str_replace("@@SKIN@@", $_CONF['skin'], $html);

  echo $html;
 }
}

$e = new encuesta();


?>
