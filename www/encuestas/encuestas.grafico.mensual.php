<?php
require_once('../connections/conexion.php');
require_once('../functions/date.php');
require_once('../functions/db.php');
require_once('../jpgraph/src/jpgraph.php'); 
require_once('../jpgraph/src/jpgraph_bar.php'); 

session_start();
if (!isset($_SESSION['idEncuesta'])){
 header(sprintf("Location: %s", $initPage));
 exit();
} 

$idEncuesta=$_SESSION['idEncuesta'];

$mes  = $_GET['mes'];
$anio = $_GET['anio'];

$mes2  = $mes % 12 + 1;
$anio2 = $mes < 12 ? $anio : $anio + 1;     

mysql_select_db($database_conexion, $conexion);
$sql = "SELECT DAY(fecha) AS fecha, COUNT(*) AS conteo FROM encuestas_participantes WHERE fecha >= '$anio-$mes-01' AND fecha < '$anio2-$mes2-01' AND idEncuesta=$idEncuesta GROUP BY DAY(fecha) ORDER BY DAY(fecha)"; 
$rs = mysql_query($sql, $conexion) or die( register_mysql_error("EG001", mysql_error()));
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

$datay = array ( 12, 8, 1, 3, 10, 5);

$width  = ( isset( $_GET['width'] ))  ? $_GET['width']  : 640;
$height = ( isset( $_GET['height'] )) ? $_GET['height'] : 480;
$font = $width / 320 * 6;
$marginFac = $width / 640;

$graph = new Graph( $width, $height, "auto");    
$graph->SetScale("textlin");

$graph->xaxis->SetTickLabels($dx);
$graph->img->SetMargin(45 * $marginFac, 5, 100 * $marginFac, 45 * $marginFac);
$graph->SetMarginColor('white');

$bplot = new BarPlot($dy);
$bplot->SetFillColor('orange');
$graph->Add($bplot);
$bplot->value->Show();
$bplot->value->SetFormat('%d');
$bplot->value->SetFont(FF_FONT1,FS_BOLD);
$bplot->SetWidth(0.7);

/*$graph->title->Set("Grafica para Mes de " . getMes($mes));
$graph->xaxis->title->Set("Dia");
$graph->yaxis->title->Set("Mensajes");*/

$graph->title->SetFont(FF_FONT1,FS_BOLD);
$graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD);
$graph->xaxis->title->SetFont(FF_FONT1,FS_BOLD);

$bplot->SetLegend("Mes: " . $sum);
$graph->legend->Pos( 0.02, 0.1, "right", "center");
$graph->legend->SetLayout(LEGEND_VERT);

$graph->Stroke();
?>