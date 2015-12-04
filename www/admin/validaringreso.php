<?php

require_once('../connections/conexion.php');
$usuario_Recordset1 = "-1";
if (isset($_POST['usuario'])) {
    $usuario_Recordset1 = (get_magic_quotes_gpc()) ? $_POST['usuario'] : addslashes($_POST['usuario']);
}
$clave_Recordset1 = "-1";
if (isset($_POST['clave'])) {
    $clave_Recordset1 = (get_magic_quotes_gpc()) ? $_POST['clave'] : addslashes($_POST['clave']);
}

$sql = sprintf("SELECT id, usuario FROM mantenimiento_usuarios WHERE usuario='%s' AND clave=MD5('%s') AND activo=1", $usuario_Recordset1, $clave_Recordset1);
$rs = mysql_query($sql, $conexion) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($rs);
$totalRows_Recordset1 = mysql_num_rows($rs);
echo $totalRows_Recordset1;

if ($totalRows_Recordset1 == 0) {
    $insertGoTo = "index.html";
    header(sprintf("Location: %s", $insertGoTo));
} else {
    session_start();
    $_SESSION['idAdmin'] = $row_Recordset1['id'];
    $_SESSION['usuario'] = $row_Recordset1['usuario'];
    $insertGoTo = "menu.php";
    header(sprintf("Location: %s", $insertGoTo));
}