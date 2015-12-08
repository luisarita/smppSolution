<?php
require_once('../connections/conexion.php');
require_once('../functions/functions.php');
require_once('../functions/db.php');
require_once('functions.php');

session_start();
if (!isset($_SESSION['idAdmin'])) {
    header("Location: " . initPage());
    exit();
} else if (!permission(33)) {
    header("Location: " . initPage());
    exit();
}

ini_set("max_execution_time", 0);

if (isset($_POST['action']) && isset($_POST['mensaje'])) {
    if (strlen($_POST['mensaje']) > 1) {
        if ($_POST['action'] == 'Priorizar') {
            $SQL = sprintf("UPDATE mensajes_pendientes SET prioridad=(IF(fuente=0,1,1)) WHERE mensaje=%s AND numero_salida=%s AND estado=0;", GetSQLValueString($_POST['mensaje'], "text"), GetSQLValueString($_POST['numero_salida'], "text"));
        } else if ($_POST['action'] == 'Despriorizar') {
            $SQL = sprintf("UPDATE mensajes_pendientes SET prioridad=prioridad+1 WHERE mensaje=%s AND estado=0 AND numero_salida=%s ;", GetSQLValueString($_POST['mensaje'], "text"), GetSQLValueString($_POST['numero_salida'], "text"));
        } else if ($_POST['action'] == 'Eliminar') {
            $SQL = sprintf("UPDATE mensajes_pendientes SET estado=2 WHERE estado=0 AND mensaje=%s;", GetSQLValueString($_POST['mensaje'], "text"));
        }
        mysql_query($SQL, $conexion) or die(register_mysql_error("CC0001", mysql_error()));
        header("Location: ?");
        exit();
    }
}

$SQL = sprintf("SELECT DISTINCT mensaje, numero_salida, COUNT(*) AS conteo, prioridad FROM mensajes_pendientes WHERE  estado=0 AND visible=1 GROUP BY mensaje, numero_salida, prioridad ORDER BY prioridad, conteo DESC;");
$rs = mysql_query($SQL, $conexion) or die(register_mysql_error("CC0003", mysql_error()));
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>Consulta de Cola</title>
        <link rel="stylesheet" type="text/css" href="../css/style.css" />
    </head>
    <body>
        <table width="760px" cellpadding="0" cellspacing="0">
            <tr><td colspan="8">&nbsp;</td></tr>
            <tr><th colspan="8">Mensajes en Cola</th></tr>
            <tr>
                <th style="width: 25px">&nbsp;</th>
                <th>Mensaje</th>
                <th>Shortcode</th>
                <th>Conteo</th>
                <th>Prioridad</th>
                <th colspan='3'>&nbsp;</th>
            </tr>
            <tr><td colspan="8">&nbsp;</td></tr><?php
            $i = 0;
            $total = 0;
            while ($row = mysql_fetch_array($rs)) {
                ?><form method="POST">
                    <input type='hidden' name='mensaje' value='<?php echo $row ['mensaje']; ?>' />
                    <input type='hidden' name='numero_salida' value='<?php echo $row ['numero_salida']; ?>' />
                    <tr>
                        <td><?php echo ++$i; ?></td>
                        <td><?php echo $row ['mensaje']; ?></td>
                        <td><?php echo $row ['numero_salida']; ?></td>
                        <td><?php echo number_format($row ['conteo'], 0); ?></td>
                        <td><?php echo number_format($row ['prioridad'], 0); ?></td>
                        <td><input name="action" type="submit" class="button" value='Priorizar' /></td>
                        <td><input name="action" type="submit" class="button" value='Despriorizar' /></td>
                        <td><input name="action" type="submit" class="button" value='Eliminar' /></td>
                    </tr>
                </form><?php
                $total += $row['conteo'];
            }
            ?>
            <tr><td colspan="8" >&nbsp;</th></tr>   
            <tr><th colspan="3" style="text-align: right">Total</th><th><?php echo number_format($total, 0); ?></th><th colspan='5'>&nbsp;</th></tr>   
            <tr><td colspan="8" >&nbsp;</th></tr>   
            <tr><th colspan="8" style="text-align: right"><a href="menu.php">Menu</a></th></tr>   
        </table>
    </body>
</html>