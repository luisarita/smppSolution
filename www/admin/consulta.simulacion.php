<?php
session_start();
if (!isset($_SESSION['idAdmin'])) {
    header("Location: " . $initPage);
}

require_once('../connections/conexion.php');
mysql_select_db($database_conexion, $conexion);

$usuario = (isset($_POST['usuario'])) ? $_POST['usuario'] : '-1';
$desde = (isset($_POST['desde'])) ? $_POST['desde'] : strftime("%Y-%m-%d %H:00", time() - 24 * 3600);
$hastaStr = (isset($_POST['hasta'])) ? $_POST['hasta'] : strftime("%Y-%m-%d %H:00", time() + 3600);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>Consulta Simulaci&oacute;n</title>
        <link rel="stylesheet" type="text/css" href="../css/style.css" />
        <style type="text/css">@import url(../lib/calendar/calendar-blue.css);</style>
        <script type="text/javascript" src="../lib/calendar/calendar.js"></script>
        <script type="text/javascript" src="../lib/calendar/lang/calendar-es.js"></script>
        <script type="text/javascript" src="../lib/calendar/calendar-setup.js"></script>
    </head>
    <body>
        <table border="0" cellspacing="0" cellpadding="0">
            <tr><td>&nbsp;</td></tr>     
            <tr><td>
                    <table cellpadding="0" cellspacing="0" width="100%">
                        <form action="" id="formfiltrar" name="formfiltrar" method="post">
                            <table cellpadding="0" cellspacing="0" width="100%">
                                <tr><th colspan="5">Criterios de Busqueda</th></tr>
                                <tr><td colspan="5">&nbsp;</td></tr>
                                <tr>
                                    <td colspan="2">Usuario:</td>
                                    <td>
                                        <select name="usuario" id="usuario"><?php
$res_usuarios = mysql_query("select id, usuario from mantenimiento_usuarios", $conexion) or die(mysql_error());
while ($row_usuarios = mysql_fetch_array($res_usuarios)) {
    ?><option value = "<?php echo $row_usuarios['id'] ?>" <?php if ($usuario == $row_usuarios['id']) echo "selected"; ?> ><?php echo $row_usuarios['usuario'] ?></option><?php
                                            }
                                            ?></select>
                                    </td>
                                </tr>
                                <tr><td colspan="5">&nbsp;</td></tr>
                                <tr>
                                    <td colspan="2">Fecha Desde:</td>
                                    <td><input id="desde" name="desde" value="<?php echo $desde ?>"></td><td><button id="desde_btn">...</button></td>
                                </tr><tr>
                                    <td colspan="2">Fecha Hasta:</td><td><input id="hasta" name="hasta" type="text" value="<?php echo $hastaStr ?>"></td>
                                    <td><button id="hasta_btn">...</button></td>
                                </tr>
                                <tr><td colspan="5" style="text-align: right"><input type="submit" class="button" value="Filtrar" /></td></tr>
                                <input type="hidden" name="accion" value="filtrar" />
                                <tr><td colspan="5">&nbsp;</td></tr>
                        </form>
                        <tr>
                            <th style="width: 25px">&nbsp;</th>
                            <th>Mensaje</th>
                            <th style="width: 120px">Fecha</th>
                            <th>Numero</th>
                            <th>Numero Salida</th>
                        </tr>
                        <tr><td colspan="5">&nbsp;</td></tr><?php
                                            if (isset($_POST["accion"])) {
                                                $res_mensaje = mysql_query("select mensaje,fecha,numero,numero_salida from mantenimiento_simulaciones where usuario = " . $usuario . " and fecha >= '" . $desde . "' and fecha <= '" . $hastaStr . "'", $conexion) or die(mysql_error());
                                                $i = 0;
                                                while ($row_mensajes = mysql_fetch_array($res_mensaje)) {
                                                    ?><tr>
                                    <td><?php echo ++$i; ?></td>
                                    <td><?php echo $row_mensajes ['mensaje']; ?></td>
                                    <td><?php echo $row_mensajes ['fecha']; ?></td>
                                    <td><?php echo $row_mensajes ['numero']; ?></td>
                                    <td><?php echo $row_mensajes ['numero_salida']; ?></td>
                                </tr><?php
                    }
                }
                                            ?><tr><td colspan="5">&nbsp;</td></tr>
                        <tr><th colspan="5" style="text-align: center"><a href="menu.php">Menu</a></th></tr>   
                    </table></td></tr>
        </table>
    </body>
    <script type="text/javascript">
        Calendar.setup({
            inputField: "desde", // id of the input field
            ifFormat: "%Y-%m-%d %H:%M", // format of the input field
            showsTime: true, // will display a time selector
            button: "desde_btn", // trigger for the calendar (button ID)
            singleClick: true, // double-click mode
            step: 1                 // show all years in drop-down boxes (instead of every other year as default)
        });

        Calendar.setup({
            date: "2006/01/11",
            inputField: "hasta", // id of the input field
            ifFormat: "%Y-%m-%d %H:%M", // format of the input field
            showsTime: true, // will display a time selector
            button: "hasta_btn", // trigger for the calendar (button ID)
            singleClick: true, // double-click mode
            step: 1                 // show all years in drop-down boxes (instead of every other year as default)
        });
    </script>
</html>