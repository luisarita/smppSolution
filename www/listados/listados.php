<?php
/*
En printParticipantes se le puso -1 al estado
En printGrafico se le puso -1 al estado
En insertarGanador se le puse -1 al estado dos veces
printDetail, constructor tiene partes exclusivas
Existen variables propietarios

*/

class listado {
 private $templateLogin    = "";
 private $templateHTML     = "listados/html.html";
 private $templateSorteo   = "listados/sorteo.html";
 
 private $title       = "Listados";
 private $table       = "listados";
 private $tableDetail = "(SELECT lp.id, lm.idListado, lp.fecha, lp.estado, lp.numero, lp.mensaje, lp.contestado, lp.seleccionado FROM listados_participantes lp, listados_mensajes lm WHERE lm.id=lp.idMensaje)";
 private $tableUpdate = "listados_participantes";
 private $sessionVar  = "idListado";

 private $url        = "";
 private $pagina     = 0; 
 private $limit      = 25;
 private $enableChat = 0;

 private $cntParticipantes = 0;
 
 /* Variables propietarias */
 /*private $maxResultado = "";
 private $maxConteo    = "";*/

 function listado      (){
  session_start();
  if (!isset($_SESSION[$this->sessionVar])) if (!$this->doLogin()) $this->printLogin();
  $this->constructor();
 }
 function constructor  (){
  global $conexion;
  global $database_conexion;
  
  $this->pagina = ( isset($_GET['pagina'])) ? $_GET['pagina'] : 0;
  mysql_select_db($database_conexion, $conexion);

  $query_cnt = sprintf("SELECT DATE_FORMAT(MIN(p.fecha), '%%Y-%%m-%%d %%H:00') AS desde, MAX(p.id) AS lastID FROM %s p WHERE p.estado=1 AND p.%s=%s;", $this->tableDetail, $this->sessionVar, GetSQLValueString($_SESSION[$this->sessionVar], "int"));
  $rs        = mysql_query( $query_cnt, $conexion ) or die(register_mysql_error("CN001", mysql_error()));
  $row       = mysql_fetch_array($rs);
  $this->desde   = $row['desde'];
  $this->lastID  =  $row['lastID'];

  $this->desde     = (isset($_POST['desde' ])) ? $_POST['desde' ] : (isset($_GET['desde' ]) ? $_GET['desde' ] : (($this->desde) ? $this->desde : strftime( "%Y-%m-%d %H:00", time() - (strftime("%H", time()) * 60 * 60 )))); 
  $this->hasta     = (isset($_POST['hasta' ])) ? $_POST['hasta' ] : (isset($_GET['hasta' ]) ? $_GET['hasta' ] : strftime( "%Y-%m-%d %H:59", time() + 3600));
  $this->numero    = (isset($_POST['numero'])) ? $_POST['numero'] : (isset($_GET['numero']) ? $_GET['numero'] : "");
  
  $this->url       = $_SERVER['PHP_SELF'];
  
 }

 function action(){
  $action = isset($_POST['MM_ACTION']) ? $_POST['MM_ACTION'] : (isset($_GET['MM_ACTION']) ? $_GET['MM_ACTION'] : "");
  switch ($action){
   case "logout":
    $this->doLogout();
    $this->printLogin();
    break;
   case "imagen":
    $this->printImagen();
	break;
   case "grafico":
    $this->printGrafico();
	break;
   case "sorteo":
    $this->printSorteo();
	break;	
   case "cancelarGanadores":
    $this->cancelarGanadores();
	break;
	
   case "eliminar":
    $this->eliminar();
	break;
   case "agregar":
    $this->agregar();
	break;
   /*case "reiniciar":
    $this->reiniciar();
	break;*/
	
   default:
    $this->printDetail();
	break;
  }
 }

