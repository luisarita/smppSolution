<?php
require_once('functions.php');
require_once('../connections/conexion.php');

session_start();
if (!isset($_SESSION['idAdmin'])) {
    header("Location: " . initPage());
    exit();
} else if (!permission(16)) {
    header("Location: " . initPage());
    exit();
}

$id = intval($_GET['id']);
if (isset($_POST["accion"]) && $_POST["accion"] == "guardar") {
    mysql_query("DELETE FROM mantenimiento_permisos WHERE usuario=$id ", $conexion) or die(mysql_error());
    if (isset($_POST['opciones'])) {
        $selec_opciones = $_POST['opciones'];
        for ($i = 0; $i < sizeof($selec_opciones); ++$i) {
            $result = mysql_query("INSERT INTO mantenimiento_permisos (usuario,permiso) VALUES ($id," . $selec_opciones[$i] . ")", $conexion) or die(mysql_error());
        }
    }
}

$res_opcion = mysql_query("SELECT usuario FROM mantenimiento_usuarios WHERE id=" . $id, $conexion);
$row_opcion = mysql_fetch_array($res_opcion);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <meta http-equiv="Refresh">
            <title>Permisos</title>
            <link rel="stylesheet" type="text/css" href="../css/style.css" />
            <script language="javascript">
                function validar(forma) {
                    return true;
                }
            </script>
    </head>   
    <body> 
        <form name="form1" method="post" action="?id=<?php echo $id; ?>">
            <table width="220px" cellpadding="0" cellspacing="0">
                <tr><th colspan="2">Mantenimiento de Permisos</th></tr>
                <tr><td colspan="2">&nbsp;</td></tr>     
                <tr>
                    <td>Usuario:</td>  
                    <td><?php echo (isset($row_opcion['usuario'])) ? $row_opcion['usuario'] : ''; ?></td>
                </tr>
                <tr><td colspan="2">&nbsp;</td></tr>     
                <tr>
                    <th>Opci&oacute;n</th>
                    <th>&nbsp;</th>
                </tr>
                <tr><td colspan="2">&nbsp;</td></tr><?php
$res_opciones = mysql_query("SELECT a.id, a.opcion, (SELECT COUNT(DISTINCT b.usuario) FROM mantenimiento_permisos b WHERE a.id=b.permiso AND b.usuario=$id) AS permiso FROM mantenimiento_opciones a ORDER BY id", $conexion) or die(mysql_error());
while ($row_opciones = mysql_fetch_array($res_opciones)) {
    ?><tr>
                        <td><?php echo $row_opciones['opcion']; ?></td>
                        <td style="text-align: center">
                            <input class="checkbox" type="checkbox" name="opciones[]" <?php if (isset($row_opciones['permiso']) && $row_opciones['permiso'] == 1) echo "checked "; ?> value="<?php echo $row_opciones['id']; ?>"  />
                        </td>
                    </tr><?php }
?>
                <tr><td colspan="3" style="text-align: right">&nbsp;</td></tr>
                <tr><td colspan="3" style="text-align: right">
                        <input type="submit" class="button" name="Submit" value="Guardar Datos" onclick="return validar(this.form)" />
                        <input type="hidden" name="accion" value="guardar">
                    </td></tr>
                <tr><td colspan="2">&nbsp;</td></tr>
                <tr><th colspan="3" style="text-align: center"><a href="menu.php">Menu</a></th></tr>   
            </table>
        </form>
    </body>
</html>