<?php
require_once('../connections/conexion.php');
require_once('../functions/functions.php');
require_once('../functions/db.php');
require_once('functions.php');

session_start();
if (!isset($_SESSION['idAdmin'])) {
    header("Location: " . initPage());
    exit();
} else if (!permission(32)) {
    header("Location: " . initPage());
    exit();
}

ini_set("max_execution_time", 0);

$numero = (isset($_POST['numero'])) ? $_POST['numero'] : '';
$fecha1 = (isset($_POST['fecha1'])) ? $_POST['fecha1'] : strftime("%Y-%m-%d 00:00:00", time());
$fecha2 = (isset($_POST['fecha2'])) ? $_POST['fecha2'] : strftime("%Y-%m-%d 23:59:59", time());
//if ( $numero == '' ) $numero = '';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Consulta de Envíos</title>
        <link rel="stylesheet" type="text/css" href="../css/style.css" />
    </head>
    <body>
        <form action="" id="formfiltrar" name="formfiltrar" method="post">
            <table width="100%" cellpadding="0" cellspacing="0">
                <tr><th colspan="8">Criterios de Busqueda</th></tr>
                <tr><td colspan="8">&nbsp;</td></tr>
                <tr>
                    <td colspan="2">Numero:</td>
                    <td colspan="4"><input class="textbox" id="numero" name="numero" type="text" value="<?php echo $numero; ?>"></td>
                    <tr>
                        <td colspan="2">Fecha Desde:</td>
                        <td colspan="4"><input class="textbox" id="fecha1" name="fecha1" type="text" value="<?php echo $fecha1; ?>"></td>
                        <tr>
                            <td colspan="2">Fecha Hasta:</td>
                            <td colspan="4"><input class="textbox" id="fecha2" name="fecha2" type="text" value="<?php echo $fecha2; ?>"></td>
                        </tr><tr>
                            <td colspan="2">Incluir Histórico:</td>
                            <td colspan="4"><input class="checkbox" id="historico" name="historico" type="checkbox" value="1" ></td>
                        </tr>
                        <tr><td colspan="8">&nbsp;</td></tr>
                        <tr><td colspan="8" style="text-align: right">
                                <input type="submit" class="button" value="Filtrar" />
                            </td></tr>
                        <tr><td colspan="8">&nbsp;</td></tr>
                        </table>
                        <input type="hidden" name="accion" value="filtrar" />
                        </form>
                        <table width="100%" cellpadding="0" cellspacing="0">
                            <tr><td colspan="7">&nbsp;</td></tr>
                            <tr><th colspan="7">Mensajes Recibidos</th></tr>
                            <tr>
                                <th style="width: 25px">&nbsp;</th>
                                <th>C&oacute;digo</th>
                                <th>N&uacute;mero</th>
                                <th>Shortcode</th>
                                <th>Mensaje</th>
                                <th style="width: 140px">Fecha</th>
                                <th>Estado</th>
                            </tr>
                            <tr><td colspan="6">&nbsp;</td></tr><?php
                            if (isset($_POST["accion"])) {
                                $sql = sprintf('SELECT crt.numero_de_comunicacion_re, crt.telefono_origen_de_respue, crt.telefon_destino_de_respu, crt.datos_recibidos, crt.fecha_de_recepcion, d.valor AS procesado FROM comunicacion_recibida_tabla crt LEFT OUTER JOIN diccionario_comunicacion_recibida$procesado d ON d.id=crt.procesado WHERE crt.telefono_origen_de_respue=%s AND crt.fecha_de_recepcion>=%s AND crt.fecha_de_recepcion<=%s', GetSQLValueString($numero, "text"), GetSQLValueString($fecha1, "text"), GetSQLValueString($fecha2, "text"));
                                if (isset($_POST['historico']) && $_POST['historico'] == 1)
                                    $sql = sprintf("%s UNION %s", $sql, str_replace("comunicacion_recibida_tabla", "comunicacion_recibida_tabla_bck", $sql));
                                $sql .= " ORDER BY numero_de_comunicacion_re";
                                $rs = mysql_query($sql, $conexion) or die(register_mysql_error("CE0001", mysql_error()));
                                $i = 0;
                                while ($row = mysql_fetch_array($rs)) {
                                    ?><tr>
                                        <td><?php echo ++$i; ?></td>
                                        <td><?php echo $row ['numero_de_comunicacion_re']; ?></td>
                                        <td><?php echo $row ['telefono_origen_de_respue']; ?></td>
                                        <td><?php echo $row ['telefon_destino_de_respu']; ?></td>
                                        <td><?php echo $row ['datos_recibidos']; ?></td>
                                        <td><?php echo $row ['fecha_de_recepcion']; ?></td>
                                        <td><?php echo $row ['procesado']; ?></td>
                                    </tr><?php
                                }
                            }
                            ?>
                        </table>
                        <?php
                        $colSpan = 12;
                        $html = "";
                        $html .= "<table width='100%' cellpadding='0' cellspacing='0'>";
                        $html .= "<tr><td colspan='$colSpan'>&nbsp;</td></tr>";
                        $html .= "<tr><th colspan='$colSpan' style='text-align: center'>Mensajes Enviados</th></tr>";
                        $html .= "<tr>";
                        $html .= "<th style='width: 25px'>&nbsp;</th>";
                        $html .= "<th>N&uacute;mero</th>";
                        $html .= "<th>Shortcode</th>";
                        $html .= "<th>Mensaje</th>";
                        $html .= "<th style='width: 140px'>Fecha Generación</th>";
                        $html .= "<th style='width: 140px'>Fecha Envío</th>";
                        $html .= "<th style='width: 140px'>Fecha Confirmación</th>";
                        $html .= "<th>Estado</th>";
                        $html .= "<th>Fuente</th>";
                        $html .= "<th>Prioridad</th>";
                        $html .= "<th>Tipo Servicio</th>";
                        $html .= "<th>Respuesta ESME</th>";
                        $html .= "</tr>";
                        $html .= "<tr><td colspan=$colSpan>&nbsp;</td></tr>";
                        echo $html;
                        if (isset($_POST["accion"])) {
                            $sql = sprintf("SELECT numero, numero_salida, mensaje, prioridad, fecha_salida, fecha_envio, fecha_confirmacion, IF(estado=1,'Enviado','No Enviado') AS estado, fuente, tipo_servicio, respuesta_esme FROM mensajes_pendientes WHERE numero=%s AND fecha_salida>=%s AND fecha_salida<=%s", GetSQLValueString($numero, "text"), GetSQLValueString($fecha1, "text"), GetSQLValueString($fecha2, "text"));
                            if (isset($_POST['historico']) && $_POST['historico'] == 1)
                                $sql = sprintf("%s UNION %s", $sql, str_replace("mensajes_pendientes", "mensajes_enviados", $sql));
                            $sql .= " ORDER BY fecha_envio";
                            $rs = mysql_query($sql, $conexion) or die(register_mysql_error("CE0002" . mysql_error(), mysql_error()));
                            $i = 0;
                            $html = "";
                            while ($row = mysql_fetch_array($rs)) {
                                $html .= "<tr>";
                                $html .= " <td>" . ++$i . "</td>";
                                $html .= " <td>" . $row ['numero'] . "</td>";
                                $html .= " <td>" . $row ['numero_salida'] . "</td>";
                                $html .= " <td>" . $row ['mensaje'] . "</td>";
                                $html .= " <td>" . $row ['fecha_salida'] . "</td>";
                                $html .= " <td>" . $row ['fecha_envio'] . "</td>";
                                $html .= " <td>" . $row ['fecha_confirmacion'] . "</td>";
                                $html .= " <td>" . $row ['estado'] . "</td>";
                                $html .= " <td>" . $row ['fuente'] . "</td>";
                                $html .= " <td>" . $row ['prioridad'] . "</td>";
                                $html .= " <td>" . $row ['tipo_servicio'] . "</td>";
                                $html .= " <td>" . $row ['respuesta_esme'] . "</td>";
                                $html .= "</tr>";
                            }
                        }
                        $html .= "<tr><td colspan='$colSpan'>&nbsp;</td></tr>";
                        $html .= "<tr><th colspan='$colSpan' style='text-align: center'><a href='menu.php'>Menu</a></th></tr>";
                        echo $html;
                        ?>
                        </table>
                        </body>
                        </html>