<?php
require_once('functions.php');
require_once('../connections/conexion.php');

session_start();
if (!isset($_SESSION['idAdmin'])) {
    header("Location: " . initPage());
    exit();
} else if (!permission(29)) {
    header("Location: " . initPage());
    exit();
}

mysql_select_db($database_conexion, $conexion);
if (isset($_POST["accion"])) {
    $idTelechat = $_POST['idTelechat'];
    $fecha = $_POST['fecha_reinicio'];
    $SQL = sprintf("UPDATE telechats_mensajes SET estado=NULL, fecha_reinicio=NULL WHERE estado=3 AND idTelechat=%s AND fecha_reinicio=%s", GetSQLValueString($idTelechat, "int"), GetSQLValueString($fecha, "date"));
    mysql_query($SQL, $conexion) or die(mysql_error());
} elseif (isset($_POST["idTelechat"])) {
    $idTelechat = $_POST['idTelechat'];
} else {
    $idTelechat = 0;
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>Recuperaci&oacute;n de Telechats</title>
        <link rel="stylesheet" type="text/css" href="../css/style.css" />
        <script language="javascript">
            function validar(form) {
                if (form.idTelechat.selectedIndex == -1) {
                    alert("Debe seleccionar un telechat");
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
        <form  id="formactualizar" name="formactualizar" method="post" action="">
            <table style="width: 220px" cellpadding="0" cellspacing="0">
                <tr><th colspan="2">Recuperaci�n de Telechats</th></tr>
                <tr><td colspan="2">&nbsp;</td></tr>     
                <tr>
                    <td>Telechat:</td>
                    <td><select name="idTelechat" id="idTelechat" onChange="this.form.submit()"><?php
                            $SQL = "SELECT id, nombre FROM telechats WHERE estado=1 ORDER BY nombre;";
                            $rs = mysql_query($SQL, $conexion) or die(mysql_error());
                            while ($row = mysql_fetch_array($rs)) {
                                ?><option value="<?php echo $row['id'] ?>" <?php if ($idTelechat == $row['id']) echo "selected"; ?>><?php echo $row['nombre'] ?></option><?php
                            }
                            ?></select></td>
                </tr>
                <tr><td colspan="2">&nbsp;</td></tr>  
                <tr>
                    <td>Reinicio(s):</td>
                    <td><select name="fecha_reinicio" id="fecha_reinicio"><?php
                            $SQL = sprintf("SELECT DISTINCT fecha_reinicio FROM telechats_mensajes WHERE estado=3 AND fecha_reinicio IS NOT NULL AND idTelechat=%s ORDER BY fecha_reinicio DESC", GetSQLValueString($idTelechat, "int"));
                            $rs = mysql_query($SQL, $conexion) or die(mysql_error());
                            while ($row = mysql_fetch_array($rs)) {
                                ?><option value="<?php echo $row['fecha_reinicio'] ?>"><?php echo $row['fecha_reinicio'] ?></option><?php
                            }
                            ?></select></td>
                </tr>
                <tr><td colspan="2">&nbsp;</td></tr>    
                <tr><td colspan="2" style="text-align: right"><input name="accion" type="submit" class="button" id="accion" value="Recuperar" onclick="return validar(this.form)" /></td></tr>    
                <tr><td colspan="2">&nbsp;</td></tr>    
                <tr><th colspan="2" style="text-align: center"><a href="menu.php">Menu</a></th></tr>   
            </table>
        </form>
    </body>
</html>