 function doLogin    (){
  global $conexion;
  global $database_conexion;
  
  $this->doLogout();
  session_start();	 
  
  if (!isset($_POST['username']) || !isset($_POST['password'])) return false;
  $usuario = (get_magic_quotes_gpc()) ? $_POST['username'] : addslashes($_POST['username']);
  $clave = (get_magic_quotes_gpc()) ? $_POST['password'] : addslashes($_POST['password']);
  
  mysql_select_db($database_conexion, $conexion);
  $sql = sprintf("SELECT id FROM %s WHERE estado=1 AND usuario='%s' AND clave='%s'", $this->table, $usuario, $clave);
  $rs  = mysql_query($sql, $conexion) or die(register_mysql_error("VI001", mysql_error()));
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

 function insertarGanador         (){ /* Insertar un nuevo ganador              */ 
  global $conexion;
  $numero = "    NO HAY ";
  
  $sql = sprintf("SELECT id, numero FROM %s dt WHERE estado=1 AND %s=%s AND fecha>=%s AND fecha<=%s AND numero NOT IN (SELECT DISTINCT numero FROM %s dti WHERE estado=1 AND seleccionado=1 AND %s=%s) ORDER BY RAND() LIMIT 1;", $this->tableDetail, $this->sessionVar, $_SESSION[$this->sessionVar], GetSQLValueString($this->desde, "date"), GetSQLValueString($this->hasta, "date"), $this->tableDetail, $this->sessionVar, $_SESSION[$this->sessionVar]);
  $rsJ = mysql_query($sql, $conexion) or die(register_mysql_error("IG001", mysql_error()));
  if ( mysql_num_rows($rsJ) > 0 ){
   while ( $rowJ = mysql_fetch_array( $rsJ )){
    $numero = $rowJ[ 'numero' ];
    $id     = $rowJ[ 'id' ];
	$tableUpdate = $this->tableDetail;
	if (isset($this->tableUpdate)) $tableUpdate = $this->tableUpdate;
    $insertSQL = sprintf("UPDATE %s SET seleccionado=1 WHERE id=%s;", $tableUpdate, $id);
    $numero = substr($numero,0,strlen($numero)-2) . "??";
    mysql_query($insertSQL, $conexion) or die(register_mysql_error("IG002", mysql_error()));
   }
  }
  return $numero;
 }
 function cancelarGanadores       (){ /* Cancelar a todos los ganadores         */ 
  global $conexion;
  
  if ((isset($_POST["MM_ACTION"])) && ($_POST["MM_ACTION"] == "cancelarGanadores")) {
   $SQL = array();
   
   $tableUpdate = $this->tableDetail;
   if (isset($this->tableUpdate)) $tableUpdate = $this->tableUpdate;
   $SQL[sizeof($SQL)] = sprintf("UPDATE %s SET seleccionado=0 WHERE id IN (SELECT id FROM %s dt WHERE %s=%s);", $tableUpdate, $this->tableDetail, $this->sessionVar, $_SESSION[$this->sessionVar]); 
   foreach ($SQL as $key => $value){ echo $value;
    $rs = mysql_query($value, $conexion) or die(register_mysql_error("CG00" . $key, mysql_error()));
   }
  }
   
  header("Location: " . $_SERVER['PHP_SELF']);
  exit();
 }
 function printConteoParticipantes(){ /* Imprimir participantes de la actividad */ 
  $html = "";
  $html = "<tr>
   <td>Cantidad de Participantes:</td>
   <td colspan='2' style='text-align: right'>" . $this->cntParticipantes . "</td>
  </tr>";
  return $html;  
 }
 function printLogin              (){ /* Imprimir pantalla de inicio de sesion  */ 
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
 function printImagen             (){ /* Imprimir im�gen                        */ 
  global $conexion;
  $rs = mysql_query(sprintf("SELECT logo_archivo, logo_tipo FROM %s WHERE id=%s;", $this->table, GetSQLValueString($_SESSION[$this->sessionVar],"int")), $conexion) or die(register_mysql_error("TI001", mysql_error()));
  $row    = mysql_fetch_assoc($rs);
  $data   = $row[ "logo_archivo" ];
  $type   = $row[ "logo_tipo" ];
  header( "Content-type: $type");
  echo $data;
 }
 function printGrafico            (){ /* Imprimir gr�fico                       */ 
  global $conexion;
  global $_CONF;
  
  $title = ""; 
  $this->desde  = (isset($_GET['month']) && isset($_GET['anio'])) ? $_GET['anio'] . "-" . $_GET['month'] . "-01" : date("Y-m-01");
  $this->hasta  = (isset($_GET['month']) && isset($_GET['anio'])) ? date('Y-m-d 23:59:59', strtotime('-1 second', strtotime('+1 month', strtotime($_GET['month'] . '/01/' . $_GET['anio'] .' 00:00:00')))) : date('Y-m-d 23:59:59', strtotime('-1 second', strtotime('+1 month', strtotime(date('m') . '/01/' . date('Y') .' 00:00:00'))));
  
  $sql   = sprintf("SELECT DayOfMonth(fecha) AS descripcion, COUNT(*) AS cantidad, '%s' AS color FROM %s dt WHERE estado=1 AND %s=%s AND fecha>=%s AND fecha<%s GROUP BY DayOfMonth(fecha)", $_CONF['bargraph-color'], $this->tableDetail, $this->sessionVar, $_SESSION[$this->sessionVar], GetSQLValueString($this->desde, "date"), GetSQLValueString($this->hasta, "date")); 
  $result = mysql_query($sql, $conexion) or die(mysql_error());

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
 function printParticipantes      (){ /* Imprimir participantes                 */ 
  global $conexion;
  
  $condition = ($this->numero == "" ) ? "" : sprintf("AND rp.numero=%s", GetSQLValueString($this->numero,"text") );
  $SQL = sprintf("SELECT rp.id, rp.numero, rp.fecha, rp.mensaje, rp.contestado FROM %s r, %s rp WHERE rp.estado=1 AND r.id=rp.%s AND r.id=%s AND fecha>=%s AND fecha<=%s %s ORDER BY rp.id DESC", $this->table, $this->tableDetail, $this->sessionVar, GetSQLValueString($_SESSION[$this->sessionVar], "int"), GetSQLValueString($this->desde, "date"), GetSQLValueString($this->hasta, "date"), $condition); 
  $cntSQL = sprintf ("SELECT COUNT(*) AS conteo FROM (%s) dt", $SQL);
  $rs = mysql_query($cntSQL, $conexion) or die(register_mysql_error("PP001", mysql_error()));
  $row = mysql_fetch_array($rs);
  $this->cntParticipantes = $row['conteo'];
  
  $SQL = sprintf("%s LIMIT %s, %s", $SQL, $this->pagina * $this->limit, $this->limit );
  $rs = mysql_query($SQL, $conexion) or die(register_mysql_error("PP002", mysql_error()));
  
  $html = "<table border='0' cellpadding='0' cellspacing='0' width='100%'>
   <tr>
    <th style='text-align: center; width: 90px'>N&uacute;mero</th>
    <th style='text-align: center'>Mensaje</th>
    <th style='text-align: center; width: 130px'>Fecha</th>
   </tr>";
  $i = $this->pagina * $this->limit;
  while ($row = mysql_fetch_array( $rs )){
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
   </tr>";
   ++$i;
  }
	  
  $anterior  = (!$this->pagina == 0) ? "<a href='$this->url?pagina=" . ($this->pagina - 1) . "'>&laquo; Anterior</a>" : "";
  $siguiente = "<a href='$this->url?pagina=" . ($this->pagina + 1) . "'>Siguiente &raquo;</a>";

  $html .= "<tr><td colspan='4'>&nbsp;</td></tr>";
  $html .= sprintf("<tr><th colspan='4' style='text-align: right'>%s <a>-</a> %s</th></tr>", $anterior, $siguiente);
  $html .= "</table>";
  return $html;
  
 }
 function printSorteo             (){ /* Imprimir sorteo                        */ 
  $numero = $this->insertarGanador();

  $html = file_get_contents($this->templateSorteo);
  $html = str_replace("@@NUMERO@@",    $numero, $html);
  $html = str_replace("@@GANADORES@@", $this->printGanadores(), $html);
  $html = str_replace("@@DETALLE@@" ,  $this->printDetalleGanadores(), $html);
  echo $html;
 }
 function printGanadores          (){ /* Imprimir ganadores                     */ 
  global $conexion;
 
  $SQL       = sprintf("SELECT g.numero FROM %s g WHERE %s=%s AND seleccionado=1;", $this->tableDetail, $this->sessionVar, $_SESSION[$this->sessionVar]);
  $rsNumeros = mysql_query($SQL, $conexion) or die(register_mysql_error("PG001", mysql_error())); 
  
  $html = "";
  while( $row_rsNumeros = mysql_fetch_assoc($rsNumeros) ){ 
   $html .= "<tr><td style='text-align: center' colspan='4'><font size=+1>" . $row_rsNumeros['numero'] . "</font></td></tr>";
  } 
  return $html;
 }
 function printDetalleGanadores   (){ /* Imprimir detalle de ganadores          */ 
  global $conexion;
  $sql       = sprintf("SELECT g.numero FROM %s g WHERE %s=%s AND seleccionado=1 ORDER BY fecha DESC;", $this->tableDetail, $this->sessionVar, $_SESSION[$this->sessionVar]);
  $rs = mysql_query($sql, $conexion) or die(register_mysql_error("RR005", mysql_error()));
  
  $html = "";
  while ($row = mysql_fetch_assoc($rs)){
   //$html .= sprintf("<tr><td align='center'>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>", $row['numero'], $row['fecha'], $row['desde'], $row['hasta'] );
  }
  return $html;
 }
 
 /* Funciones propietarias */ 
 function printOpciones           (){
  global $conexion;

  $html = "";
  
  $SQL = sprintf("SELECT id, mensaje FROM listados_mensajes WHERE %s=%s ORDER BY mensaje;", $this->sessionVar, GetSQLValueString($_SESSION[$this->sessionVar], "int"));
  $rsOpciones = mysql_query($SQL, $conexion) or die(register_mysql_error("PO001", mysql_error()));
  
  while( $row = mysql_fetch_array( $rsOpciones ) ){
   $html .= "<tr>
    <td>" . $row['mensaje'] . "&nbsp;</td>
    <td style='text-align: right'><input class='checkbox' type='checkbox' name='ids[]' value='" . $row['id'] ."' /></td>
   </tr>";
  }
  return $html;
 }
 function eliminar              (){
  global $conexion;
  
  $ids      = $_POST['ids'];
  foreach ( $ids as $id => $value ){
   $sql = sprintf("DELETE FROM listados_mensajes WHERE id=%s AND %s=%s;", GetSQLValueString($value, "int"), $this->sessionVar, GetSQLValueString($_SESSION[$this->sessionVar], "int"));
   mysql_query($sql, $conexion) or die(register_mysql_error("E0001", mysql_error()));
  }

  header("Location: " . $_SERVER['PHP_SELF']);
  exit();
 }
 function agregar              (){
  global $conexion;
  
  $msg = $_POST['msg'];
  $sql = sprintf("INSERT INTO listados_mensajes(idListado, mensaje) VALUES (%s, %s);", GetSQLValueString($_SESSION[$this->sessionVar], "int"), GetSQLValueString($msg, "text") );
  mysql_query($sql, $conexion) or die(register_mysql_error("E0001", mysql_error()));

  header("Location: " . $_SERVER['PHP_SELF']);
  exit();
 }
 function reiniciar               (){
  global $conexion;
  
  $tableUpdate = $this->tableDetail;
  if (isset($this->tableUpdate)) $tableUpdate = $this->tableUpdate;
  $sql = sprintf("UPDATE %s SET estado=0 WHERE id IN (SELECT id FROM %s dt WHERE %s=%s);", $tableUpdate, $this->tableDetail, $this->sessionVar, $_SESSION[$this->sessionVar]);
  mysql_query($sql, $conexion) or die(register_mysql_error("LR003", mysql_error()));

  header("Location: " . $_SERVER['PHP_SELF']);
  exit();
 }

 
 function printDetail             (){
  global $conexion;
  global $_CONF;

  $html = file_get_contents($this->templateHTML);
  $html = str_replace("@@TITLE@@", $this->title,$html);
  $html = str_replace("@@MAXLENGTH@@"   , $_CONF['msg_max_length'], $html);
  $html = str_replace("@@PARTICIPANTES@@",$this->printParticipantes(),$html);
  $html = str_replace("@@CONTEOPARTICIPANTES@@",$this->printConteoParticipantes(),$html);
  $html = str_replace("@@DESDE@@",$this->desde,$html);
  $html = str_replace("@@HASTA@@",$this->hasta,$html);
  $html = str_replace("@@NUMERO@@",$this->numero,$html);
  $html = str_replace("@@GANADORES@@",$this->printGanadores(),$html);
  $html = str_replace("@@OPCIONES@@",$this->printOpciones(),$html);
  
  /* Estad�sticas Propietarias  */
  /*$html = str_replace("@@MAYORNUMERO@@",$this->maxConteo,$html);
  $html = str_replace("@@MEJORRESULTADO@@",$this->maxResultado,$html);*/

  echo $html;	 
 }
}


?>