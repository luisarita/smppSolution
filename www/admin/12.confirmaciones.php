<?php
require_once('functions.php');
require_once('../connections/conexion.php');

session_start();
if (!isset($_SESSION['idAdmin'])) {
    header("Location: " . initPage());
} else if (!permission(12)) {
    header("Location: " . initPage());
}

$desde = (isset($_POST['desde'])) ? $_POST['desde'] : strftime("%Y-%m-%d", time() - 24 * 3600);
$hasta = (isset($_POST['hasta'])) ? $_POST['hasta'] : strftime("%Y-%m-%d", time() + 3600);

$sql = "SELECT s.nombre, s.numero, SUM(confirmados) AS confirmados, SUM(no_confirmados) AS no_confirmados, SUM(confirmados+no_confirmados) AS total, SUM(confirmados)/SUM(confirmados+no_confirmados)*100 AS porcentaje FROM estadisticas_confirmaciones e, suscripciones s WHERE s.id=e.idSuscripcion AND fecha>=" . GetSQLValueString($desde, "date") . " AND fecha<=" . GetSQLValueString($hasta, "date") . " GROUP BY s.nombre ORDER BY s.numero, s.nombre";
$rs = mysql_query($sql, $conexion) or die(mysql_error());
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>Confirmaciones</title>
        <link type="text/css" rel="stylesheet" href="../css/style.css"  />
        <style type="text/css">@import url(../lib/calendar/calendar-blue.css);</style>
        <script type="text/javascript" src="../lib/calendar/calendar.js"></script>
        <script type="text/javascript" src="../lib/calendar/lang/calendar-es.js"></script>
        <script type="text/javascript" src="../lib/calendar/calendar-setup.js"></script>
    </head>
    <body>
        <table cellpadding="1" cellspacing="1">
            <tr><th colspan="5">Confirmaci&oacute;n de Mensajes</th></tr>
            <tr><td colspan="5">&nbsp;</td></tr>
            <tr><td colspan="5" style="text-align: center">
                    <form action="" id="formserv" name="formserv" method="post">
                        <table cellpadding="1" cellspacing="1">
                            <tr>
                                <td>Fecha Desde:</td>
                                <td colspan="3"><input id="desde" name="desde" value="<?php echo $desde ?>"></td>
                                <td colspan="1"><button id="desde_btn">...</button></td>
                            </tr><tr>
                                <td>Fecha Hasta:</td>
                                <td colspan="3"><input id="hasta" name="hasta" type="text" value="<?php echo $hasta ?>"></td>
                                <td colspan="1"><button id="hasta_btn">...</button></td>
                            </tr>
                            <tr><td colspan="5" style="text-align: right">
                                    <input type="submit" class="button" value="Filtrar" />
                                </td></tr>
                        </table>
                    </form>
                </td></tr>
            <tr><td colspan="5">&nbsp;</td></tr>
            <tr>
                <th style="text-align: left">N�mero</th>
                <th style="text-align: left">Suscripci�n</th>
                <th style="text-align: right">Confirmados</th>
                <th style="text-align: right">No Confirmados</th>
                <th style="text-align: right">Total</th>
                <th style="text-align: right">%</th>
            </tr>
            <tr><td>&nbsp;</td></tr><?php
$confirmados = 0;
$no_confirmados = 0;
$total = 0;
while ($row = mysql_fetch_array($rs)) {
    $confirmados += $row['confirmados'];
    $no_confirmados += $row['no_confirmados'];
    $total += $row['total'];
    ?><tr>
                    <td><?php echo $row['numero']; ?></td>
                    <td><?php echo $row['nombre']; ?></td>
                    <td style="text-align: right"><?php echo number_format($row['confirmados'], 0); ?></td>
                    <td style="text-align: right"><?php echo number_format($row['no_confirmados'], 0); ?></td>
                    <td style="text-align: right"><?php echo number_format($row['total'], 0); ?></td>
                    <td style="text-align: right"><?php echo number_format($row['porcentaje'], 0); ?>%</td>
                </tr><?php
        }
        $porcentaje = ($total > 0 ) ? $confirmados / $total * 100 : 0;
?>
            <tr><td>&nbsp;</td></tr>
            <tr>
                <th style="text-align: left" colspan="2">&nbsp;</th>
                <th style="text-align: right"><?php echo number_format($confirmados, 0); ?></th>
                <th style="text-align: right"><?php echo number_format($no_confirmados, 0); ?></th>
                <th style="text-align: right"><?php echo number_format($total, 0); ?></th>
                <th style="text-align: right"><?php echo number_format($porcentaje, 0); ?>%</th>
            </tr>
        </table>
    </body>
    <script type="text/javascript">
        Calendar.setup({
            inputField: "desde", // id of the input field
            ifFormat: "%Y-%m-%d", // format of the input field
            showsTime: true, // will display a time selector
            button: "desde_btn", // trigger for the calendar (button ID)
            singleClick: true, // double-click mode
            step: 1                 // show all years in drop-down boxes (instead of every other year as default)
        });

        Calendar.setup({
            date: "2006/01/11",
            inputField: "hasta", // id of the input field
            ifFormat: "%Y-%m-%d", // format of the input field
            showsTime: true, // will display a time selector
            button: "hasta_btn", // trigger for the calendar (button ID)
            singleClick: true, // double-click mode
            step: 1                 // show all years in drop-down boxes (instead of every other year as default)
        });
    </script>
</html