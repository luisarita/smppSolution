<?php
/*
  ALTER TABLE suscripciones_participantes ADD COLUMN obs_anl VARCHAR(255);
  ALTER TABLE suscripciones_participantes ADD COLUMN usr_anl VARCHAR(255);
  ALTER TABLE suscripciones_participantes ADD COLUMN fec_anl DATETIME;
 */
require_once('../connections/conexion.php');
require_once('../functions/functions.php');
require_once('../functions/db.php');
require_once('functions.php');

session_start();
if (!isset($_SESSION['idAdmin'])) {
    header("Location: " . initPage());
    exit();
} else if (!permission(9)) {
    header("Location: " . initPage());
    exit();
}

mysql_select_db($database_conexion, $conexion);
$numero = (isset($_POST['numero'])) ? $_POST['numero'] : '';
if ($numero == '')
    $numero = '';
if (isset($_POST["accion"]) && $_POST["accion"] == 'actualizar') {
    $obs = $_POST['obs'];
    $id = $_POST['id'];
    $usr_act = $_SESSION['usuario'];
    $sql = sprintf("UPDATE suscripciones_participantes SET estado=0, obs_anl=%s, usr_anl=%s, fec_anl=NOW() WHERE id=%s", GetSQLValueString($obs, "text"), GetSQLValueString($usr_act, "text"), GetSQLValueString($id, "int"));
    mysql_query($sql, $conexion) or die(register_mysql_error("CS0001", mysql_error()));
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>Consulta Suscripciones</title>
        <link rel="stylesheet" type="text/css" href="../css/style.css" />
        <script>
            function validar(myForm) {
                if (myForm.obs.value == '') {
                    alert('Debe ingresar una observaci�n');
                    return false;
                } else {
                    myForm.submit();
                    return true;
                }
            }
        </script>
    </head>
    <body>
        <form action="" id="formfiltrar" name="formfiltrar" method="post">
            <table width="760px" cellpadding="0" cellspacing="0">
                <tr><th colspan="8">Criterios de Busqueda</th></tr>
                <tr><td colspan="8">&nbsp;</td></tr>
                <tr><td colspan="8">Numero: <input class="textbox" id="numero" name="numero" type="text" value="<?php echo $numero; ?>"></td></tr>
                <tr><td colspan="8">&nbsp;</td></tr>
                <tr><td colspan="8" style="text-align: right"><input type="submit" class="button" value="Filtrar" /></td></tr>
                <tr><td colspan="8">&nbsp;</td></tr>
            </table>
            <input type="hidden" name="accion" value="filtrar" />
        </form>
        <table width="760px" cellpadding="0" cellspacing="0">
            <tr><td colspan="8">&nbsp;</td></tr>
            <tr>
                <th style="width: 25px">&nbsp;</th>
                <th style="width: 90px">N&uacute;mero</th>
                <th>Nombre</th>
                <th>Observacion</th>
                <th>Usuario</th>
                <th style="width: 140px">Fecha</th>
                <th>Estado</th>
                <th>&nbsp;</th>
            </tr>
            <tr><td colspan="5">&nbsp;</td></tr><?php
            if (isset($_POST["accion"])) {
                $sql = sprintf("SELECT p.id, p.numero, p.estado, s.nombre, p.usr_anl, p.obs_anl, p.fec_anl FROM suscripciones_participantes p, suscripciones s WHERE p.numero=%s AND p.idSuscripcion=s.id;", GetSQLValueString($numero, "text"));
                $rs = mysql_query($sql, $conexion) or die(register_mysql_error("CS0002", mysql_error()));
                $i = 0;
                while ($row = mysql_fetch_array($rs)) {
                    ?><form action="" id="formactualizar" name="formactualizar" method="post">
                        <tr>
                            <td><?php echo ++$i; ?></td>
                            <td><?php echo $row ['numero']; ?></td>
                            <td><?php echo $row ['nombre']; ?></td>
                            <td><input type="text" maxlength="254" id="obs" name="obs"  value="<?php echo $row ['obs_anl']; ?>" <?php if ($row ['estado'] == 0) echo 'readonly'; ?> /></td>
                            <td><?php echo $row ['usr_anl']; ?></td>
                            <td><?php echo $row ['fec_anl']; ?></td>
                            <td><?php if ($row ['estado'] == 1) echo "Activo";
                    else echo "Inactivo"; ?></td>
                            <td style="text-align: center">
                                <input type="hidden" name="id" value="<?php echo $row ['id']; ?>" />
                                <input type="hidden" name="numero" value="<?php echo $numero; ?>" />
                                <input type="hidden" name="accion" value="actualizar" />
                                <input type="hidden" name="estado" value="<?php echo $row ['estado']; ?>" />
        <?php $type = ($row ['estado'] == 1) ? 'button' : 'hidden'; ?>
                                <input type="<?php echo $type; ?>" onclick="javascript: return validar(this.form)" class='button' value='Desactivar' />
                            </td>
                        </tr>
                        <tr><td colspan='8'>&nbsp;</td></tr>    
                        <tr><th colspan='8' style="text-align: center"><a href="menu.php">Menu</a></th></tr>   
                    </form><?php
                }
            }
            ?>
        </table>
    </body>
</html>