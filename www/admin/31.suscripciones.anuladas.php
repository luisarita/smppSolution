<?php
require_once('../connections/conexion.php');
require_once('../functions/functions.php');
require_once('../functions/db.php');
require_once('functions.php');

session_start();
if (!isset($_SESSION['idAdmin'])) {
    header("Location: " . initPage());
    exit();
} else if (!permission(31)) {
    header("Location: " . initPage());
    exit();
}

$desde = (isset($_POST['desde'])) ? $_POST['desde'] : strftime("%Y-%m-%d 00:00:00", time());
$hasta = (isset($_POST['hasta'])) ? $_POST['hasta'] : strftime("%Y-%m-%d 23:59:00", time());

mysql_select_db($database_conexion, $conexion);
$sql = sprintf("SELECT p.numero, s.nombre, p.usr_anl as usuario, p.obs_anl as observacion, p.fec_anl as fecha FROM suscripciones_participantes p, suscripciones s WHERE p.idSuscripcion=s.id AND p.fec_anl>=%s AND p.fec_anl<=%s;", GetSQLValueString($desde, "date"), GetSQLValueString($hasta, "date"));
$rs = mysql_query($sql, $conexion) or die(register_mysql_error("CS0002", mysql_error()));
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>Anulaciones</title>
        <link rel="stylesheet" type="text/css" href="../css/style.css" />
        <style type="text/css">@import url(../lib/calendar/calendar-brown.css);</style>
        <script type="text/javascript" src="../lib/calendar/calendar.js"></script>
        <script type="text/javascript" src="../lib/calendar/lang/calendar-es.js"></script>
        <script type="text/javascript" src="../lib/calendar/calendar-setup.js"></script>
    </head>
    <body>
        <form id="frmGenerar" name="frmGenerar" method="post">
            <table width="220px" cellpadding="0" cellspacing="0">
                <tr><th colspan="2">Consolidado de Suscripciones</th></tr>
                <tr><td colspan="0">&nbsp;</td></tr>     
                <tr>
                    <td>Desde:</td>
                    <td><input class="textbox-small" type="text" id='desde' name='desde' value='<?php echo $desde ?>' />&nbsp;<button name="desde_btn" id="desde_btn">..</button></td>
                </tr>
                <tr><td colspan="2">&nbsp;</td></tr>  
                <tr>
                    <td>Hasta:</td>
                    <td><input class="textbox-small" type="text" id='hasta' name='hasta' value='<?php echo $hasta ?>' />&nbsp;<button name="hasta_btn" id="hasta_btn">..</button></td>
                </tr>
                <tr><td colspan="2">&nbsp;</td></tr>    
                <tr><td colspan="2" style="text-align: right"><input name="accion" type="submit" class="button" id="" value="Generar" /></td></tr>    
                <tr><td colspan="2">&nbsp;</td></tr>    
                <tr><th colspan="2" style="text-align: center"><a href="menu.php">Menu</a></th></tr>
            </table>
        </form>
        <table><?php
            $html = sprintf("<tr><th>%s</th><th>%s</th><th>%s</th><th>%s</th><th>%s</th></tr>", "N�mero", "Nombre", "Usuario", "Observaci�n", "Fecha");
            while ($row = mysql_fetch_array($rs)) {
                $html .= sprintf("<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>", $row["numero"], $row["nombre"], $row["usuario"], $row["observacion"], $row["fecha"]);
            }
            echo $html;
            ?></table>
    </body>
</html>