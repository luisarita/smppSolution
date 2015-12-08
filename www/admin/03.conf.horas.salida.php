<?php
require_once('../connections/conexion.php');
require_once('functions.php');
require_once('../conf.php');

session_start();
if (!isset($_SESSION['idAdmin'])) {
    header("Location: " . initPage());
    exit();
} else if (!permission(3)) {
    header("Location: " . initPage());
    exit();
}

if (isset($_POST['inicio']) && isset($_POST['final'])) {
    $sql = "UPDATE mantenimiento_parametros SET valor='" . $_POST['inicio'] . "' WHERE id=4";
    mysql_query($sql) or die(mysql_error());
    $sql = "UPDATE mantenimiento_parametros SET valor='" . $_POST['final'] . "' WHERE id=5";
    mysql_query($sql) or die(mysql_error());
}

$sql = "SELECT valor FROM mantenimiento_parametros WHERE id=4";
$rs = mysql_query($sql, $conexion) or die(mysql_error());
$row = mysql_fetch_array($rs);
$inicio = $row['valor'];

$sql = "SELECT valor FROM mantenimiento_parametros WHERE id=5";
$rs = mysql_query($sql, $conexion) or die(mysql_error());
$row = mysql_fetch_array($rs);
$final = $row['valor'];
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Administraci&oacute;n</title>
        <link type="text/css" rel="stylesheet" href="../css/style.css"  />
    </head>

    <body>
        <form id="formMantenimiento" name="formMantenimiento" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <table>
                <tr>
                    <th colspan="2">Configuraci&oacute;n de Horarios de Suscripciones</th>
                </tr>
                <tr>
                    <td class="noborder">Hora de Inicio:</td>
                    <td class="noborder"><input type="text" name="inicio" id="inicio" class="w100" value="<?php echo $inicio ?>" maxlength="2" /></td>
                </tr>
                <tr>
                    <td class="noborder">Hora Final:</td>
                    <td class="noborder"><input type="text" name="final" id="final" class="w100" value="<?php echo $final ?>" maxlength="2" /></td>
                </tr>
                <tr><td colspan="2" class="controls noborder"><input type="submit" name="button" id="button" value="Salvar" /></td></tr>
                <tr><th colspan="2" style="text-align: center"><a href="menu.php">Menu</a></th></tr>   
            </table>
        </form>
    </body>

</html><?php
mysql_free_result($rs);
