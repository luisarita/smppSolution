<?php
require_once('functions.php');
require_once('../connections/conexion.php');

session_start();
if (!isset($_SESSION['idAdmin'])) {
    header("Location: " . initPage());
    exit();
} else if (!permission(8)) {
    header("Location: " . initPage());
    exit();
}

$tipo = (isset($_POST['tipo'])) ? $_POST['tipo'] : '1';
$actividad = (isset($_POST['actividad'])) ? $_POST['actividad'] : '0';

if (isset($_POST['Actualizar'])) {
    $query = "";
    if ($tipo == "1") {
        $respuesta = (isset($_POST['respuesta'])) ? $_POST['respuesta'] : '';
        $query = "UPDATE suscripciones SET respuesta='" . $respuesta . "' WHERE id=" . $actividad;
    } else if ($tipo == "2") {
        $mensaje_participante = (isset($_POST['mensaje_participante'])) ? $_POST['mensaje_participante'] : '';
        $query = "UPDATE telechats SET mensaje_participante='" . $mensaje_participante . "' WHERE id=" . $actividad;
    } else if ($tipo == "3") {
        $mensaje_participante_a = (isset($_POST['mensaje_participante_a'])) ? $_POST['mensaje_participante_a'] : '';
        $mensaje_participante_b = (isset($_POST['mensaje_participante_b'])) ? $_POST['mensaje_participante_b'] : '';
        $query = "UPDATE rifas SET mensaje_participante_a='" . $mensaje_participante_a . "', mensaje_participante_b='" . $mensaje_participante_b . "' WHERE id=" . $actividad;
    }
    mysql_query($query, $conexion) or die();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title></title>
        <link type="text/css" rel="stylesheet" href="../css/style.css"  />
        <script language="javascript">
            function validar(forma) {
                if (forma.respuesta.value == "") {
                    alert("La respuesta no puede ser vacia");
                    return false;
                }
                return true;
            }
        </script>
    </head>
    <body>
        <table border="0" cellspacing="0" cellpadding="0">
            <tr><td>&nbsp;</td></tr>     
            <tr><td>
                    <form action="" id="formactualizar" name="formactualizar" method="post">
                        <table cellpadding="0" cellspacing="0" width="100%">
                            <tr><th colspan="2">Mantenimiento de Respuestas</th></tr>
                            <tr><td colspan="2">&nbsp;</td></tr>
                            <tr>
                                <td>Tipo de Actividad:</td>
                                <td>
                                    <select name="tipo" id="tipo" onChange="this.form.actividad.selectedIndex = 0;
                this.form.submit()">
                                        <option value = "1" <?php if ($tipo == "1") echo "selected"; ?> >Suscripciones</option>
                                        <option value = "2" <?php if ($tipo == "2") echo "selected"; ?> >Telechats</option>
                                        <option value = "3" <?php if ($tipo == "3") echo "selected"; ?> >Rifas</option>
                                    </select>
                                </td>
                            </tr>
                            <tr><td colspan="2">&nbsp;</td></tr>
                            <tr>
                                <td>Actividad:</td>
                                <td>
                                    <select name="actividad" id="actividad" onChange="this.form.submit()">
                                        <option value="-1">Favor seleccione una actividad</option><?php
                                        if ($tipo == 1) {
                                            $query = "SELECT id, nombre FROM suscripciones ORDER BY nombre";
                                        } else if ($tipo == 2) {
                                            $query = "SELECT id, nombre FROM telechats ORDER BY nombre";
                                        } else if ($tipo == 3) {
                                            $query = "SELECT id, nombre FROM rifas ORDER BY nombre";
                                        }
                                        $actividad_info = mysql_query($query, $conexion);
                                        while ($row_respuestas = mysql_fetch_assoc($actividad_info)) {
    ?><option value = "<?php echo $row_respuestas['id']; ?>" <?php if ($actividad == $row_respuestas['id']) echo "selected"; ?>><?php
                                            echo $row_respuestas['nombre'];
                                            ?></option><?php
                                            }
                                            ?></select>
                                </td>
                            </tr>
                            <tr><td colspan="2">&nbsp;</td></tr>
                            <tr>
                                <td>Respuesta(s):</td><td><?php
                                        if ($tipo == 1) {
                                            $query = "SELECT respuesta FROM suscripciones WHERE id=" . $actividad;
                                        } else if ($tipo == 2) {
                                            $query = "SELECT mensaje_participante FROM telechats WHERE id=" . $actividad;
                                        } else if ($tipo == 3) {
                                            $query = "SELECT mensaje_participante_a, mensaje_participante_b FROM rifas WHERE id=" . $actividad;
                                        }
                                        $rs = mysql_query($query, $conexion) or die();
                                        $row = mysql_fetch_array($rs);
                                        if (is_array($row)) {
                                            foreach ($row as $index => $value) {
                                                if (!is_numeric($index))
                                                    echo sprintf("<input id='%s' name='%s' type='text' maxlength='255' value='%s'><br/>", $index, $index, $value);
                                            }
                                        }
                                            ?></td>
                            </tr>
                            <tr><td colspan="2">&nbsp;</td></tr>
                            <tr><td colspan="2" style="text-align: right">
                                    <input name='Actualizar' type="submit" class="button" value="Actualizar" onClick="return validar(this.form)" />
                                </td></tr>
                            <tr><td colspan="2">&nbsp;</td></tr>    
                            <tr><th colspan="2" style="text-align: center"><a href="menu.php">Menu</a></th></tr>   
                        </table>
                    </form>
                </td></tr>
        </table>
    </body>
</html>