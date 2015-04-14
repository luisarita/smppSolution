<?php
/*
larita - 2009-07-26
 - se hizo cambios para compatibilidad con fechas y horas








*/

require_once ('../connections/conexion.php');
include ('../lib/jpgraph/src/jpgraph.php');
include ('../lib/jpgraph/src/jpgraph_bar.php');


/* Parametros: */
$initPage = "/telechat.html";



session_start();
if (!isset($_SESSION['idTelechat'])){
 header("Location: " . $initPage); exit();
}

$idTelechat = $_SESSION['idTelechat'];



require_once('../connections/conexion.php'); 
mysql_select_db($database_conexion, $conexion);

function DaysInMonth ( $Year, $MonthInYear ){
 if ( in_array ( $MonthInYear, array ( 1, 3, 5, 7, 8, 10, 12 ) ) )
  return 31; 
 if ( in_array ( $MonthInYear, array ( 4, 6, 9, 11 ) ) )
 return 30; 
 if ( $MonthInYear == 2 )
  return ( checkdate ( 2, 29, $Year ) ) ? 29 : 28;
 return false;
}

$mes = $_GET['mes'];
$anio = $_GET['anio'];

$mes2 = $mes % 12 + 1;
$anio2 = $mes < 12 ? $anio : $anio + 1;	

mysql_select_db($database_conexion, $conexion);
$query_Recordset1 = "SELECT DAY(fecha) AS fecha, COUNT(*) AS conteo FROM telechats_mensajes WHERE fecha >= '$anio-$mes-01' AND fecha < '$anio2-$mes2-01' AND idTelechat=$idTelechat AND numero <> '5049999999' GROUP BY DAY(fecha) ORDER BY DAY(fecha)";

$Recordset1 =mysql_query($query_Recordset1, $conexion) or die( mysql_error());

$num = mysql_num_rows($Recordset1);

$dx = array();
$dy = array();

$dm = DaysInMonth( $anio, $mes );

for( $i = 0; $i < $dm + 1; $i++ ){
 $dx[$i] = $i;
 $dy[$i] = 0;
}

$sum = 0;
for( $i = 0; $i < $num; ++$i){
 $r = mysql_fetch_array($Recordset1);
 $dy[$r[0]] = $r[1];
 $sum += $r[1];
}

$datay=array(12,8,1,3,10,5);

// Create the graph. These two calls are always required
$graph = new Graph(900,200,"auto");    
$graph->SetScale("textlin");

// Add a drop shadow
$graph->SetShadow();

$graph->xaxis->SetTickLabels($dx);

// Adjust the margin a bit to make more room for titles
$graph->img->SetMargin(40,30,20,40);

// Create a bar pot
$bplot = new BarPlot($dy);

// Adjust fill color
$bplot->SetFillColor('orange');
$graph->Add($bplot);

// Setup values
$bplot->value->Show();
$bplot->value->SetFormat('%d');
$bplot->value->SetFont(FF_FONT1,FS_BOLD);

// Center the values in the bar
$bplot->SetValuePos('center');
$bplot->SetWidth(0.7);

// Setup the titles
switch( $mes ){
 case 1: $m = 'Enero'; break;
 case 2: $m = 'Febrero'; break;
 case 3: $m = 'Marzo'; break;
 case 4: $m = 'Abril'; break;
 case 5: $m = 'Mayo'; break;
 case 6: $m = 'Junio'; break;
 case 7: $m = 'Julio'; break;
 case 8: $m = 'Agosto'; break;
 case 9: $m = 'Septiembre'; break;
 case 10: $m ='"Octubre'; break;
 case 11: $m = 'Noviembre'; break;
 case 12: $m = 'Diciembre'; break;
}

$graph->title->Set("Grafica para Mes de $m");
$graph->xaxis->title->Set("Dia");
$graph->yaxis->title->Set("Numero de Mensajes");

$graph->title->SetFont(FF_FONT1,FS_BOLD);
$graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD);
$graph->xaxis->title->SetFont(FF_FONT1,FS_BOLD);

$bplot->SetLegend(" Total: " . $sum);
$graph->legend->SetLayout(LEGEND_HOR);

// Display the graph
$graph->Stroke();
?>