<?php
 require_once('../conf.php');
 require_once("../connections/conexion.php");
 require_once("../functions/db.php");
 require_once('../functions/functions.php');
 require_once('../lib/jpgraph/src/jpgraph.php');
 require_once('../lib/jpgraph/src/jpgraph_bar.php');
 
 class clasificacionesHTML {
  public $_CONFHTML = array (
   //Arreglo de configuracion del script
   'title'                 => 'Categorizacion de Códigos',
   'skin'                  => 'tecmovil',
  );
 
  // Propiedades
  private $desde = "";
  private $hasta = "";
 
  function clasificacionesHTML(){
   session_start();
   $this->registerVariables();
 
   if (isset($_POST['login'])){
    $html = $this->doLogin();    
   } elseif (isset($_POST['MM_ACTION']) && $_POST['MM_ACTION'] == 'logout'){
    $html = $this->doLogout();    
   } elseif (isset($_GET['MM_ACTION']) && $_GET['MM_ACTION'] == 'grafico'){
    $html = $this->printGrafico($_GET['tipo']);    
   } elseif (isset($_SESSION['username'])) {
     $html = $this->printBody();
   } else {
    $html = $this->printLogin();
   }
   echo $html; 
  }
 
  function registerVariables(){
   $this->desde = (isset($_POST['desde'])) ? $_POST['desde'] : ((isset($_SESSION['desde'])) ? $_SESSION['desde'] : date("Y-m-d 00:00:00"));
   $this->hasta = (isset($_POST['hasta'])) ? $_POST['hasta'] : ((isset($_SESSION['hasta'])) ? $_SESSION['hasta'] : date("Y-m-d 23:59:59"));
   $_SESSION['desde'] = $this->desde;
   $_SESSION['hasta'] = $this->hasta;
  }
  function doLogout(){
   session_destroy();
   $html = $this->printLogin();
   echo $html;
  }
  function doLogin(){
   if ($this->isValid()){
    $_SESSION['username'] = $_POST['username'];
    return $this->printBody();
   }
   return $this->printLogin();
  }
  function isValid(){
   global $conexion;
   global $database_conexion;   

   $sql = sprintf("SELECT COUNT(*) AS conteo, MAX(id) AS idRecarga FROM ac_recargas WHERE usuario=%s AND clave=%s;", GetSQLValueString($_POST['username'], "text"), GetSQLValueString($_POST['password'], "text"));

   mysql_select_db($database_conexion, $conexion);
   $rs  = mysql_query($sql) or die(register_mysql_error("KI0001", mysql_error()));
   if ($row = mysql_fetch_array($rs)){
    if ($row['conteo'] == 1){
     $_SESSION['idRecarga'] = $row['idRecarga'];
     return true;
    }
   }
   return false;
  }
 
  function printLogin(){
   $html = file_get_contents(sprintf("../skins/%s/login.html", $this->_CONFHTML['skin']));
   $html = str_replace("skins/", "../skins/",$html);
   $html = str_replace("@@TITLE@@",$this->_CONFHTML['title'],$html);
   return $html;
  }
  function printBody (){
   $html = file_get_contents("body.html");
   $html = str_replace("@@TITLE@@", $this->_CONFHTML['title'],$html);
   $html = str_replace("@@DESDE@@", $this->desde, $html);
   $html = str_replace("@@HASTA@@", $this->hasta, $html);
   return $html;
  }
  function getClasificacion( $clasificacion, $tipo ){
   global $conexion;
   global $database_conexion;   
   global $_CONF;
   mysql_select_db($database_conexion, $conexion);

   $sql = sprintf("SELECT nombre FROM ac_recargas_codigos_clasificaciones WHERE clasificacion=%s AND idRecarga=%s", GetSQLValueString($clasificacion, "text"), $_SESSION['idRecarga']);
   $result = mysql_query($sql, $conexion) or die(mysql_error());
   if ($row = mysql_fetch_assoc($result)){
    return $row['nombre'];
   }
   return $clasificacion;
  }
  function printGrafico($clasificacion = 1, $title = ""){
   global $conexion;
   global $database_conexion;   
   global $_CONF;

   $colors = array();
   $datax = array(); $datay = array();

   $i = 0;
   $num_rifas_h = 0;

   $sql   = sprintf( "SELECT c.clasificacion%s, c.clasificacion%s AS descripcion, COUNT(*) AS cantidad, '#%s' AS color FROM ac_recargas_participantes p, ac_recargas_codigos c WHERE p.idRecarga=c.idRecarga AND p.codigo=c.codigo AND c.idRecarga=%s AND p.fecha_codigo>=%s AND p.fecha_codigo<=%s GROUP BY c.clasificacion%s, c.clasificacion%s ORDER BY COUNT(*) DESC;", $clasificacion, $clasificacion, $_CONF['bargraph-color'], $_SESSION['idRecarga'], GetSQLValueString($this->desde, "date"), GetSQLValueString($this->hasta, "date"), $clasificacion, $clasificacion); 
   
   mysql_select_db($database_conexion, $conexion);
   $result = mysql_query($sql, $conexion) or die(mysql_error());
   while ($row = mysql_fetch_assoc($result)){
    $nombreClasificacion = $this->getClasificacion($row['descripcion'], $clasificacion);
    $index = -1;
    foreach ($datax as $key => $value){
     if ($value == $nombreClasificacion)
      $index = $key;
    }
    if ($index == -1){ 
     $index = $i;
     ++$i;
    }
    if (true){
     $datax[$index]   = $nombreClasificacion;
     if (!isset($datay[$index])) $datay[$index] = 0;
     $datay[$index]   += $row['cantidad'];
     $colors[$index]  = $row['color'];
     $num_rifas_h = $num_rifas_h  + $row['cantidad'];
    }
   }

   if ( $i == 0){
    $datax [$i] = "";
    $datay [$i] = 0;
   }

   $dataxR=array(); $datayR=array();
   foreach ( $datax as $key => $index ){
       $dataxR[] = $index; 
   }    
  
   foreach ( $datay as $key => $index ){
       $datayR[] = $index;
   }

   $width  = ( isset( $_GET['width'] )) ? $_GET['width'] : 800;
   $height = ( isset( $_GET['height'] )) ? $_GET['height'] : 600;
   $font = $width / 320 * 3;

   $graph = new Graph($width,$height,"auto");     
   $graph->SetScale("textlin");
   $graph->img->SetMargin(20, 20, 20, intval($height / 4));
   $graph->SetMarginColor('white');
   $graph->xaxis->SetFont(FF_VERDANA,FS_NORMAL,$font);
   $graph->yaxis->SetFont(FF_VERDANA,FS_NORMAL,$font);
   $graph->yaxis->SetLabelMargin(25);
   $graph->xaxis->SetLabelMargin(25);
   $graph->xaxis->SetLabelAngle(0);

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
 }
 
 ini_set("display_errors", 1);
 $k = new clasificacionesHTML();