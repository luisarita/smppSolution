<?php

include( 'connections/conexion.php' );
include( 'configuracion/parametros.php');
include( 'conf.php');
include( 'functions/functions.php');

if( !$conexion ){
 print error(3);
 exit(0);
}

set_time_limit(0);

mysql_select_db( $database_conexion, $conexion );
$id = $_GET['id'];

$sql = sprintf("SELECT m.path, m.tipo, m.id FROM ws_media m, ws_compras_msg cm WHERE cm.id_media=m.id AND cm.code=%s AND cm.fechaexp>=NOW();", GetSQLValueString($id, "text"));
$rst = mysql_query($sql, $conexion) or die();

if( !$rst ){
 echo 'Esta descarga no ha sido encontrada o ha vencido';
} else if( mysql_num_rows($rst) < 1 ){
 echo 'Esta descarga ha vencido';
} else {
 $row = mysql_fetch_assoc( $rst );
 $filename = $row['path'];
 $l = $row['path'];
 $fs = filesize(  $l );

 header( "Accept-Ranges: bytes");
 header( "Content-Length: " . $fs );
 header( "Content-Type: " . get_type($filename));

 if( strcmp($_SERVER['REQUEST_METHOD'], 'HEAD') != 0 ){
    $i = readfile($l);
 }
}

mysql_close($conexion);
exit(0);