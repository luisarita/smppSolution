<?php

class medias {
 private $templateLogin    = "";
 private $templateHTML     = "medias/html.html";
 private $templateSorteo   = "medias/sorteo.html";
 
 private $title       = "Medias";
 private $table       = "ws_media";
 private $tableDetail = "ws_compras_msg";
 private $tableUpdate = "ws_compras_msg";
 private $sessionVar  = "id_media";

 private $numero     = "";
 private $desde      = "";
 private $hasta      = "";

 private $url        = "";
 private $pagina     = 0; 
 private $limit      = 25;
 private $enableChat = 0;

 private $cntParticipantes = 0;
 
 function medias      (){
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
  
  /* Variables propietarias */
  /*$SQL = sprintf("SELECT numero FROM (SELECT numero, SUM(resultado) AS s_resultado FROM trivias_participantes WHERE %s=%s GROUP BY numero) dt ORDER BY s_resultado DESC", $this->sessionVar, GetSQLValueString($_SESSION[$this->sessionVar], "int"));
  $rsMaxResultado           = mysql_query($SQL, $conexion) or die(register_mysql_error("TR012", mysql_error()));
  $row_rsMaxResultado       = mysql_fetch_assoc($rsMaxResultado);
  $totalRows_rsMaxResultado = mysql_num_rows($rsMaxResultado);
  $this->maxResultado = $row_rsMaxResultado['numero']; 

  $SQL = sprintf("SELECT numero FROM (SELECT numero, SUM(conteo) AS s_conteo FROM trivias_participantes WHERE %s=%s GROUP BY numero) dt ORDER BY s_conteo;", $this->sessionVar, GetSQLValueString($_SESSION[$this->sessionVar], "int"));
  $rsMaxConteo = mysql_query($SQL, $conexion) or die(register_mysql_error("TR013", mysql_error()));
  $row_rsMaxConteo = mysql_fetch_assoc($rsMaxConteo);
  $totalRows_rsMaxConteo = mysql_num_rows($rsMaxConteo);
  $this->maxConteo = $row_rsMaxConteo['numero']; */
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
   case "cambiarImagen":
    $this->cambiarImagen();
    $this->printDetail();
    break;
   case "cancelarGanadores":
    $this->cancelarGanadores();
    break; 
   case "control":
    $this->control();
    break;
   case "reiniciar":
    $this->reiniciar();
    break;
   case "printHiddenFrame":
    $this->printHiddenFrame();
    break;
   case "printVisibleFrame":
    $this->printVisibleFrame();
    break;   
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

 function cambiarImagen (){
  global $conexion;
  global $root;
  
  if (!isset( $_FILES['file']['name'] )) die( 'No ingreso un archivo' );
     
  $name = $_FILES['file']['name'];
  $src  = $_FILES['file']['tmp_name'];
  $path = select_path( $_POST['tipo'] );
  $name = str_replace( " ", "_", $name);
  move_uploaded_file( $src,  $root . $path . $name );
  $path .= $name;
  
  $query = sprintf("UPDATE ws_media SET path=%s WHERE id=%s;", GetSQLValueString($path, "text"), GetSQLValueString($_SESSION[$this->sessionVar], "int")); 
  $rs = mysql_query($query, $conexion) or die(register_mysql_error("MC0002", mysql_error()));
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
   <td style='text-align: right'>Cantidad de Participantes: " . $this->cntParticipantes . "</td>
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
 
 function printImagen             (){ /* Imprimir imÃ¡gen                        */ 
  global $root;
  global $conexion;
  
  $sql    = sprintf("SELECT path FROM ws_media WHERE tipo=7 AND id IN (%s) ORDER BY RAND() LIMIT 1;", $_SESSION[$this->sessionVar] );
  $result = mysql_query($sql, $conexion) or die(register_mysql_error("RI001", mysql_error()));
  $row    = mysql_fetch_assoc($result);

  $filename = $row['path'];
  $l        = $root . $row['path'];
  $fs       = filesize(  $l );
     
  header( 'Accept-Ranges: bytes');
  header( 'Content-Type: ' . get_type($filename)); 
  //header( 'Content-Length: $fs' );exit();
  if( strcmp($_SERVER['REQUEST_METHOD'], 'HEAD') != 0 )
   $i = readfile($l);
 }
 function printGrafico            (){
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
 function printParticipantes      (){
  global $conexion;
  
  $condition = ($this->numero == "" ) ? "" : sprintf("AND rp.numero=%s", GetSQLValueString($this->numero,"text") );
  $SQL = sprintf("SELECT rp.id, rp.numero, rp.fecha, rp.mensaje, rp.contestado FROM %s r, %s rp WHERE rp.estado=1 AND r.id=rp.%s AND r.id=%s AND fecha>=%s AND fecha<=%s %s ORDER BY rp.id DESC", $this->table, $this->tableDetail, $this->sessionVar, GetSQLValueString($_SESSION[$this->sessionVar], "int"), GetSQLValueString($this->desde, "date"), GetSQLValueString($this->hasta, "date"), $condition); 
  $cntSQL = sprintf ("SELECT COUNT(*) AS conteo FROM (%s) dt", $SQL);
  $rs = mysql_query($cntSQL, $conexion) or die(register_mysql_error("PP001", mysql_error()));
  $row = mysql_fetch_array($rs);
  $this->cntParticipantes = $row['conteo'];
  
  $SQL = sprintf("%s LIMIT %s, %s", $SQL, $this->pagina * $this->limit, $this->limit );
  $rs = mysql_query($SQL, $conexion) or die(register_mysql_error("PP002", mysql_error()));
  
  $html = "";
  $i = $this->pagina * $this->limit;
  while ($row = mysql_fetch_array( $rs )){
   $contestado = ($row['contestado'] == 1) ? "checked disabled" : "";
   $style      = ($row['contestado'] == 1) ? "" : "color: #000000";
   $html .= "<tr>
    <td scope='row'>";
   if ( $this->enableChat ){
    $html .="<input type='button' class='msg-button' value='" . $row['numero'] . "' onclick=\"javascript: popUpChat('?MM_ACTION=respuestaMensaje&id=" . $row['id'] . "')\" /><input type='checkbox' name='chkMarca' class='checkbox' onClick=\"javascript: if ( confirm('Â¿Desea marcar como contestada?')) window.location='" . $this->url ."?MM_ACTION=marcarContestado&idM=" . $row['id'] . "'\" " . $contestado . " />";
   } else {
    $html .= $row['numero'];
   }
   $html .= "</td>
    <td scope='row' style='max-width: 250px; overflow: hidden; " . $style . "'>" . $row['mensaje'] . "</td>
    <td scope='row' >" . $row['fecha'] . "</td>
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
 function printSorteo             (){
  $numero = $this->insertarGanador();

  $html = file_get_contents($this->templateSorteo);
  $html = str_replace("@@NUMERO@@",    $numero, $html);
  $html = str_replace("@@GANADORES@@", $this->printGanadores(), $html);
  $html = str_replace("@@DETALLE@@" ,  $this->printDetalleGanadores(), $html);
  echo $html;
 }
 function printGanadores          (){
  global $conexion;
 
  $SQL       = sprintf("SELECT g.numero FROM %s g WHERE %s=%s AND seleccionado=1;", $this->tableDetail, $this->sessionVar, $_SESSION[$this->sessionVar]);
  $rsNumeros = mysql_query($SQL, $conexion) or die(register_mysql_error("PG001", mysql_error())); 
  
  $html = "";
  while( $row_rsNumeros = mysql_fetch_assoc($rsNumeros) ){ 
   $html .= "<tr><td style='text-align: center' colspan='4'><font size=+1>" . $row_rsNumeros['numero'] . "</font></td></tr>";
  } 
  return $html;
 }
 function printDetalleGanadores   (){
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
  $sql = sprintf("SELECT m.nombre, c.conteo FROM ws_media m LEFT OUTER JOIN (SELECT id_media, COUNT(*) AS conteo FROM ws_compras_msg WHERE fecha >=%s AND fecha <=%s GROUP BY id_media) c ON m.id=c.id_media WHERE m.id IN (%s) GROUP BY m.nombre;", GetSQLValueString($this->desde, "date"), GetSQLValueString($this->hasta, "date"), $_SESSION[$this->sessionVar]);  
  $rsMedia = mysql_query($sql, $conexion) or die(register_mysql_error("MC002", mysql_error()));
  while ($row = mysql_fetch_array($rsMedia)){
   $html .= sprintf("<td>%s</td><td style='text-align: right'>%s</td>", $row['nombre'], $row['conteo']);
  }
  return $html;
 } 
 function printTipos              (){
  global $conexion;

  $sql = "SELECT id, nombre FROM ws_tipo";
  $rsTipos = mysql_query($sql, $conexion) or die(register_mysql_error("MC0004", mysql_error()));
  
  $selectTipos = '<select name="tipo" id="tipo">';
  while ($rowTipos = mysql_fetch_assoc($rsTipos)) {  
   $selectTipos .= sprintf( "<option value='%s'>%s</option>", $rowTipos['id'], $rowTipos['nombre']);
  } 
  $selectTipos .= "</select>";
  return $selectTipos;
 }
 function reiniciar               (){
  global $conexion;
  
  $tableUpdate = $this->tableDetail;
  if (isset($this->tableUpdate)) $tableUpdate = $this->tableUpdate;
  $sql = sprintf("UPDATE %s SET estado=0 WHERE %s=%s;", $tableUpdate, $this->sessionVar, GetSQLValueString($_SESSION[$this->sessionVar], "int"));
  mysql_query($sql, $conexion) or die(register_mysql_error("MR003", mysql_error()));

  header("Location: " . $_SERVER['PHP_SELF']);
  exit();
 } 
 function printDetail             (){
  global $conexion;
  global $_CONF;

  if (isset($_CONF['skin']) && $_CONF['skin'] != "") $this->templateHTML = "skins/" . $_CONF['skin'] . "/media.html";
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
  $html = str_replace("@@TIPOS@@",$this->printTipos(),$html);
  if (isset($_CONF['skin']) && $_CONF['skin'] != "") $html = str_replace("@@SKIN@@", $_CONF['skin'], $html);
  
  echo $html;  
 }
}


?>