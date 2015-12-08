<?php
require_once('functions.php');
require_once('../connections/conexion.php');

session_start();
if (!isset($_SESSION['idAdmin'])) {
    header("Location: index.php");
    exit();
} else if (!permission(20)) {
    header("Location: index.php");
    exit();
}

if (isset($_POST["accion"])) {
    $idRifa = $_POST['idRifa'];
    $fecha = $_POST['fecha_reinicio'];
    $SQL = sprintf("UPDATE rifas_participantes SET estado=1, fecha_reinicio=NULL WHERE estado=0 AND idRifa=%s AND fecha_reinicio=%s", GetSQLValueString($idRifa, "int"), GetSQLValueString($fecha, "date"));
    mysql_query($SQL, $conexion) or die(mysql_error());
} elseif (isset($_POST["idRifa"])) {
    $idRifa = $_POST['idRifa'];
} else {
    $idRifa = 0;
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>Recuperaci�n de Rifas</title>
        <link rel="stylesheet" type="text/css" href="../css/style.css" />
        <script language="javascript">
            function validar(form) {
                if (form.idRifa.selectedIndex == -1) {
                    alert("Debe seleccionar una rifa");
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
                <tr><th colspan="2">Recuperaci�n de Rifas</th></tr>
                <tr><td colspan="0">&nbsp;</td></tr>     
                <tr>
                    <td>Suscripci&oacute;n:</td>
                    <td><select name="idRifa" id="idRifa" onChange="this.form.submit()"><?php
                            $SQL = "SELECT id, nombre FROM rifas WHERE estado=1 ORDER BY nombre";
                            $rs = mysql_query($SQL, $conexion) or die(mysql_error());
                            while ($row = mysql_fetch_array($rs)) {
                                ?><option value="<?php echo $row['id'] ?>" <?php if ($idRifa == $row['id']) echo "selected"; ?>><?php echo $row['nombre'] ?></option><?php
                            }
                            ?></select></td>
                </tr>
                <tr><td colspan="2">&nbsp;</td></tr>  
                <tr>
                    <td>Reinicio(s):</td>
                    <td><select name="fecha_reinicio" id="fecha_reinicio"><?php
                            $SQL = sprintf("SELECT DISTINCT fecha_reinicio FROM rifas_participantes WHERE estado=0 AND fecha_reinicio IS NOT NULL AND idRifa=%s ORDER BY fecha_reinicio DESC", GetSQLValueString($idRifa, "int"));
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