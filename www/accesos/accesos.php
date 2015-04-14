<?php

class accesos {
 private $templateLogin = "";
 private $templateHTML = "accesos/html.html";
 private $templateHTMLNueva = "suscripcionesNueva.html";
 private $title = "Accesos";
 private $table = "accesos_usuarios";
 private $sessionVar = "idAcceso";
 private $agregarActividades = 1;
 private $url = "";
 private $usuario = "";

 function accesos() {
  session_start();
  if (!isset($_SESSION[$this->sessionVar])){
   if (!$this->doLogin()){
    $this->printLogin();
   }
   $this->constructor();
  }
 }
 
 function getAcceso(){
  //verficamos si se reciben los datos POST de la actividad a la que se desea acceder
  if (isset($_POST['actividad']) && $_POST['actividad'] == -1) {
   echo $this->printFrame("?MM_ACTION=nuevaSuscripcion");
  } else if (isset($_POST['actividad'])) {
   global $conexion;
   $data = explode("@", $_POST['actividad']);
   $idActividad   = $data[0];
   $tipoActividad = $data[1];

   /* Revisar si el usuario tiene permiso a la actividad, si no matar el script */
   $sql = sprintf("SELECT ap.idActividad, ap.tipoActividad, aa.pagina, aa.campo FROM accesos_permisos ap, accesos_actividades aa WHERE aa.id=ap.tipoActividad AND ap.estado=1 AND ap.idActividad=%s AND tipoActividad=%s AND idUsuario=%s;", GetSQLValueString($idActividad, "int"), GetSQLValueString($tipoActividad, "int"), GetSQLValueString($_SESSION['idAcceso'], "int"));
   $rs = mysql_query($sql, $conexion) or die(register_mysql_error("MU0004", mysql_error()));
   if (mysql_num_rows($rs) == 0){ die(register_mysql_error("MU0005", mysql_error())); }
   
   /* Recuperar el valor del campo pagina de la tabla accesos actividades y guardarelo en $pagina; el valor de campo en la variable $campo */
   $row = mysql_fetch_array($rs);
   $pagina = $row['pagina'];
   $campo  = $row['campo'];

   $_SESSION[$campo]            = $idActividad;
   $_SESSION['idTipoActividad'] = $tipoActividad;
   $_SESSION['username']        = $tipoActividad;
   $_SESSION['su']              = 1;
   
   echo $this->printFrame($pagina);
   exit;
  }
 }
 
 function printFrame($pagina){
  return "<html>
    <head><title>" . $this->title . "</title></head>
     <frameset rows='40,*'>
      <noframes>
       <body>Su visualizador no soporta frames.</body>
      </noframes>
      <frame name='accesos' src='/accesos.php?dummy=" . time() . "'>
      <frame name='actividad' src='" . $pagina . "'>
     </frameset>
    </html>";
 }

 function constructor() {
  global $conexion;
  global $database_conexion;

  mysql_select_db($database_conexion, $conexion);
  $this->url = $_SERVER['PHP_SELF'];
 }

 function action() {
  if (!isset($_SESSION)){ session_start(); }
  $action = isset($_POST['MM_ACTION']) ? $_POST['MM_ACTION'] : (isset($_GET['MM_ACTION']) ? $_GET['MM_ACTION'] : "");
  switch ($action) {
   case "logout":
    $this->doLogout();
    $this->printLogin();
    break;
   case "acceso":
    $this->getAcceso();
    break;
   case "nuevaSuscripcion":
    $this->printNuevaSuscripcion();
    break;
   case "nuevaSuscripcionAgregar":
    $this->nuevaSuscripcionAgregar();
    $this->printNuevaSuscripcion();
    break;
   default:
    $this->printDetail();
    break;
  }
 }

