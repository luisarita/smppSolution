<?php 

ini_set("max_execution_time", 0);
ini_set("memory_limit","2048M");

class suscripcion {
 private $templateLogin    = "templates/login.html";
 private $templateHTML     = "suscripciones/html.html";
 private $templateListado  = "suscripciones/listado.html";
 private $templateSorteo   = "suscripciones/sorteo.html";
 private $templateHistorial = "suscripciones/historial.html";
 private $templateEnvio = "suscripciones/envios.html";

 private $title      = "Suscripciones";
 private $table      = "suscripciones";
 private $sessionVar = "idSuscripcion";
 private $directorio = "_repositorio/suscripciones/";
 private $url        = "";
 private $pagina     = 0; 
 private $desde;
 private $hasta;

 public  $idSuscripcion;
 private $shortCode;
 private $cntParticipantes = 0;
 private $habilitarMedia = 0;
 private $aplicarVariables = 0;
 private $variables = array( 1 => '', 2 => '', 3 => '', 4 => '', 5 => '');

 function suscripcion($id = -1, $redir = true){
  if (!isset($_SESSION)){ session_start(); }
  if (!isset($_SESSION[$this->sessionVar]))
   if ($this->validarLink()){
    $this->constructor();
   } else if (!$this->doLogin($id, $redir)){
    if ($redir) {
     $this->printLogin();
    }
   } else {
    $this->constructor();
  } else {
   $this->constructor();
  }
 }
 function constructor(){
  global $conexion;
  global $database_conexion;
  
  $this->idSuscripcion = $_SESSION[$this->sessionVar];
  $this->pagina = ( isset($_GET['pagina'])) ? $_GET['pagina'] : 0;
  mysql_select_db($database_conexion, $conexion);

  $SQL1         = sprintf("SELECT habilitar_media, numero, BIN(aplicarVariables) AS aplicarVariables, variable1, variable2, variable3, variable4, variable5 FROM suscripciones WHERE id=%s", GetSQLValueString($this->idSuscripcion, "int"));
  $rs1          = mysql_query( $SQL1, $conexion ) or die(register_mysql_error("SS001", mysql_error()));
  $row1         = mysql_fetch_array($rs1);
  
  $this->habilitarMedia   = $row1['habilitar_media'];
  $this->aplicarVariables = $row1['aplicarVariables'];
  
  foreach ($this->variables as $key => $value){
   $this->variables[$key] = trim($row1[sprintf("variable%s", ($key))]);
  }
    
  $this->shortCode        = $row1['numero'];
  
  $SQL2           = sprintf("SELECT DATE_FORMAT(MIN(fecha), '%%Y-%%m-%%d %%H:00') AS desde, MAX(id) AS lastID FROM suscripciones_participantes WHERE idSuscripcion=%s", GetSQLValueString($this->idSuscripcion, "int"));
  $rs2            = mysql_query( $SQL2, $conexion ) or die(register_mysql_error("SS002", mysql_error()));
  $row2           = mysql_fetch_array($rs2);
  $this->desde   = $row2['desde'];
  $this->lastID  = $row2['lastID'];

  $this->desde     = (isset($_POST['desde' ])) ? $_POST['desde' ] : (isset($_GET['desde' ]) ? $_GET['desde' ] : (($this->desde) ? $this->desde : strftime( "%Y-%m-%d %H:00", time() - (strftime("%H", time()) * 60 * 60 )))); 
  $this->hasta     = (isset($_POST['hasta' ])) ? $_POST['hasta' ] : (isset($_GET['hasta' ]) ? $_GET['hasta' ] : strftime( "%Y-%m-%d %H:59", time() + 3600));
  $this->numero    = (isset($_POST['numero'])) ? $_POST['numero'] : (isset($_GET['numero']) ? $_GET['numero'] : "");
  
  $this->url       = $_SERVER['PHP_SELF'];
 }
 
