<?php
require_once ('functions.php');
require_once ('../connections/conexion.php');
require_once ('../functions/db.php');

session_start();
if (!isset($_SESSION['idAdmin'])) {
    header("Location: index.php");
    exit();
} else if (!permission(38)) {
    header("Location: index.php");
    exit();
}

$desde = strftime("%Y-%m-%d 00:00:00", time());
$hasta = strftime("%Y-%m-%d 23:59:59", time());
$idTelechat = 0;

if (isset($_POST['desde']) && isset($_POST['hasta']) && isset($_POST['idTelechat'])) {
    ini_set('max_execution_time', 0);
    ini_set("memory_limit", "786M");

    $desde = $_POST['desde'];
    $hasta = $_POST['hasta'];
    $idTelechat = $_POST['idTelechat'];

    $_SQL = sprintf("SELECT rp.numero, rp.mensaje AS msgOriginal, rr.mensaje AS msgRespuesta, rp.fecha FROM telechats t, telechats_mensajes rp, telechats_respuestas rr WHERE t.id=rp.idTelechat AND rp.id=rr.idMensaje AND t.id=%s AND rp.fecha>=%s AND rp.fecha<=%s ORDER BY rp.fecha DESC", GetSQLValueString($idTelechat, "int"), GetSQLValueString($desde, "date"), GetSQLValueString($hasta, "date"));
    $rs = mysql_query($_SQL, $conexion) or die(register_mysql_error("CRC001", mysql_error()));
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>Consulta de Chat</title>
        <link rel="stylesheet" type="text/css" href="../css/style.css" />
        <style type="text/css">@import url(../lib/calendar/calendar-brown.css);</style>
        <script type="text/javascript" src="../lib/calendar/calendar.js"></script>
        <script type="text/javascript" src="../lib/calendar/lang/calendar-es.js"></script>
        <script type="text/javascript" src="../lib/calendar/calendar-setup.js"></script>
    </head>

    <body>
        <form id="frmGenerar" name="frmGenerar" method="post">
            <table width="220px" cellpadding="0" cellspacing="0">
                <tr><th colspan="2">Consulta de Chat</th></tr>
                <tr><td colspan="0">&nbsp;</td></tr>     
                <tr>
                    <td>Rifa:</td>
                    <td><select name="idTelechat" id="idTelechat" onChange="this.form.submit()"><?php
$res_datos = mysql_query("SELECT id, nombre FROM telechats WHERE estado=1 ORDER BY nombre", $conexion) or die(register_mysql_error("CRC002", mysql_error()));
while ($row_datos = mysql_fetch_array($res_datos)) {
    $selected = ($idTelechat == $row_datos['id']) ? "selected" : "";
    ?><option value="<?php echo $row_datos['id'] ?>" <?php echo $selected ?> ><?php echo $row_datos['nombre'] ?></option><?php
                            }
                            ?></select></td>
                </tr>
                <tr><td colspan="2">&nbsp;</td></tr>  
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
        </form><br/>
        <table width="800px" cellpadding="0" cellspacing="0">
            <tr>
                <th style='width: 90px'>Numero</th>
                <th style='width: 130px'>Fecha</th>
                <th style='width: 290px; overflow: hidden;
                    white-space: nowrap;
                    text-overflow: ellipsis; '>Mensaje Original</th>
                <th style='width: 290px'>Mensaje Respuesta</th>
            </tr><?php
                            if (isset($rs)) {
                                while ($row = mysql_fetch_array($rs)) {
                                    ?><tr>
                        <td><?php echo $row['numero'] ?></td>
                        <td><?php echo $row['fecha'] ?></td>
                        <td><?php echo $row['msgOriginal'] ?></td>
                        <td><?php echo $row['msgRespuesta'] ?></td>
                    </tr><?php
        }
    }
                            ?>
            <tr><td colspan="5">&nbsp;</td></tr>
            <tr><th colspan="5" style="text-align: center"><a href="menu.php">Menu</a></th></tr>   
        </table>
    </body>
    <script type="text/javascript">
        Calendar.setup({
            inputField: "desde",
            ifFormat: "%Y-%m-%d %H:%M:%S",
            showsTime: true,
            button: "desde_btn",
            singleClick: true,
            step: 1
        });

        Calendar.setup({
            inputField: "hasta",
            ifFormat: "%Y-%m-%d %H:%M:%S",
            showsTime: true,
            button: "hasta_btn",
            singleClick: true,
            step: 1
        });
    </script>
</html>