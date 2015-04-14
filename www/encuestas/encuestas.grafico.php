<?php
require_once ("../connections/conexion.php"); 
require_once ('../configuracion/parametros.php');

$title    = "Encuestas";
$initPage = "encuestas.html";

session_start();
if (!isset($_SESSION['idEncuesta'])){
 header(sprintf("Location: %s", $initPage));
 exit();
}
$id = $_SESSION[ 'idEncuesta' ];

mysql_select_db($database_conexion, $conexion);
$result = mysql_query("SELECT pregunta, wallpaper FROM encuestas WHERE id=$id", $conexion) or die(mysql_error());
$row   = mysql_fetch_assoc($result);
$title = $row['pregunta'];

if ( isset($_GET['frame']) ){
 $wallpaper = "";
 if ($row['wallpaper'] != "") $wallpaper = $SURVEY_WALL . $row['wallpaper'];

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
 <html xmlns="http://www.w3.org/1999/xhtml">
  <head>
   <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
   <meta http-equiv="Refresh" content="60">
   <link rel="stylesheet" type="text/css" href=../"css/style.css" />
   <style>
	#outer { position: relative; text-align: center; height: 400px; width: 100%  }
	#inner { position: absolute; top: 50%; text-align: center; width: 100% }
    <?php if ($wallpaper != ""){ ?>body { background-image: url(<?php echo $wallpaper; ?>); background-repeat: repeat; background-attachment: fixed; } <?php } ?>
   </style>
   <title><?php echo $title ?></title>
  </head>
  <body>
   <div id="outer">
    <div id="inner">
     <center>
      <table width="640" cellpadding="0" cellspacing="0">
       <tr>
        <th style="font-size: 24px; color: #000000; background:#FFF; border: 1px #000 solid"><?php echo $title; ?></th>
       </tr>
       <tr><td><img src="encuestas.grafico.php" border="0" /></td></tr>
       <tr><td>&nbsp;</td></tr>
      </table>
     </center>
     <br/><br/><br/><br/><br/>
    </div>
   </div>
  </body>
 </html><?php
 exit();
}

require_once ("../jpgraph/src/jpgraph.php");
require_once ("../jpgraph/src/jpgraph_bar.php");

$result = mysql_query("SELECT SUM(cantidad) AS c FROM encuestas_opciones WHERE idEncuesta=$id AND descripcion<>''", $conexion) or die( mysql_error() );
$row   = mysql_fetch_assoc($result);
$total = $row['c'];

$resl = mysql_query("SELECT descripcion, cantidad, color FROM encuestas_opciones WHERE idEncuesta=$id AND descripcion<>''", $conexion) or die(mysql_error());

$i  = 0; $colors = array();
while ($row = mysql_fetch_assoc($resl)){
 $datax[$i]  = $row['descripcion'];
 $datay[$i]  = ( $total > 0 ) ? ( $row['cantidad'] / $total * 100 ) : 0;
 $colors[$i] = "#" . trim( $row['color'] );
 ++$i;
}

$dataxR=array(); $datayR=array();
foreach ( $datax as $key => $index ){
 $dataxR[sizeof($dataxR)] = $index; 
}
foreach ( $datay as $key => $index )
 $datayR[sizeof($datayR)] = $index;

$width  = ( isset( $_GET['width'] )) ? $_GET['width'] : 640;
$height = ( isset( $_GET['height'] )) ? $_GET['height'] : 480;
$font = $width / 320 * 4;
$marginFac = $width / 640;

$graph = new Graph($width,$height,"auto");     
$graph->SetScale("textlin");

$graph->img->SetMargin(45 * $marginFac, 5, 40 * $marginFac, 145 * $marginFac);
$graph->SetMarginColor('white');
$graph->xaxis->SetFont(FF_VERDANA,FS_NORMAL,$font);
$graph->yaxis->SetFont(FF_VERDANA,FS_NORMAL,$font);
$graph->xaxis->SetLabelMargin(25);
$graph->xaxis->SetLabelAngle(90);

$graph->xaxis->SetTickLabels($dataxR);

$graph->xaxis->title->SetFont(FF_VERDANA,FS_NORMAL, $font);
$graph->yaxis->title->SetFont(FF_VERDANA,FS_NORMAL, $font);
$graph->xaxis->title->Set("");
$graph->yaxis->title->Set("");

$bplot = new BarPlot($datay);

$bplot->value->SetFormat("%d%%");
$bplot->value->SetFont(FF_VERDANA,FS_NORMAL,$font);
$bplot->value->Show();

$bplot->SetValuePos('top');
$bplot->SetWidth(0.7);


$graph->Add($bplot);
$bplot->SetFillColor( $colors );

$graph->Stroke();

?>