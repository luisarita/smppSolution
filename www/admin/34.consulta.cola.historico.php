<?php
require_once('../connections/conexion.php');
require_once('../functions/functions.php');
require_once('../functions/db.php');
require_once('functions.php');

ini_set("max_execution_time", 0);
session_start();
if (!isset($_SESSION['idAdmin'])) {
    header("Location: " . initPage());
    exit();
} else if (!permission(34)) {
    header("Location: " . initPage());
    exit();
}

$desde = strftime("%Y-%m-%d %H:%M:%S", time());
$hasta = strftime("%Y-%m-%d %H:%M:%S", time());

if (isset($_POST['accion'])) {
    $desde = $_POST['desde'];
    $hasta = $_POST['hasta'];

    $SQL = array();
    $SQL[] = sprintf("DROP TEMPORARY TABLE T1;");
    $SQL[] = sprintf("CREATE TEMPORARY TABLE T1 (mensaje VARCHAR(255), numero_salida VARCHAR(5), fecha_envio DATETIME, conteo INT);");
    $SQL[] = sprintf("INSERT INTO T1 SELECT DISTINCT mensaje, numero_salida, fecha_envio, COUNT(*) AS conteo FROM mensajes_pendientes WHERE estado=1 AND visible=1 AND fecha_envio>=%s AND fecha_envio <= %s GROUP BY mensaje, numero_salida, fecha_envio;", GetSQLValueString($desde, "date"), GetSQLValueString($hasta, "date"));
    if (isset($_POST['historico']) && $_POST['historico'] == 1) {
        $SQL[] = sprintf("INSERT INTO T1 SELECT DISTINCT mensaje, numero_salida, fecha_envio, COUNT(*) AS conteo FROM mensajes_enviados WHERE estado=1 AND visible=1 AND fecha_envio>=%s AND fecha_envio <= %s GROUP BY mensaje, numero_salida, fecha_envio;", GetSQLValueString($desde, "date"), GetSQLValueString($hasta, "date"));
    }
    $SQL[] = sprintf("SELECT * FROM T1 ORDER BY fecha_envio DESC;");

    foreach ($SQL as $index => $value) {
        $rs = mysql_query($value, $conexion) or die(register_mysql_error("CCH00" . $index, mysql_error()));
    }
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>Consulta de Cola Hist&oacute;rica</title>
        <link rel="stylesheet" type="text/css" href="../css/style.css" />
        <style type="text/css">@import url(../lib/calendar/calendar-brown.css);</style>
        <script type="text/javascript" src="../lib/calendar/calendar.js"></script>
        <script type="text/javascript" src="../lib/calendar/lang/calendar-es.js"></script>
        <script type="text/javascript" src="../lib/calendar/calendar-setup.js"></script>
    </head>
    <body>
        <table width="760px" cellpadding="0" cellspacing="0">
            <form action="" id="formfiltrar" name="formfiltrar" method="post">
                <tr><th colspan="6">Criterios de B&uacute;squeda</th></tr>
                <tr><td colspan="6">&nbsp;</td></tr>
                <tr>
                    <td colspan="2">Desde:</td>
                    <td colspan="4"><input name="desde" id="desde" value="<?php echo $desde ?>" /><button id='desde_btn'> ... </button></td>
                </tr><tr>
                    <td colspan="2">Hasta:</td>
                    <td colspan="4"><input name="hasta" id="hasta" value="<?php echo $hasta ?>" /><button id='hasta_btn'> ... </button></td>
                </tr><tr>
                    <td colspan="2">Incluir Hist&oacute;rico:</td>
                    <td colspan="4"><input class="checkbox" id="historico" name="historico" type="checkbox" value="1" ></td>
                </tr>
                <tr><td colspan="6">&nbsp;</td></tr>
                <tr><td colspan="6" style="text-align: right">
                        <input type="submit" class="button" value="Filtrar" />
                    </td></tr>
                <tr><td colspan="6">&nbsp;</td></tr>
                <input type="hidden" name="accion" value="filtrar" />
            </form>
            <tr><td colspan="6">&nbsp;</td></tr>
            <tr><th colspan="6">Mensajes en Cola</th></tr>
            <tr>
                <th style="width: 25px">&nbsp;</th>
                <th>Shortcode</th>
                <th>Mensaje</th>
                <th>Fecha Envio</th>
                <th>Conteo</th>
            </tr>
            <tr><td colspan="5">&nbsp;</td></tr><?php
            if (isset($rs)) {
                $i = 0;
                while ($row = mysql_fetch_array($rs)) {
                    ?><tr>
                        <td><?php echo ++$i; ?></td>
                        <td><?php echo $row ['numero_salida']; ?></td>
                        <td><?php echo $row ['mensaje']; ?></td>
                        <td><?php echo $row ['fecha_envio']; ?></td>
                        <td><?php echo number_format($row ['conteo'], 0); ?></td>
                    </tr><?php
                }
            }
            ?>
            <tr><td colspan="6" >&nbsp;</th></tr>   
            <tr><th colspan="6" style="text-align: right"><a href="menu.php">Menu</a></th></tr>   
        </table>
    </body>
    <script language="javascript">
        Calendar.setup({
            inputField: "desde", // id of the input field
            ifFormat: "%Y-%m-%d %H:%M:%S", // format of the input field
            showsTime: true, // will display a time selector
            button: "desde_btn", // trigger for the calendar (button ID)
            singleClick: true, // double-click mode
            step: 1                    // show all years in drop-down boxes (instead of every other year as default)
        });

        Calendar.setup({
            date: "2006/01/11",
            inputField: "hasta", // id of the input field
            ifFormat: "%Y-%m-%d %H:%M:%S", // format of the input field
            showsTime: true, // will display a time selector
            button: "hasta_btn", // trigger for the calendar (button ID)
            singleClick: true, // double-click mode
            step: 1                    // show all years in drop-down boxes (instead of every other year as default)
        });
    </script>
</html>