 function doLogin() {
  global $conexion, $database_conexion;

  $this->doLogout();
  session_start();

  if (!isset($_POST['username']) || !isset($_POST['password'])){
   return false;
  }
  $this->usuario = (get_magic_quotes_gpc()) ? $_POST['username'] : addslashes($_POST['username']);
  $clave = (get_magic_quotes_gpc()) ? $_POST['password'] : addslashes($_POST['password']);

  mysql_select_db($database_conexion, $conexion);
  $sql = sprintf("SELECT id FROM %s WHERE estado=1 AND usuario='%s' AND clave = MD5('%s');", $this->table, $this->usuario, $clave);
  $rs = mysql_query($sql, $conexion) or die(register_mysql_error("VI001", mysql_error()));
  $row = mysql_fetch_assoc($rs);

  if (mysql_num_rows($rs) == 0){
   return false;
  }
  $_SESSION[$this->sessionVar] = $row['id'];
  $_SESSION['username'] = $_POST['username'];
  header("Location: " . $_SERVER['PHP_SELF']);
  exit();
 }
 function doLogOut() {
  unset($_SESSION['su']);
  unset($_SESSION[$this->sessionVar]);
  session_destroy();
 }
 function printLogin() { // Imprimir pantalla de inicio de sesion
  global $_CONF;
  
  if ($this->templateLogin == "") { /* No se ha configurado que plantilla de inicio de sesion utilizar */
   $this->templateLogin = "templates/login.html";
   if (isset($_CONF['skin']) && $_CONF['skin'] != ""){ $this->templateLogin = "skins/" . $_CONF['skin'] . "/login.html"; }
  }

  $template = file_get_contents($this->templateLogin);
  $html = str_replace("@@TITLE@@", $this->title, $template);
  echo $html;
  exit();
 }

 function printDetail() {
  global $_CONF;

  if (isset($_CONF['skin']) && $_CONF['skin'] != ""){ $this->templateHTML = "skins/" . $_CONF['skin'] . "/accesos.html"; }
  $template = file_get_contents($this->templateHTML);
  $html = str_replace("@@TITLE@@", $this->title, $template);
  $html = str_replace("@@OPCIONES@@", $this->printOpciones(), $html);
  if (isset($_CONF['skin']) && $_CONF['skin'] != ""){ $html = str_replace("@@SKIN@@", $_CONF['skin'], $html); }
  echo $html;
 }

 function nuevaSuscripcionAgregar(){
  global $conexion, $_CONF;
  $usuario = generateRandomString(10);
  $clave = generateRandomString(10);

  //print_r($_FILES['imagen']); exit();
  if(isset($_FILES['imagen'])) {
   $imagen = file_get_contents($_FILES['imagen']['tmp_name']);
  } else {
   $imagen = "";
  }
  
  $SQL[] = sprintf("INSERT INTO suscripciones (nombre, numero, numeroAdicional, respuesta, respuestaAdicional, renovacionA, renovacionB, renovacionC, duracion, activa, usuario, clave, logo_tipo, numeroRecepcion, numeroUsuario, numeroSalida, respuestaCancelacion, rutaShell, priorizado, monitoreable, habilitar_media, aplicarHorario, nombreComercial, aplicarVariables, variable1,     variable2,     variable3,     variable4,     variable5,     aplicarLlenadoVariables, limiteMensajesDiarios, requiereAprobacion, correoAprobacion, claveServicio, logo_archivo)" .
                                     " VALUES (%s,     '0000', '0000',          '',        '',                 '',          '',          '',          3000,     1,      %s,      %s,    'jpeg',    '0000',          '50499999999', '0000',       '',                   '',        0,          1,            0,               1,              %s,              1,                IFNULL(%s,''), IFNULL(%s,''), IFNULL(%s,''), IFNULL(%s,''), IFNULL(%s,''), 1,                       5,                     1,                  %s,               %s,            %s);", 
          GetSQLValueString($_POST['nombre'], "text"), 
          GetSQLValueString($usuario, "text"), 
          GetSQLValueString($clave, "text"), 
          GetSQLValueString($_POST['nombreComercial'], "text"), 
          GetSQLValueString($_POST['variable1'], "text"), 
          GetSQLValueString($_POST['variable2'], "text"), 
          GetSQLValueString($_POST['variable3'], "text"), 
          GetSQLValueString($_POST['variable4'], "text"), 
          GetSQLValueString($_POST['variable5'], "text"), 
          GetSQLValueString($_CONF['correo_aprobaciones'], "text"), 
          GetSQLValueString($_POST['nombreComercial'], "text"),
          GetSQLValueString($imagen, "text"));
  $SQL[] = sprintf("INSERT INTO accesos_permisos (idUsuario, idActividad, tipoActividad, estado) SELECT %s, MAX(id), 1, 1 FROM suscripciones;",
          GetSQLValueString($_SESSION['idAcceso'], "int"));
  foreach ($SQL as $query){
   mysql_query($query, $conexion) or die(mysql_error());
  }
  header("Location: accesos.php?msg=suscripcionCreada");
 }
 function printNuevaSuscripcion(){
  global $_CONF;

  if (isset($_CONF['skin']) && $_CONF['skin'] != ""){ $this->templateHTMLNueva = "skins/" . $_CONF['skin'] . "/suscripcionesNueva.html"; }
  $template = file_get_contents($this->templateHTMLNueva);
  $html = str_replace("@@TITLE@@", $this->title, $template);
  //$html = str_replace("@@OPCIONES@@", $this->printOpciones(), $html);
  if (isset($_CONF['skin']) && $_CONF['skin'] != ""){ $html = str_replace("@@SKIN@@", $_CONF['skin'], $html); }
  echo $html;
 }
 //funciones menu usuarios
 function loginActividad() {
 }

