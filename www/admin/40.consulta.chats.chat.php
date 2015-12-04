<?php
require_once ('functions.php');
require_once ('../connections/conexion.php');
require_once ('../functions/db.php');

session_start();
if (!isset($_SESSION['idAdmin'])) {
    header("Location: " . initPage());
    exit();
} else if (!permission(40)) {
    header("Location: " . initPage());
    exit();
}

$desde = strftime("%Y-%m-%d 00:00:00", time());
$hasta = strftime("%Y-%m-%d 23:59:59", time());
$idChat = 0;

if (isset($_POST['desde']) && isset($_POST['hasta']) && isset($_POST['idChat'])) {
    ini_set('max_execution_time', 0);
    ini_set("memory_limit", "786M");

    $desde = $_POST['desde'];
    $hasta = $_POST['hasta'];
    $idChat = $_POST['idChat'];

    mysql_select_db($database_conexion, $conexion);

    $_SQL = sprintf("SELECT r.nombre AS chat, rr.numero, rr.mensaje AS msgOriginal, rr.mensaje AS msgRespuesta, rr.fecha FROM chats r, chats_respuestas rr WHERE r.id=rr.idChat AND r.id=%s AND rr.fecha>=%s AND rr.fecha<=%s ORDER BY rr.fecha DESC", GetSQLValueString($idChat, "int"), GetSQLValueString($desde, "date"), GetSQLValueString($hasta, "date"));
    $rs = mysql_query($_SQL, $conexion) or die(mysql_error()); //die(register_mysql_error("CRC001", mysql_error()));
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
            <table width="540px" cellpadding="0" cellspacing="0">
                <tr><th colspan="3">Consulta de Chat</th></tr>
                <tr><td colspan="3">&nbsp;</td></tr>     
                <tr>
                    <td>Chat:</td>
                    <td colspan="2"><select name="idChat" id="idChat" onChange="this.form.submit()"><?php
$res_datos = mysql_query("SELECT id, nombre FROM chats WHERE estado=1 ORDER BY nombre", $conexion) or die(register_mysql_error("CRC002", mysql_error()));
while ($row_datos = mysql_fetch_array($res_datos)) {
    $selected = ($idChat == $row_datos['id']) ? "selected" : "";
    ?><option value="<?php echo $row_datos['id'] ?>" <?php echo $selected ?> ><?php echo $row_datos['nombre'] ?></option><?php
                            }
                            ?></select></td>
                </tr>
                <tr><td colspan="3">&nbsp;</td></tr>  
                <tr>
                    <td>Desde:</td>
                    <td colspan='2'><input class="textbox-small" type="text" id='desde' name='desde' value='<?php echo $desde ?>' />&nbsp;<button name="desde_btn" id="desde_btn">..</button></td>
                </tr>
                <tr><td colspan="3">&nbsp;</td></tr>  
                <tr>
                    <td>Hasta:</td>
                    <td colspan='2'><input class="textbox-small" type="text" id='hasta' name='hasta' value='<?php echo $hasta ?>' />&nbsp;<button name="hasta_btn" id="hasta_btn">..</button></td>
                </tr>
                <tr><td colspan="3">&nbsp;</td></tr>    
                <tr><td colspan="3" style="text-align: right"><input name="accion" type="submit" class="button" id="" value="Generar" /></td></tr>    
                <tr><td colspan="3">&nbsp;</td></tr>    
                <tr>
                    <th style='width: 90px'>Numero</th>
                    <th style='width: 130px'>Fecha</th>
                    <th style='width: 290px; overflow: hidden;
                        white-space: nowrap;
                        text-overflow: ellipsis; '>Mensaje Respuesta</th>
                </tr>
                <tr><td colspan="3">&nbsp;</td></tr><?php
                            if (isset($rs)) {
                                while ($row = mysql_fetch_array($rs)) {
                                    ?><tr>
                            <td><?php echo $row['numero'] ?></td>
                            <td><?php echo $row['fecha'] ?></td>
                            <td><?php echo $row['msgRespuesta'] ?></td>
                        </tr><?php
            }
        }
                            ?>
                <tr><td colspan="3">&nbsp;</td></tr>
                <tr><th colspan="3" style="text-align: center"><a href="menu.php">Menu</a></th></tr>   
            </table>
        </form><br/>
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