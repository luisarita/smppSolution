<?php
require_once('functions.php');
require_once('../connections/conexion.php');

session_start();
if (!isset($_SESSION['idAdmin'])) {
    header("Location: index.php");
    exit();
} else if (!permission(6)) {
    header("Location: index.php");
    exit();
}

mysql_select_db($database_conexion, $conexion);

$_palabra = "";
if (isset($_POST['palabra']) && trim($_POST['palabra']) != "")
    $_palabra = "%" . trim($_POST['palabra']) . "%";
$_SQL = sprintf("SELECT r.nombre AS rifa, rp.numero, rp.mensaje AS msgOriginal, rr.mensaje AS msgRespuesta, rp.fecha FROM rifas r, rifas_participantes rp, rifas_respuestas rr WHERE r.id=rp.idRifa AND rp.id=rr.idParticipante AND rr.id AND rr.mensaje LIKE %s AND %s <> '' ORDER BY rp.fecha DESC", GetSQLValueString($_palabra, "text"), GetSQLValueString($_palabra, "text"));
$rs = mysql_query($_SQL, $conexion) or die();
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>B�squeda por Palabra</title>
        <link rel="stylesheet" type="text/css" href="../css/style.css" />
    </head>

    <body>
        <form id="formBusqueda" name="formBusqueda" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <table width="440px" cellpadding="0" cellspacing="0">
                <tr><th colspan="2">B&uacute;squeda por Palabra</th></tr>
                <tr><td colspan="2">&nbsp;</td></tr>
                <tr>
                    <td class="noborder">Palabra:</td>
                    <td class="noborder"><input type="text" name="palabra" id="palabra" /></td>
                </tr>
                <tr><td colspan="2">&nbsp;</td></tr>
                <tr>
                    <td colspan="2" style="text-align:right"><input type="submit" class="button" name="button" id="button" value="B&uacute;squeda" /></td>
                </tr>
            </table>
        </form><br/>
        <table width="440px" cellpadding="0" cellspacing="0">
            <tr>
                <th>Numero</th>
                <th>Fecha</th>
                <th>Mensaje Original</th>
                <th>Mensaje Respuesta</th>
                <th>Rifa</th>
            </tr><?php
            while ($row = mysql_fetch_array($rs)) {
                ?><tr>
                    <td><?php echo $row['numero'] ?></td>
                    <td><?php echo $row['fecha'] ?></td>
                    <td><?php echo $row['msgOriginal'] ?></td>
                    <td><?php echo $row['msgRespuesta'] ?></td>
                    <td><?php echo $row['rifa'] ?></td>
                </tr><?php
            }
            ?>
            <tr><td colspan="5">&nbsp;</td></tr>
            <tr><th colspan="5" style="text-align: center"><a href="menu.php">Menu</a></th></tr>   
        </table>
    </body>
</html><?php
mysql_free_result($rs);
?>