 function printOpciones() { //funcion modificada
  global $conexion, $database_conexion;
  
  mysql_select_db($database_conexion, $conexion);
  $sql = sprintf("SELECT DISTINCT aa.id, aa.nombre, aa.tabla, aa.pagina FROM accesos_actividades aa, accesos_permisos ap WHERE estado=1 AND ap.tipoActividad=aa.id AND ap.idUsuario=%s ORDER BY aa.nombre;", GetSQLValueString($_SESSION['idAcceso'], "int"));
  $rs = mysql_query($sql, $conexion) or die(register_mysql_error("MU0001", mysql_error()));
  
  $html  = "<tr><td><form method='post' action='' target='actividad'>";
  $html .= "<input type='hidden' name='MM_ACTION' value='acceso'>";
  $html .= "<select name='actividad' onchange='this.form.submit()'>";
  $html .= "<option selected>Seleccione una actividad ...</option>";
  
  if (mysql_num_rows($rs) > 0) {
   while ($row = mysql_fetch_array($rs)) {
    $html .="<optgroup label='" . $row['nombre'] . "'>";
    $sql1 = sprintf("SELECT ap.tipoActividad, ap.idUsuario, ap.idActividad FROM accesos_permisos ap WHERE ap.estado=1 AND ap.idUsuario=%s AND ap.tipoActividad=%s;", GetSQLValueString($_SESSION['idAcceso'], "int"), GetSQLValueString($row[0], "int"));
    $rs1 = mysql_query($sql1, $conexion) or die(register_mysql_error("MU0002", mysql_error()));
    
    while ($row1 = mysql_fetch_array($rs1)) {
     $sql2 = sprintf("SELECT id, nombre FROM %s WHERE id=%s;", $row['tabla'], GetSQLValueString($row1['idActividad'], "int"));
     $rs2 = mysql_query($sql2, $conexion) or die(register_mysql_error("MU0003", mysql_error()));
     while ($row2 = mysql_fetch_array($rs2)) {
      $html .= "<option value='" . $row2['id'] . "@" . $row1['tipoActividad'] . "'>" . $row2['nombre'] . "</option>";
     }
    }
    $html .= "</optgroup>";
   }
  } else if (!$this->agregarActividades) {
   $html .="<tr><td>Ninguna actividad asignada</td></tr>";
  }
  
  if ($this->agregarActividades) {  
   $html .="<optgroup label='Acciones'>";
   $html .= "<option value='-1'>Agregar Actividad</option>";
  }
 
  $html .= "</select></form></td></tr>";
  $html .= "</table>";
  return $html;
 }
}