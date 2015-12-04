<?php
define('PINNOTAVAILABLE', "Pin no disponible");
define('PINNOTAVAILABLE', "Numero no valido");

class envioPines {
 private $table        = "pines";
 private $sessionVar   = "idPin"; 
 private $templateHTML = "pines.html";
    
 function envioPines(){
  session_start();
  if (!isset($_SESSION[$this->sessionVar])) {
   if (!$this->doLogin()) {
    $this->printLogin();
   }
  }
  $this->constructor();
 }
 
 function action(){
  $action = filter_input(INPUT_POST, 'MM_ACTION', FILTER_SANITIZE_STRING);
  switch ($action){
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
  
  $this->idEnvioPin = $_SESSION[$this->sessionVar];
  $this->pagina = filter_input(INPUT_POST, 'pagina', FILTER_SANITIZE_NUMBER_INT);
  mysql_select_db($database_conexion, $conexion);

  $this->url       = filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_STRING);
  $params = explode("&", filter_input(INPUT_SERVER, 'QUERY_STRING', FILTER_SANITIZE_STRING));
  foreach ($params as $param) {
   array_push($this->params, $param);
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
  
  $usuario = GetPost('username');
  $clave = GetPost('password');
  if ($usuario == "" || $clave == "") { return false; }
  
  mysql_select_db($database_conexion, $conexion);
  $sql = sprintf("SELECT id FROM %s WHERE estado=1 AND usuario='%s' AND claveAdmin='%s'", $this->table, $usuario, $clave);
  $rs  = mysql_query($sql, $conexion) or die(register_mysql_error("EL001", mysql_error()));

  if ( mysql_num_rows($rs) > 0 ){
   $_SESSION['su'] = 1;
  } else {
   $sql = sprintf("SELECT id FROM %s WHERE estado=1 AND usuario='%s' AND clave='%s'", $this->table, $usuario, $clave);
   $rs = mysql_query($sql, $conexion) or die(register_mysql_error("EL002", mysql_error()));
   if ( mysql_num_rows($rs) === 0 ){ return false; }
  }
  $row = mysql_fetch_assoc($rs);
  $_SESSION[$this->sessionVar] = $row['id'];
  $_SESSION['username'] = $usuario;
  header("Location: " . INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_STRING); exit();
 }
 
 function printDetail              (){
  global $_CONF;
  if (isset($_CONF['skin']) && $_CONF['skin'] != ""){ $this->templateHTML = "skins/" . $_CONF['skin'] . "/pines.html"; }
  $html = file_get_contents($this->templateHTML);
  $html = str_replace("@@TITLE@@", $this->title, $html);
  
  /*$html = str_replace("@@URL@@", sprintf("%s?%s", $this->url, htmlentities(implode("&", $this->params))), $html);
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
  $html = str_replace("@@MENSAJE@@",$this->mensaje,$html);*/
  echo $html;
 }
 
 function validateNumber($numero){
  global $_CONF;
  if (!is_numeric($numero) || strlen($numero) != $_CONF['num_length'] || substr($numero, 0, 3) != $_CONF['area_code']){
   return false;
  } else {
   return true;
  }
 }
 
 /* Funcionalidad Operativa */
 function getAvailablePin(){
  global $conexion, $database_conexion;
  mysql_select_db($database_conexion, $conexion);
  $sql = sprintf("SELECT pin FROM pines_pines WHERE usado=0 ORDER BY RAND()");
  $rs  = mysql_query($sql, $conexion) or die(register_mysql_error("EL001", mysql_error()));
  if ( mysql_num_rows($rs) == 0 ){
   return false;
  } else {
   $row = mysql_fetch_assoc($rs);
   return $row['pin'];
  }
 }
 function sendPin($numero){
  if (!$this->validateNumber($numero)){
   return NUMBERNOTVALID;
  }
  $pin = $this->getAvailablePin();
  if (!$pin){
   return PINNOTAVAILABLE;
  } else {
   $this->registerPin($pin, $numero);
   $this->processPins();
  }
 }
 function registerPin($pin, $numero){
  global $conexion;
  $sqlUpd = sprintf("UPDATE pines_pines SET usado=1 WHERE pin='%s'", $pin);
  mysql_query($sqlUpd, $conexion) or die(register_mysql_error("EL001", mysql_error()));
  $sqlIns = sprintf("INSERT INTO pines_envios (idPin, pin, numero, fecha) VALUES (%s, '%s', '%s', NOW())", $this->idEnvioPin, $pin, $numero);
  mysql_query($sqlIns, $conexion) or die(register_mysql_error("EL001", mysql_error()));
 }
 function processPins(){
  global $conexion;
  $sql = sprintf("SELECT id, pin, numero FROM pines_envios WHERE estado=0 AND idPin=%s", $this->idEnvioPin);
  $rs = mysql_query($sql, $conexion) or die(register_mysql_error("EL001", mysql_error()));
  while ($row = mysql_fetch_assoc($rs)){
   $sqlUpdStatus = sprintf("UPDATE pines_envios SET estado=1 WHERE id=%s", $row['id']);
   mysql_query($sqlUpdStatus, $conexion) or die(register_mysql_error("EL001", mysql_error()));
   $result = pinProxy($row['pin'], $row['numero']);
   $sqlUpdResult = sprintf("UPDATE pines_envios SET resultado='%s' WHERE id=%s", $result, $row['id']);
   mysql_query($sqlUpdResult, $conexion) or die(register_mysql_error("EL001", mysql_error()));
  }
 }
 function loadPins($pins){
  global $conexion;
  $pin_array = preg_split("/\\r\\n|\\r|\\n/", $pins);
  foreach($pin_array as $pin){
   $sql = sprintf("INSERT IGNORE INTO pines_pines (pin, fecha) VALUES ('%s', NOW())", $pin);
   mysql_query($sql, $conexion) or die(register_mysql_error("EL001", mysql_error()));
  }  
 }
}