 function doLogin ($id, $redir){
  global $conexion;
  global $database_conexion;
  
  $this->doLogout();
  session_start();
  
  if (!isset($_POST['username']) || !isset($_POST['password'])) return false;
  $usuario = (get_magic_quotes_gpc()) ? $_POST['username'] : addslashes($_POST['username']);
  $clave = (get_magic_quotes_gpc()) ? $_POST['password'] : addslashes($_POST['password']);
  
  mysql_select_db($database_conexion, $conexion);
  $sql = sprintf("SELECT id FROM %s WHERE (activa=1 AND usuario='%s' AND clave='%s') AND (id=%s OR -1=%s)", $this->table, $usuario, $clave, $id, $id);
  $rs  = mysql_query($sql, $conexion) or die(register_mysql_error("EL001", mysql_error())); 
  $row = mysql_fetch_assoc($rs);

  if ( mysql_num_rows($rs) == 0 ) return false;
  $_SESSION[$this->sessionVar] = $row['id'];
  $_SESSION['username'] = $_POST['username'];
  if ($redir){
   header("Location: " . $_SERVER['PHP_SELF']);
   exit();
  } else {
   return true;
  }
 }
 function validarLink(){
  global $_CONF;
  if (!isset($_GET['verify']) || !isset($_GET['idSuscripcion']) || !isset($_GET['idMensaje'])) return false;
  $realSeed = md5(sprintf("%s%s%s", $_GET['idSuscripcion'], $_GET['idMensaje'], $_CONF['hash_salt']));
  $builtSeed = sprintf("%s", $_GET['verify']);
  if ($realSeed != $builtSeed) return false;
  return true;
 }
 function doLogOut(){
  unset($_SESSION['su']);
  unset($_SESSION[$this->sessionVar]);
  session_destroy();
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
   case "eliminarMensaje":
    $this->eliminarMensaje();
    break;
   case "enviarMensaje":
    $this->enviarMensaje();
    break; 
   case "enviarMensajeMedia":
    $this->enviarMensajeMedia();
    break; 
   case "cancelarGanadores":
    $this->cancelarGanadores();
    break;
   case "printListado":
    $this->printListado();
    break; 
   case "printSorteo":
    $this->printSorteo();
    break; 
   case "historialSorteo":
    $this->printHistorialSorteo();
    break;			
   case "detalleEnvio":
    $this->printDetalleEnvio();
    break;			
   case "aprobarMensaje":
    $this->aprobarMensaje();
    break;		
   case "cargarExcel":
    $this->cargarExcel();
    break;
   case "descargarExcel":
    $this->descargarExcel();
    break;       
   default:
    $this->printDetail();
    break;
  }
 }
 
 function contenidoMailAprobacion($contenido){
  global $_CONF;
  global $conexion;
  
  $sql = sprintf("SELECT m.id AS idMensaje, s.numero, s.nombre, m.fecha FROM suscripciones s, suscripciones_mensajes m WHERE m.idSuscripcion=s.id AND m.idSuscripcion=%s ORDER BY m.id DESC", $this->idSuscripcion);
  $rs = mysql_query($sql, $conexion) or die(register_mysql_error("SSR000", mysql_error()));
  $row = mysql_fetch_array($rs);

  $seed = sprintf("%s%s%s", $this->idSuscripcion, $row['idMensaje'], $_CONF['hash_salt']);
  $link = sprintf("http://%s/suscripciones.php?MM_ACTION=aprobarMensaje&idSuscripcion=%s&idMensaje=%s&verify=%s&", $_CONF['url_suscripciones'], $this->idSuscripcion, $row['idMensaje'], md5($seed));
  $html = sprintf("<b>Número:</b>%s<br/><b>Suscripción:</b>(%s) %s<br/><b>Fecha de Salida:</b>%s<br/><b>Mensaje:</b>%s<br /><br/><a href='%s'>Aprobar</a>", $row['numero'], $this->idSuscripcion, $row['nombre'], $row['fecha'], $contenido, $link);
  return $html;
 }
 
 function enviarMensaje (){
  global $conexion;
  $condicion = " ";

  for ($i = 1; $i < 6 ; $i++){
   if (isset($_POST['condHidden' . $i]) && strlen($_POST['condHidden' . $i]) > 0){
    $resultado = $this->validarCondicion($_POST['condHidden' . $i]);
    if ($resultado != 'error' ){
     $condicion .= sprintf("%s", $resultado);
     //echo $condicion . "<br/>";
    } else {
     die('Las variables no cumplieron con las condiciones esperadas: ' . $_POST['condHidden']);
    }
   }
  }

  if (strlen($_POST['contenido']) > 180){
   header(sprintf("Location: %s?alert=longitud", $_SERVER['PHP_SELF']));
   exit(); 
  }
  
  $SQL = sprintf("SELECT limiteMensajesDiarios - COUNT(*) AS disponibles, DATEDIFF(DATE(%s),NOW()) AS dias, s.nombre, s.requiereAprobacion, s.correoAprobacion FROM suscripciones s LEFT OUTER JOIN (SELECT * FROM suscripciones_mensajes WHERE DATE(fecha)=DATE(%s) AND estado IN (0,1)) m ON s.id=m.idSuscripcion WHERE s.id=%s GROUP BY s.nombre, s.requiereAprobacion, s.correoAprobacion;", GetSQLValueString($_POST['fecha'], "date"), GetSQLValueString($_POST['fecha'], "date"), GetSQLValueString($this->idSuscripcion, "int"));
  $rs = mysql_query($SQL, $conexion) or die(mysql_error()); // die(register_mysql_error("SSR000", mysql_error()));
  $row = mysql_fetch_array($rs);
  
  if ($row['disponibles'] <= 0){
   header(sprintf("Location: %s?alert=limiteMensajesDiarios", $_SERVER['PHP_SELF']));
   exit(); 
  } else if ($row['dias'] < 0){  header(sprintf("Location: %s?alert=fechaAnterior", $_SERVER['PHP_SELF']));
   exit(); 
  }
  
  $SQL = array();
  $fecha = sprintf('CASE WHEN %s < NOW() THEN NOW() ELSE %s END', GetSQLValueString($_POST['fecha'], "date"), GetSQLValueString($_POST['fecha'], "date"));
  $SQL[] = sprintf("INSERT INTO suscripciones_mensajes (idSuscripcion, mensaje, fecha_creacion, fecha, condicion) VALUES (%s, %s, NOW(), %s, %s);", GetSQLValueString($this->idSuscripcion, "int"), GetSQLValueString($_POST['contenido'], "text"), $fecha, GetSQLValueString($condicion, "text"));

  foreach ($SQL as $key => $value){
   $rs = mysql_query($value, $conexion) or die(mysql_error());
  }

  if ($row['requiereAprobacion'] == 1){
   enviarMail($row['correoAprobacion'], $this->contenidoMailAprobacion($_POST['contenido']), 'Aprobacion de mensaje: ' . $row['nombre']);
  }
  
  header(sprintf("Location: %s?alert=agregado", $_SERVER['PHP_SELF']));
  exit(); 
 }
 
 function cargarExcel(){
   global $conexion;
   
   $archivo = $this->directorio . $this->idSuscripcion."_".date("YmdHis").".".substr(strrchr($_FILES["fileExcel"]['name'], '.'), 1);
   $version = (substr(strrchr($archivo, '.'), 1) == "xlsx" ) ? "Excel2007" : "Excel5";
   $cont = 1;
   $registro = array("","","","","","");
	
   if ($archivo != "") {
    if (copy($_FILES['fileExcel']['tmp_name'], $archivo)) {
        $reader = PHPExcel_IOFactory::createReader($version);
        $reader->setReadDataOnly(true);
        $phpExcel = $reader->load($archivo);
        $workSheet = $phpExcel->getActiveSheet();

        foreach($workSheet->getRowIterator() as $row){ 
            $registro[1] = $phpExcel->getActiveSheet()->getCell('A'.$cont)->getValue(); 
            $registro[2] = $phpExcel->getActiveSheet()->getCell('B'.$cont)->getValue();
            $registro[3] = $phpExcel->getActiveSheet()->getCell('C'.$cont)->getValue(); 
            $registro[4] = $phpExcel->getActiveSheet()->getCell('D'.$cont)->getValue(); 
            $registro[5] = $phpExcel->getActiveSheet()->getCell('E'.$cont)->getValue();
            $registro[6] = $phpExcel->getActiveSheet()->getCell('F'.$cont)->getValue();
            $variable = 0;

            foreach ($registro as $key => $value){
                if ($key > 1){
                    if(strlen(($value)) > 0 ){
                        $variable = $key-1;
                    }
                    $value = get_magic_quotes_gpc() ? stripslashes($value) : $value;
                    $value = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($value) : mysql_escape_string($value);
                    $value = "'" . $value . "'";
                    $registro[ $key ] = $value;
                }
            }

            if(validarNumero($registro[1])){ 	    
                $query = sprintf("REPLACE INTO suscripciones_participantes (idsuscripcion, numero, fecha, estado, variable1, variable2, variable3, variable4, variable5, variableEnviada, variableLlenada) VALUES (%s, %s, NOW(), 1, %s, %s, %s, %s, %s, %s, %s)", GetSQLValueString($this->idSuscripcion,"text"), $registro[1], $registro[2], $registro[3], $registro[4], $registro[5], $registro[6], GetSQLValueString($variable,"int"), GetSQLValueString($variable,"int"));
                mysql_query($query, $conexion) or die(register_mysql_error("SCL0001", mysql_error()));
                $this->cargados += 1;
            } else {
                if($this->erroneos != ""){ $this->erroneos .= "<br/>"; }
                $this->erroneos .= $registro[1];
            }
            $cont++;
        }
        mysql_query("UPDATE suscripciones_participantes p, suscripciones_bloqueos b SET p.estado=0 WHERE p.numero=b.numero;", $conexion) or die(register_mysql_error("SCL0002", mysql_error()));
        $sql = sprintf("INSERT INTO suscripciones_carga_lote (id, idSuscripcion, fecha, usuario, conteo_carga, nombre_archivo) VALUES (NULL, %s, NOW(), %s, %s, %s)", GetSQLValueString($this->idSuscripcion,"int"), GetSQLValueString($_SESSION['username'], "text"), GetSQLValueString($cont-1, "int"), GetSQLValueString($archivo,"text"));
        mysql_query($sql, $conexion) or die(register_mysql_error("SCL0003", mysql_error()));
        $this->printDetail();
    } else {
        echo "<script>alert('Error al cargar archivo');</script>";
    }
   }
  }
 function descargarExcel(){
  global $conexion;

  $sql = sprintf("SELECT p.numero, p.variable1, p.variable2, p.variable3, p.variable4, p.variable5 FROM suscripciones r, suscripciones_participantes p WHERE p.idSuscripcion=r.id AND r.id=%s ORDER BY r.nombre, p.fecha;", $this->idSuscripcion);
  $rs = mysql_query( $sql, $conexion ) or die(register_mysql_error("XS001", mysql_error()));

  $objPHPExcel = new PHPExcel();
  $objPHPExcel->getProperties()->setCreator("SMPP");
  $objPHPExcel->getProperties()->setLastModifiedBy("SMPP");
  $objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Document");
  $objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Document");
  $objPHPExcel->getProperties()->setDescription("Office 2007 XLSX");

  $i = 1; $j = 0; $lname = "";
  while ($row = mysql_fetch_array($rs)){
   if ( $j == 0 ){
    $objPHPExcel->setActiveSheetIndex($j);
    $objPHPExcel->getActiveSheet()->setTitle("Datos");
    $i = 1;
    ++$j;
   }
  
   $objPHPExcel->getActiveSheet()->SetCellValue("A$i", $row['numero'  ]);
   $objPHPExcel->getActiveSheet()->SetCellValue("B$i", $row['variable1']);
   $objPHPExcel->getActiveSheet()->SetCellValue("C$i", $row['variable2']);
   $objPHPExcel->getActiveSheet()->SetCellValue("D$i", $row['variable3']);
   $objPHPExcel->getActiveSheet()->SetCellValue("E$i", $row['variable4']);
   $objPHPExcel->getActiveSheet()->SetCellValue("F$i", $row['variable5']);
   ++$i;
  }
		
  $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
  $path     = "basedatos.xlsx";
  $fullpath = tempnam(sys_get_temp_dir(), 'suscripciones');
  $objWriter->save($fullpath);
   
  header( 'Accept-Ranges: bytes');
  header( 'Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
  header( 'Content-Disposition: attachment; filename=' . $path);
  header( 'Pragma: no-cache');
  header( 'Expires: 0');
  if( strcmp($_SERVER['REQUEST_METHOD'], 'HEAD') != 0 ){
   $i = readfile($fullpath);
  }
  exit();
 }
 
 function validarCondicion( $string ){
  $condicionCompleta = "";
  $correcto = false;
       
  $condiciones = explode("@@OR@@", $string);  //str_replace(' ','',$string));   
           
  for ( $i=0; $i < count($condiciones); $i++){
   if( preg_match("/^TRIM\(p.variable[1-5]\)[<|>|=|>=|<=]TRIM\([0-9a-zA-Z'.,¿?¡!\s]{1,}\)$/", $condiciones[$i])){
    $correcto = true;
   } else {
    $correcto = false;
    return "error";
   }           
  }
  if ( $correcto ){
   for($x = 0; $x < count($condiciones); $x++){
    $condicionCompleta = ( $x == 0 ) ? (" AND (" . $condiciones[$x] ) . "" : ($condicionCompleta . " OR " . $condiciones[$x]);
   }
   $condicionCompleta .= ")";
  }
  return $condicionCompleta;
 }
 
 function enviarMensajeMedia       (){
  global $conexion;

  $SQL = array();
  $SQL[] = sprintf("INSERT INTO suscripciones_medias (idSuscripcion, idMedia, fecha) VALUES (%s, %s, %s);", GetSQLValueString($this->idSuscripcion, "int"), GetSQLValueString($_POST['idMedia'], "text"), GetSQLValueString($_POST['fecha'], "date"));
   
  foreach ($SQL as $key => $value){
   $rs = mysql_query($value, $conexion) or die(register_mysql_error("SSAM0" . $key, mysql_error()));
  }
   
  header(sprintf("Location: %s?alert=agregado", $_SERVER['PHP_SELF']));
  exit();
 }
 function eliminarMensaje          (){
  global $conexion;

  $SQL = array();
  $SQL[] = sprintf("UPDATE suscripciones_mensajes SET estado=-1 WHERE estado=0 AND idSuscripcion=%s AND id=%s;", GetSQLValueString($this->idSuscripcion, "int"), GetSQLValueString($_GET['id'], "int"));
   
  foreach ($SQL as $key => $value){
   $rs = mysql_query($value, $conexion) or die(register_mysql_error("SSE00" . $key, mysql_error()));
  }
   
  header(sprintf("Location: %s", $_SERVER['PHP_SELF']));
  exit();
 }
 function insertarGanador          (){
  global $conexion;
  $numero = "    NO HAY ";

  $sql = sprintf("SELECT id, numero FROM suscripciones_participantes WHERE idSuscripcion=%s AND fecha>=%s AND fecha<=%s AND numero NOT IN (SELECT DISTINCT numero FROM suscripciones_ganadores WHERE estado=1 AND idSuscripcion=%s) ORDER BY RAND() LIMIT 1;", $this->idSuscripcion, GetSQLValueString($this->desde, "date"), GetSQLValueString($this->hasta, "date"), $this->idSuscripcion);
  $rsPosible           = mysql_query($sql, $conexion) or die(register_mysql_error("SR001", mysql_error()));
  $row_rsPosible       = mysql_fetch_assoc($rsPosible);
  $totalRows_rsPosible = mysql_num_rows($rsPosible);
  if ( $totalRows_rsPosible > 0 ){ 
   $id = $row_rsPosible[ 'id' ];
   $numero = $row_rsPosible[ 'numero' ];

   $insertSQL = sprintf("INSERT INTO suscripciones_ganadores (idSuscripcion, numero, estado, fecha, desde, hasta, mensaje) VALUES (%s, %s, 1, NOW(), %s, %s, '%s');", GetSQLValueString($this->idSuscripcion, "int"), GetSQLValueString($numero, "text"), GetSQLValueString($this->desde, "date"), GetSQLValueString($this->hasta, "date"), "");
   $numero = substr($numero,0,strlen($numero)-2) . "??";
   mysql_query($insertSQL, $conexion) or die(register_mysql_error("SR002", mysql_error()));   
   
   /*$sql = sprintf("UPDATE suscripciones_participantes SET seleccionado=0 WHERE numero=%s AND idSuscripcion=%s;", GetSQLValueString($numero, "text"), $this->idSuscripcion);
   mysql_query($sql, $conexion) or die(register_mysql_error("SR003", mysql_error()));
   $sql = sprintf("UPDATE suscripciones_participantes SET seleccionado=1 WHERE id=%s;", $id);
   mysql_query($sql, $conexion) or die(register_mysql_error("SR004", mysql_error()));
   $numero = substr($numero,0,strlen($numero)-2) . "??";*/   
  }
  return $numero;
 }
 function cancelarGanadores        (){
  global $conexion;
  
  if ((isset($_POST["MM_ACTION"])) && ($_POST["MM_ACTION"] == "cancelarGanadores")) {
   $SQL = sprintf("CALL sp_suscripciones_cancelar_ganadores(%s)", GetSQLValueString($this->idSuscripcion, "int"));   
   mysql_query($SQL, $conexion) or die(register_mysql_error("RCG00" . $key, mysql_error()));

   /*$SQL = array();
   $SQL[] = sprintf("UPDATE suscripciones_participantes SET seleccionado=0 WHERE idSuscripcion=%s;", GetSQLValueString($this->idSuscripcion, "int"));
   foreach ($SQL as $key => $value){
    $rs = mysql_query($value, $conexion) or die(register_mysql_error("RCG00" . $key, mysql_error()));
   }*/
  }
   
  header("Location: " . $_SERVER['PHP_SELF']);
  exit();
 }
 function aprobarMensaje           (){
  global $conexion;
  $sql = sprintf("UPDATE suscripciones_mensajes SET estadoAprobacion=1 WHERE idSuscripcion=%s AND id=%s;", $_GET['idSuscripcion'], $_GET['idMensaje']);
  $rs = mysql_query($sql, $conexion) or die(register_mysql_error("AM001", mysql_error()));  
  if ($rs == 1){ echo "El mensaje ha sido aprobado"; }
  exit();
 }
 
 function printImagen              (){
  global $conexion;
  $rs = mysql_query(sprintf("SELECT logo_archivo, logo_tipo FROM suscripciones WHERE id=%s;", GetSQLValueString($this->idSuscripcion,"int")), $conexion) or die(register_mysql_error("SI001", mysql_error()));
  $row    = mysql_fetch_assoc($rs);
  $data   = $row[ "logo_archivo" ];
  $type   = $row[ "logo_tipo" ];
  header( "Content-type: $type");
  if ($data == ""){
   $data = readfile("images/spacer.gif", "r");
  }      
  echo $data;
 }
 function printMensajes            (){
  global $conexion;
  global $_CONF;

  // Todos los mensajes pendientes:
  $SQL1          = sprintf("SELECT s.id, s.estado AS status, CASE s.estado WHEN 1 THEN 'Enviado' WHEN 0 THEN 'Pendiente' WHEN -1 THEN 'Eliminado' ELSE 'No Determinado' END AS estado, s.mensaje, s.fecha, s.conteo FROM suscripciones_mensajes s WHERE s.estado=0 AND s.idSuscripcion=%s", $this->idSuscripcion); 
  // Todos los mensajes enviados o eliminados en la bitacora historica; incluyendo solo los del año actual hasta un limite ordenado desde el mas reciente
  $SQL2          = sprintf("%s ORDER BY s.fecha DESC", str_replace("suscripciones_mensajes", "suscripciones_mensajes_bck", str_replace("s.estado=0", "YEAR(fecha)=YEAR(NOW())", $SQL1)));
  // Todos los mensajes eliminados del dia hasta un limite ordenado desde el mas reciente
  $SQL3          = sprintf("%s ORDER BY s.fecha DESC", str_replace("s.estado=0", "s.estado=1", $SQL1));

  $SQL           = sprintf("(%s) UNION (%s) UNION (%s) ORDER BY fecha DESC;", $SQL1, $SQL2, $SQL3, $_CONF['msg_count']); //echo $SQL;
  $rsMensaje     = mysql_query($SQL, $conexion) or die( mysql_error() );  //*/ register_mysql_error("SS008", mysql_error()));
  $cant_mensajes = mysql_num_rows($rsMensaje);

  $html = ""; 
  while ( $row = mysql_fetch_assoc($rsMensaje) ){
   $html .= "<tr><td style='width: 130px'>" . $row['mensaje'] . "</td><td style='width: 140px'>" . $row['fecha'] . "</td><td>" . $row['estado'] . "</td><td style=' text-align: right'>";
   if ($row['status'] == '0'){
    $html .= "<input type='button' class='warning-button' value='Eliminar' onclick='javascript: eliminar(" . $row['id'] . ");' />&nbsp;";
   } else {
    $html .= "<input type='button' class='button' onClick=\"javascript: window.open('?MM_ACTION=detalleEnvio&id=" . $row['id'] . "', 'sorteo','toolbar=0, scrollbars=1,location=0, statusbar=0, menubar=0, resizable=0, width=600, height=600,left=20,top=50');\" value='" . number_format($row['conteo']) . "' />";
   }
   $html .= "</td></tr>";
   $html .= "<tr><td colspan='4'>&nbsp;</td></tr>";
  }

  /*if ($cant_mensajes < $_CONF['msg_count'] ){
   $SQL         = sprintf("SELECT s.id, s.estado AS status, CASE s.estado WHEN 1 THEN 'Enviado' WHEN 0 THEN 'Pendiente' WHEN -1 THEN 'Eliminado' ELSE 'No Determinado' END AS estado, s.mensaje, s.fecha, s.conteo FROM suscripciones_mensajes_bck s WHERE s.idSuscripcion=%s ORDER BY s.id DESC LIMIT %s;", $this->idSuscripcion, $_CONF['msg_count']-$cant_mensajes);
   $rsMensaje2  = mysql_query($SQL, $conexion) or die(register_mysql_error("SS009", mysql_error()));
   while ( $row = mysql_fetch_assoc($rsMensaje2) ){
    $conteo = "<input type='button' class='button' onClick=\"javascript: window.open('?MM_ACTION=detalleEnvio&id=" . $row['id'] . "', 'sorteo','toolbar=0, scrollbars=1,location=0, statusbar=0, menubar=0, resizable=0, width=600, height=600,left=20,top=50');\" value='" . number_format($row['conteo']) . "' />";
    $html .= sprintf("<tr><td>%s</td><td style='width: 140px'>%s</td><td>%s</td><td style=' text-align: right'>%s</td></tr>", $row['mensaje'], $row['fecha'], $row['estado'], $conteo);
    $html .= "<tr><td colspan='4'>&nbsp;</td></tr>";
   }
  }*/
  return $html;   
 }
 function printMensaje             (){
  $mensaje = "";
  if (isset($_GET['alert']) && $_GET['alert'] == 'agregado' ){
   $mensaje = "<script>alert('Mensaje Agregado. Puede continuar');</script>";
  } elseif (isset($_GET['alert']) && $_GET['alert'] == 'ganador' ){
   $mensaje = "<script>alert('Ganador Agregado. Puede continuar');</script>";
  } elseif (isset($_GET['alert']) && $_GET['alert'] == 'limiteMensajesDiarios' ){
   $mensaje = "<script>alert('Se ha alcanzado el limite de mensajes diarios permitidos. El mensaje no ha sido programado');</script>";
  } elseif (isset($_GET['alert']) && $_GET['alert'] == 'fechaAnterior' ){
   $mensaje = "<script>alert('La fecha ingresada no es valida, ya que es en el pasado. El mensaje no ha sido programado');</script>";
  } elseif (isset($_GET['alert']) && $_GET['alert'] == 'longitud' ){
   $mensaje = "<script>alert('La longitud del mensaje excede a la permitida');</script>";
  }
  return $mensaje;
 }
 function printConteoParticipantes (){
  $html =  $this->cntParticipantes;
  return $html;
 }
 function printGanadores           (){
  global $conexion;

  $SQL       = sprintf("SELECT numero FROM suscripciones_ganadores WHERE idSuscripcion=%s AND estado=1;", GetSQLValueString($this->idSuscripcion, "int"));
  $rsNumeros = mysql_query($SQL, $conexion) or die(register_mysql_error("SSG001", mysql_error())); 
  
  $html = "";
  while( $row_rsNumeros = mysql_fetch_assoc($rsNumeros) ){ 
   $html .= "<tr><td style='text-align: center' colspan='3'><font size=+1>" . $row_rsNumeros['numero'] . "</font></td></tr>";
  } 
  return $html;
 }
 function printDetalleGanadores    (){
  global $conexion;
  $sql = sprintf( "SELECT numero, fecha FROM suscripciones_participantes WHERE idSuscripcion=%s ORDER BY numero DESC;", $this->idSuscripcion );
  $rs = mysql_query($sql, $conexion) or die(register_mysql_error("RR005", mysql_error()));
  
  $html = "";
  while ($row = mysql_fetch_assoc($rs)){
   $html .= sprintf("<tr><td align='center'>%s</td><td align='center'>%s</td></tr>", $row['numero'], $row['fecha'] );
  }
  return $html;
 }
 function printManejarMedia        (){
  global $conexion;
  
  $html = "";
  if ( $this->habilitarMedia == 1 ){ 
   $sql = "SELECT id, nombre FROM ws_media ORDER BY nombre";
   $rs = mysql_query($sql, $conexion) or die( register_mysql_error("SM001", mysql_error()) );
   $html .= "
    <table border='0' cellpadding='0' cellspacing='0' width='400px' align='center'>
     <form method='POST'>
      <tr><th colspan='2' scope='col'>Nuevo Mensaje con Media</th></tr>
      <tr><td colspan='2'>&nbsp;</td></tr>
      <tr>
       <td scope='row' style='text-align: right'>Fecha (yy-mm-dd hh:mm:ss):</td>
       <td><input name='fecha' id='fecha' value='" . strftime("%Y-%m-%d %H:%M:%S", time()) . "' /><button id='btn2'> ... </button></td>
      </tr><tr>
       <td scope='row' valign='top' style='text-align: right'>Contenido:</td>
       <td>
        <select name='idMedia'>";
   while ( $row = mysql_fetch_assoc($rs) ){
    $html .= "<option value='" . $row['id'] . "'>" . $row['nombre'] . "</option>";
   }
   $html .= "
        </select>
       </td>
      </tr>
      <tr><td colspan='2'>&nbsp;</td></tr>
      <tr>
       <td colspan='2' scope='row' style='text-align: right'>
        <input id='enviar' name='enviar' class='button' type='submit' value='Agregar' onclick='javascript: return warning();' />
        <input type='hidden' name='MM_ACTION' value='enviarMensajeMedia'>
       </td>
      </tr>
      <tr><td>&nbsp;</td></tr>
     </form>
    </table>";
  }
  return $html;
 }
 function printScriptMedia         (){
  $html = "";
  if ( $this->habilitarMedia == 1 ){
   $html .= "
    Calendar.setup({
     date        : '01/01/2011',
     inputField  : 'fecha2',            // id of the input field
     ifFormat    : '%Y-%m-%d %H:%M:%S', // format of the input field
     showsTime   : false,               // will display a time selector
     button      : 'btn2',              // trigger for the calendar (button ID)
     singleClick : true,                // double-click mode
     showsTime   : true,
     step        : 1                    // show all years in drop-down boxes (instead of every other year as default)
    });";
  }
  return $html;
 }
 function printListado             (){
  global $_CONF;
  global $conexion;
  
  $condition = ($this->numero == "" ) ? "" : sprintf("AND numero LIKE '%%%s'", GetSQLValueString($this->numero,"int") );
  $SQL = sprintf( "SELECT numero, fecha FROM suscripciones_participantes WHERE idSuscripcion=%s AND fecha>=%s AND fecha<=%s %s", $this->idSuscripcion, GetSQLValueString($this->desde,"date"), GetSQLValueString($this->hasta,"date"), $condition); 
  $rsNumeros = mysql_query($SQL, $conexion) or die(register_mysql_error("SL001", mysql_error()));

  $content  = sprintf("<tr><th class='subtitulo' style='text-align: right' colspan='2' >Cantidad: %s</th></tr>", mysql_num_rows($rsNumeros));
  $content .= "<tr><th class='subtitulo' >Numero</th><th class='subtitulo' >Fecha</th></tr>";
  
  while($row_rsNumeros = mysql_fetch_assoc($rsNumeros)) {
   $content .= "<tr><td>" . $row_rsNumeros['numero'] . "</td><td>" . $row_rsNumeros['fecha'] . "</td></tr>";
  }

  if (isset($_CONF['skin']) && $_CONF['skin'] != "") $this->templateListado = "skins/" . $_CONF['skin'] . "/listado.html";
  $html = file_get_contents($this->templateListado);
  $html = str_replace("@@TITLE@@"  , $this->title,$html);
  $html = str_replace("@@LISTADO@@", $content, $html);
  if (isset($_CONF['skin']) && $_CONF['skin'] != "") $html = str_replace("@@SKIN@@", $_CONF['skin'], $html);

  echo $html;
  exit();
 }
 function printSorteo              (){
  $html = file_get_contents($this->templateSorteo);
  if (!isset($_GET['detalle'])){
   $numero = $this->insertarGanador();
   $link = sprintf("<tr><td colsapn='4'><a href='?MM_ACTION=printSorteo&detalle'>Detalle de Ganadores</a></td></tr>");
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
 function printHistorialSorteo     (){
  global $conexion;
  global $_CONF;
  
  $sql = sprintf( "SELECT numero, fecha, desde, hasta FROM suscripciones_ganadores WHERE idSuscripcion=%s ORDER BY fecha DESC;", $this->idSuscripcion );
  $rs = mysql_query($sql, $conexion) or die(register_mysql_error("SR005", mysql_error()));
  
  $contents = "";
  while ($row = mysql_fetch_assoc($rs)){
   $contents .= sprintf("<tr><td align='center'>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>", $row['numero'], $row['fecha'], $row['desde'], $row['hasta'] );
  }
  
  if (isset($_CONF['skin']) && $_CONF['skin'] != "") $this->templateHistorial = "skins/" . $_CONF['skin'] . "/suscripciones/historial.html";

  $html = file_get_contents($this->templateHistorial);
  $html = str_replace("@@TITLE@@",$this->title,$html);
  $html = str_replace("@@CONTENIDO@@",$contents,$html);
  if (isset($_CONF['skin']) && $_CONF['skin'] != "") $html = str_replace("@@SKIN@@", $_CONF['skin'], $html);
  
  echo $html;
  exit();
 } 
 function printDetalleEnvio        (){
  global $conexion;
  global $_CONF;
  
  $sql = sprintf( "SELECT numero, variable1, variable2, variable3, variable4, variable5 FROM suscripciones_participantes p, suscripciones_participantes_mensajes m WHERE p.id=m.idParticipante AND m.idMensaje=%s AND p.idSuscripcion=%s ORDER BY numero;", GetSQLValueString($_GET['id'], id), $this->idSuscripcion );
  $rs = mysql_query($sql, $conexion) or die(register_mysql_error("SD005", mysql_error()));
  //echo $sql;
  
  $contents = "";
  while ($row = mysql_fetch_assoc($rs)){
   $contents .= sprintf("<tr><td align='center'>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>", $row['numero'], $row['variable1'], $row['variable2'], $row['variable3'], $row['variable4'], $row['variable5'] );
  }
  
  if (isset($_CONF['skin']) && $_CONF['skin'] != "") $this->templateEnvio = "skins/" . $_CONF['skin'] . "/suscripciones/envios.html";

  $html = file_get_contents($this->templateEnvio);
  $html = str_replace("@@TITLE@@",$this->title,$html);
  $html = str_replace("@@CONTENIDO@@",$contents,$html);
  if (isset($_CONF['skin']) && $_CONF['skin'] != "") $html = str_replace("@@SKIN@@", $_CONF['skin'], $html);
  
  echo $html;
  exit();
 } 
 
 function printParticipantes       (){
  global $conexion;
  global $_CONF;
  
  $SQL           = sprintf("SELECT p.numero, p.variable1, s.variable1 AS svariable1, p.variable2, s.variable2 AS svariable2, p.variable3, s.variable3 AS svariable3, p.variable4, s.variable4 AS svariable4, p.variable5, s.variable5 AS svariable5 FROM suscripciones s, suscripciones_participantes p WHERE s.id=p.idSuscripcion AND p.idSuscripcion=%s ORDER BY p.numero;", $this->idSuscripcion); 
  $rsMensaje     = mysql_query($SQL, $conexion) or die(register_mysql_error("SS010", mysql_error()));
  $cant_mensajes = mysql_num_rows($rsMensaje);

  $html = ""; $header = ""; $hasHeader = false;
  while ( $row = mysql_fetch_assoc($rsMensaje) ){
   $html .= "<tr><td style='width: 130px'>" . $row['numero'] . "</td>";
   for ($i = 1; $i <= 5; ++$i){
    if (!$hasHeader && trim($row["svariable$i"]) != '') $header .= sprintf("<th class='subtitulo'>%s</th>", $row["svariable$i"]);   
    if (trim($row["svariable$i"]) != '') $html .= "<td>" . $row["variable$i"] . "</td>";
   }
   if (!$hasHeader) $header = sprintf("<tr><th class='subtitulo'>N&uacute;mero</th>%s</tr>", $header);
   $hasHeader = true;
   $html .= "</tr>";
  }
  $html = sprintf("%s%s", $header, $html);
  
  return $html;
 }
 function printButtonVariables     (){
  $html = ""; 
  if ($this->aplicarVariables == 1){
   foreach($this->variables as $key => $value){
    if ($value != ""){
     $html .= "<tr><td><table cellpadding='0' cellspacing='0'>";
     $html .= sprintf("<td><input type='button' class='button' value='%s' onclick='javascript: agregarVariable(\"contenido\", \"@@variable%s@@\")'></td>", $value, $key);
     $html .= sprintf("<td><table cellpadding='0' cellspacing='0'>%s</table></td>", $this->getCondiciones($key));
     $html .= "</table></td></tr>";
    }
   }
   $html = sprintf("<table cellpadding='1' cellspacing='0'>%s</table>", $html);
  }
  return $html;
 }
 function printGraficos            (){
  global $_CONF;

  $g = getdate();
  $m = $g['mon'];
  $y = $g['year'];
  
  $html = "
   <tr><td style='text-align: center' colspan='3'>
    <a href='?MM_ACTION=grafico&height=" . $_CONF['bargraph-expanded-height']. "&width=" . $_CONF['bargraph-expanded-width']. "&month=$m&anio=$y&quincenal'>
     <img src='?MM_ACTION=grafico&height=300&width=400&month=$m&anio=$y&quincenal' border='1' align='top'/>
    </a>
   </td></tr>";
  $html .= "
   <tr><td colspan='3'>&nbsp;</td></tr>
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
     <tr><td colspan='3'>&nbsp;</td></tr>
     <tr><td colspan='3' style='text-align: right'><input type='button' class='button' value='Mostrar' onClick='this.form.submit()' /></td></tr>
     <tr><td colspan='3' >&nbsp;</td></tr>
    </form>";
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
   
  $html .= "
   <tr><td style='text-align: right' colspan='3'>
    <a href='?MM_ACTION=grafico&height=" . $_CONF['bargraph-expanded-height']. "&width=" . $_CONF['bargraph-expanded-width']. "&month=$m&anio=$y'>
     <img src='?MM_ACTION=grafico&height=240&width=320&month=$m&anio=$y' border='1' align='top'/>
    </a>
   </td></tr>";
  $html .= "</table>
     </td>
    </tr>";
  return $html;
 }
 function printGrafico             (){
  global $conexion;
  global $_CONF;
  
  $title = ""; 
  $this->desdeInicioMes  = (isset($_GET['month']) && isset($_GET['anio'])) ? $_GET['anio'] . "-" . $_GET['month'] . "-01" : date("Y-m-01");
  $this->desdeQuincena   = (isset($_GET['month']) && isset($_GET['anio'])) ? date('Y-m-d 23:59:59', strtotime('-1 second', strtotime('+15 day', strtotime($_GET['month'] . '/01/' . $_GET['anio'] .' 00:00:00')))) : date('Y-m-d 23:59:59', strtotime('-1 second', strtotime('+15 day', strtotime(date('m') . '/01/' . date('Y') .' 00:00:00'))));

  $this->hasta           = (isset($_GET['month']) && isset($_GET['anio'])) ? date('Y-m-d 23:59:59', strtotime('-1 second', strtotime('+1 month', strtotime($_GET['month'] . '/01/' . $_GET['anio'] .' 00:00:00')))) : date('Y-m-d 23:59:59', strtotime('-1 second', strtotime('+1 month', strtotime(date('m') . '/01/' . date('Y') .' 00:00:00'))));
  $operador = (intval(date('d') <= 15 && isset($_GET['quincenal']))) ? "<" : ">";
  
  $sql   = sprintf("SELECT '%s' AS color, DayOfMonth(fecha) AS descripcion, SUM(conteo) AS cantidad FROM suscripciones_estadisticas WHERE idSuscripcion=%s AND fecha>=%s AND fecha%s=%s AND fecha<%s GROUP BY DayOfMonth(fecha)", $_CONF['bargraph-color'], $this->idSuscripcion, GetSQLValueString($this->desdeInicioMes, "date"),  $operador, GetSQLValueString(((isset($_GET['quincenal'])) ? $this->desdeQuincena : $this->desdeInicioMes), "date"), GetSQLValueString($this->hasta, "date"));
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
  $font = $width / 320 * 3;

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
  $bplot->value->SetFont(FF_FONT1,FS_NORMAL, $font);

  $bplot->SetValuePos('top');
  $bplot->SetWidth(0.7);

  $bplot->SetFillColor( $colors );
  $bplot->SetColor("white");

  $graph->Add($bplot);
  $graph->Stroke();
 }
 function printCargaExcel          (){
  return '<table cellpadding="0" cellspacing="0" border="0" width="400px" align="center">
    <form method="POST" enctype="multipart/form-data">
     <tr><th colspan="2">Base de Datos</th></tr>
     <tr><td>&nbsp;</td></tr>
     <tr>
      <td>Descargar Archivo Actual:</td><td><a href="?MM_ACTION=descargarExcel" />Descargar Archivo</a></td>
     </tr>
     <tr><td>&nbsp;</td></tr>
     <tr>
      <td>Cargar Archivo:</td><td><input id="fileExcel" type="file" name="fileExcel" onchange="validarFile()"></td>
     </tr>
     <tr><td>&nbsp;</td></tr>
     <tr><td colspan="2" style="text-align: right">
      <input id="cargar" name="cargar" type="button" class="button" onClick="javascript: if ( confirm(\'¿Desea agregar los registros a la base de datos?\')) this.form.submit()" value="Cargar" />
      <input type="hidden" name="MM_ACTION" value="cargarExcel">
     </td></tr>
    </form>
    </table><br/>';
 }
 
 function printLogin               (){
  global $_CONF;
  if (isset($_CONF['skin']) && $_CONF['skin'] != "") $this->templateLogin = "skins/" . $_CONF['skin'] . "/login.html";
  
  $html = file_get_contents($this->templateLogin);
  $html = str_replace("@@TITLE@@",$this->title,$html);
  echo $html;
  exit();
 }
 function getCondiciones           ($x){
  global $conexion;
  global $database_conexion;
  $variable = "";
  $query = sprintf("SELECT variable%s FROM suscripciones WHERE id=%s;", $x, GetSQLValueString($_SESSION['idSuscripcion'],"int"));
  mysql_select_db($database_conexion, $conexion);
  $rs = mysql_query($query, $conexion);
  $row = mysql_fetch_array($rs);
  $variable = $row[0];
  $resultado = sprintf("
  <tr>
   <td><select name='operSelect%s' id='operSelect%s' style='width: 40px'/>@@opciones1@@</select></td>
   <td><select name='varSelect%s' id='varSelect%s'>@@opciones@@</select></td>
   <td><input type='button' class=\"date-button\" value='+' onclick='agregarCondicion(\"varSelect%s\",\"operSelect%s\",\"condicion%s\",\"condHidden%s\",\"variable%s\");' /></td>
  </tr><tr>
   <td colspan='2'>
    <input type='text' readonly='readonly' id='condicion%s' />
    <input type='hidden' name='condHidden%s' id='condHidden%s' />
    <input type='hidden' name='condHiddenVar%s' id='variable%s' value = '%s'/>
   </td>
   <td><input type='button' class=\"date-button\" value='X' onclick='limpiarCondicion(\"condicion%s\",\"condHidden%s\")'/></td>
  </tr>", $x, $x, $x, $x, $x, $x, $x, $x, $x, $x, $x, $x, $x, $x, $variable, $x, $x);
 
  $opcionesVar = "";
  $query = sprintf('SELECT DISTINCT TRIM(variable%s) FROM suscripciones_participantes WHERE idsuscripcion=%s AND variable%s IS NOT NULL AND LENGTH(TRIM(variable%s)) > 0 AND estado=1 ORDER BY variable%s;', $x, $_SESSION['idSuscripcion'], $x, $x, $x);

  $cont = 1;
  $rs = mysql_query($query,$conexion);
  while($row = mysql_fetch_array($rs)){
   $opcionesVar .= sprintf("<option value='%s'>%s</option>", $row[0], $row[0]);
   if(!is_numeric($row[0])){
    $cont = 0;
   }
  }
 
  $resultado = str_replace("@@opciones@@", $opcionesVar, $resultado);
  $resultado = ($cont > 0 ) ? str_replace("@@opciones1@@","<option value='='>=</option><option value='>'>></option><option value='>='>>=</option><option value='<'><</option><option value='<='><=</option>",$resultado) : str_replace("@@opciones1@@", "<option value='='>=</option>", $resultado);
  return $resultado."</br>";
 }
 function printDetail              (){
  global $conexion;
  global $_CONF;

  /* Datos Generales */
  $SQL      = sprintf( "SELECT COUNT(*) AS conteo FROM suscripciones_participantes WHERE estado=1 AND idSuscripcion=%s;", GetSQLValueString($this->idSuscripcion, "int"));
  $rsp      = mysql_query($SQL, $conexion) or die(register_mysql_error("SSG002", mysql_error()));
  $row      = mysql_fetch_array( $rsp );            

  $this->cntParticipantes = $row['conteo'];
  
  if (isset($_CONF['skin']) && $_CONF['skin'] != "") $this->templateHTML = "skins/" . $_CONF['skin'] . "/suscripciones.html";
  $html = file_get_contents($this->templateHTML);
  $html = str_replace("@@CONTEOPARTICIPANTES@@",$this->printConteoParticipantes(),$html);
  $html = str_replace("@@TITLE@@"       , $this->title,$html);
  $html = str_replace("@@MAXLENGTH@@"   , $_CONF['msg_max_length'], $html);
  $html = str_replace("@@WARNLENGTH@@"  , $_CONF['msg_warning_length'], $html);
  $html = str_replace("@@MENSAJE@@"     , $this->printMensaje(), $html);
  $html = str_replace("@@VARIABLES@@"   , $this->printButtonVariables(), $html);
  $html = str_replace("@@SHORTCODE@@"   , $this->shortCode,$html);
  $html = str_replace("@@FECHAENVIO@@"  , strftime("%Y-%m-%d %H:%M:%S",time()),$html);
  $html = str_replace("@@MEDIA@@"       , $this->printManejarMedia(), $html);
  $html = str_replace("@@GANADORES@@"   , $this->printGanadores(),$html);
  $html = str_replace("@@DESDE@@"       , $this->desde,$html);
  $html = str_replace("@@HASTA@@"       , $this->hasta,$html);
  $html = str_replace("@@NUMERO@@"      , $this->numero,$html);
  $html = str_replace("@@MENSAJES@@"    , $this->printMensajes(), $html);
  $html = str_replace("@@SCRIPTMEDIA@@" , $this->printScriptMedia(), $html);
  $html = str_replace("@@GRAFICOS@@"    , $this->printGraficos(),$html);
  $html = str_replace("@@CARGAEXCEL@@"  , $this->printCargaExcel(),$html);
  if (isset($_CONF['skin']) && $_CONF['skin'] != "") $html = str_replace("@@SKIN@@", $_CONF['skin'], $html);
    
  echo $html;
 }
}