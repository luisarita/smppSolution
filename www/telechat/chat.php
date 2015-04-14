<?php
require_once('../connections/conexion.php'); 
require_once('../functions/functions.php');
require_once('../functions/db.php');
require_once('../functions/date.php');

class chat {
 private $templateResponse = "respuesta.html";

 private $sessionVar = "idTelechat";
 
 function chat(){
  session_start();
  if (!isset($_SESSION[$this->sessionVar])) if (!$this->doLogin()) $this->printLogin();
  $this->constructor();
 }

 function constructor(){
  global $conexion;
  global $database_conexion;
  
  mysql_select_db($database_conexion, $conexion);
 }
 
 function action(){
  $action = isset($_POST['MM_ACTION']) ? $_POST['MM_ACTION'] : (isset($_GET['MM_ACTION']) ? $_GET['MM_ACTION'] : "");
  switch ($action){
   case "insertarRespuesta":
    $this->insertarRespuesta();
	break;	
   default:
    $this->printFormularioRespuesta();
	break;
  }
 }
 	
 function insertarRespuesta       (){ /* Insertar Respuesta de un mensaje recibido */ 
  global $conexion;

  if (isset($_POST['mensaje']) && isset($_POST['id'])){
   $SQL = array();

   $SQL[sizeof($SQL)] = sprintf("UPDATE telechats_mensajes SET contestado=1 WHERE id=%s;", GetSQLValueString($_POST['id'], "int"));   
   $SQL[sizeof($SQL)] = sprintf("INSERT INTO telechats_respuestas (idMensaje,mensaje,estado) VALUES (%s,%s,1);", GetSQLValueString($_POST['id'], "int"), GetSQLValueString($_POST['mensaje'], "text"));
   $SQL[sizeof($SQL)] = sprintf("INSERT INTO mensajes_pendientes (numero,numero_salida,mensaje,fecha_salida,prioridad) SELECT m.numero, t.numero, tr.mensaje, NOW(), 2 FROM telechats t, telechats_respuestas tr, telechats_mensajes m WHERE m.idTelechat=t.id AND tr.idMensaje=m.id AND tr.id=@@LASTID@@;");
   
   foreach ($SQL as $key => $value){
	$value = str_replace("@@LASTID@@", mysql_insert_id(), $value);
    $rs = mysql_query($value, $conexion) or die(register_mysql_error("TR00" . $key, mysql_error()));
   }
  }
  header(sprintf("Location: %s?MM_ACTION=respuestaMensaje&contestado&id=%s", $_SERVER['PHP_SELF'], GetSQLValueString($_POST['id'], "int")));
  exit();
 }
 function printFormularioRespuesta(){
  global $conexion;
  global $database_conexion;
  
  $sql = sprintf("SELECT m.contestado, COUNT(*) AS conteo FROM telechats_mensajes m LEFT OUTER JOIN telechats_respuestas r ON m.id=r.idMensaje WHERE m.id=%s AND m.idTelechat=%s;", GetSQLValueString($_GET['id'], "int"), $_SESSION[$this->sessionVar]);
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
     <input type='submit' class='button' name='Submit' value='Enviar'>
     <input type='hidden' name='id' value='%s' />
     <input type='hidden' name='MM_ACTION' value='insertarRespuesta' />
    </div></td></tr>", $_GET['id']);
  }
  $html = file_get_contents($this->templateResponse);
  $html = str_replace("@@CONTENIDO@@", $response, $html);
  echo $html;
 }
}

$c = new chat();
$c->action();

?>