<?php
class telechat {
 private $templateLogin    = "";
 private $templateHTML     = "telechat/html.html";
 
 private $title      = "Telechat";
 private $table      = "telechats";
 private $sessionVar = "idTelechat";

 private $url        = "";
 private $pagina     = 0; 


 function telechat    (){
  session_start();
  if (!isset($_SESSION[$this->sessionVar])) if (!$this->doLogin()) $this->printLogin();
  $this->constructor();
 }
 function constructor (){
  global $conexion;
  
  $this->pagina = ( isset($_GET['pagina'])) ? $_GET['pagina'] : 0;

  /*$query_cnt = sprintf("SELECT DATE_FORMAT(MIN(fecha), '%%Y-%%m-%%d %%H:00') AS desde, MAX(id) AS lastID FROM rifas_participantes WHERE estado=1 AND idRifa=%s;", GetSQLValueString($this->idRifa, "int"));
  $rs        = mysql_query( $query_cnt, $conexion ) or die(register_mysql_error("EE001", mysql_error()));
  $row       = mysql_fetch_array($rs);
  $this->desde   = $row['desde'];
  $this->lastID  =  $row['lastID'];

  $this->desde     = (isset($_POST['desde' ])) ? $_POST['desde' ] : (isset($_GET['desde' ]) ? $_GET['desde' ] : (($this->desde) ? $this->desde : strftime( "%Y-%m-%d %H:00", time() - (strftime("%H", time()) * 60 * 60 )))); 
  $this->hasta     = (isset($_POST['hasta' ])) ? $_POST['hasta' ] : (isset($_GET['hasta' ]) ? $_GET['hasta' ] : strftime( "%Y-%m-%d %H:59", time() + 3600));
  $this->numero    = (isset($_POST['numero'])) ? $_POST['numero'] : (isset($_GET['numero']) ? $_GET['numero'] : "");*/
  
  $this->url       = $_SERVER['PHP_SELF'];
 }

function action(){
  $action = isset($_POST['MM_ACTION']) ? $_POST['MM_ACTION'] : (isset($_GET['MM_ACTION']) ? $_GET['MM_ACTION'] : "");
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

 function doLogin    (){
  global $conexion;
  
  $this->doLogout();
  session_start();	 
  
  if (!isset($_POST['username']) || !isset($_POST['password'])) return false;
  $usuario = (get_magic_quotes_gpc()) ? $_POST['username'] : addslashes($_POST['username']);
  $clave = (get_magic_quotes_gpc()) ? $_POST['password'] : addslashes($_POST['password']);
  
  $sql = sprintf("SELECT id FROM %s WHERE estado=1 AND usuario='%s' AND clave='%s'", $this->table, $usuario, $clave);
  $rs  = mysql_query($sql, $conexion) or die(register_mysql_error("TL001", mysql_error()));
  $row = mysql_fetch_assoc($rs);

  if ( mysql_num_rows($rs) == 0 ) return false;
  $_SESSION[$this->sessionVar] = $row['id'];
  $_SESSION['username'] = $_POST['username'];
  header("Location: " . $_SERVER['PHP_SELF']);
  exit();
 }  
 function doLogOut   (){
  unset($_SESSION['su']);
  unset($_SESSION[$this->sessionVar]);
  session_destroy();
 }

 function printLogin              (){
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
 function printDetail             (){
  global $conexion;

  $html = file_get_contents($this->templateHTML);
  $html = str_replace("@@TITLE@@", $this->title,$html);

  echo $html;	 
 }
}
?>