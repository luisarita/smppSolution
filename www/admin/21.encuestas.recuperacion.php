<?php
require_once('functions.php');
require_once('../connections/conexion.php');

session_start();
if (!isset($_SESSION['idAdmin'])) {
    header("Location: index.php");
    exit();
} else if (!permission(21)) {
    header("Location: index.php");
    exit();
}

mysql_select_db($database_conexion, $conexion);
if (isset($_POST["accion"])) {
    $idEncuesta = $_POST['idEncuesta'];
    $fecha = $_POST['fecha_reinicio'];
    $SQL = sprintf("UPDATE encuestas_participantes SET estado=1, fecha_reinicio=NULL WHERE estado=0 AND idEncuesta=%s AND fecha_reinicio=%s", GetSQLValueString($idEncuesta, "int"), GetSQLValueString($fecha, "date"));
    mysql_query($SQL, $conexion) or die(mysql_error());
} elseif (isset($_POST["idEncuesta"])) {
    $idEncuesta = $_POST['idEncuesta'];
} else {
    $idEncuesta = 0;
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>Recuperaci�n de Encuestas</title>
        <link rel="stylesheet" type="text/css" href="../css/style.css" />
        <script language="javascript">
            function validar(form) {
                if (form.idEncuesta.selectedIndex == -1) {
                    alert("Debe seleccionar una encuesta");
                    return false;
                } else if (form.fecha_reinicio.selectedIndex == -1) {
                    alert("Debe seleccionar una fecha");
                    return false;
                }
                return true;
            }
        </script>
    </head>
    <body>
        <form  id="formactualizar" name="formactualizar" method="post" >
            <table width="220px" cellpadding="0" cellspacing="0">
                <tr><th colspan="2">Recuperaci�n de Encuestas</th></tr>
                <tr><td colspan="0">&nbsp;</td></tr>     
                <tr>
                    <td>Encuesta:</td>
                    <td><select name="idEncuesta" id="idEncuesta" onChange="this.form.submit()"><?php
                            $SQL = "SELECT id, nombre FROM encuestas WHERE estado=1 ORDER BY nombre";
                            $rs = mysql_query($SQL, $conexion) or die(mysql_error());
                            while ($row = mysql_fetch_array($rs)) {
                                ?><option value="<?php echo $row['id'] ?>" <?php if ($idEncuesta == $row['id']) echo "selected"; ?>><?php echo $row['nombre'] ?></option><?php
                            }
                            ?></select></td>
                </tr>
                <tr><td colspan="2">&nbsp;</td></tr>  
                <tr>
                    <td>Reinicio(s):</td>
                    <td><select name="fecha_reinicio" id="fecha_reinicio"><?php
                            $SQL = sprintf("SELECT DISTINCT fecha_reinicio FROM encuestas_participantes WHERE estado=0 AND fecha_reinicio IS NOT NULL AND idEncuesta=%s ORDER BY fecha_reinicio DESC", GetSQLValueString($idEncuesta, "int"));
                            $rs = mysql_query($SQL, $conexion) or die(mysql_error());
                            while ($row = mysql_fetch_array($rs)) {
                                ?><option value="<?php echo $row['fecha_reinicio'] ?>"><?php echo $row['fecha_reinicio'] ?></option><?php
                            }
                            ?></select></td>
                </tr>
                <tr><td colspan="2">&nbsp;</td></tr>    
                <tr><td colspan="2" style="text-align: right"><input name="accion" type="submit" class="button" id="" value="Recuperar" onclick="return validar(this.form)" /></td></tr>    
                <tr><td colspan="2">&nbsp;</td></tr>
                <tr><th colspan="2" style="text-align: center"><a href="menu.php">Menu</a></th></tr>       
            </table>
        </form>
    </body>
</html>