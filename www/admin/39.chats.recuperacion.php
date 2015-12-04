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

mysql_select_db($database_conexion, $conexion);
if (isset($_POST["accion"])) {
    $idChat = $_POST['idChat'];
    $fecha = $_POST['fecha_reinicio'];
    $SQL = sprintf("UPDATE chats_participantes SET estado=1, fecha_reinicio=NULL WHERE estado=0 AND idChat=%s AND fecha_reinicio=%s;", GetSQLValueString($idChat, "int"), GetSQLValueString($fecha, "date"));
    mysql_query($SQL, $conexion) or die();
} elseif (isset($_POST["idChat"])) {
    $idChat = $_POST['idChat'];
} else {
    $idChat = 0;
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>Recuperaci�n de Chats</title>
        <link rel="stylesheet" type="text/css" href="../css/style.css" />
        <script language="javascript">
            function validar(form) {
                if (form.idChat.selectedIndex == -1) {
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
                <tr><th colspan="2">Recuperaci�n de Chats</th></tr>
                <tr><td colspan="0">&nbsp;</td></tr>     
                <tr>
                    <td>Suscripci&oacute;n:</td>
                    <td>
                        <select name="idChat" id="idChat" onChange="this.form.submit()">
                            <option></option><?php
                            $SQL = "SELECT id, nombre FROM chats WHERE estado=1 ORDER BY nombre";
                            $rs = mysql_query($SQL, $conexion) or die();
                            while ($row = mysql_fetch_array($rs)) {
                                ?><option value="<?php echo $row['id'] ?>" <?php if ($idChat == $row['id']) echo "selected"; ?>><?php echo $row['nombre'] ?></option><?php
                            }
                            ?></select></td>
                </tr>
                <tr><td colspan="2">&nbsp;</td></tr>  
                <tr>
                    <td>Reinicio(s):</td>
                    <td><select name="fecha_reinicio" id="fecha_reinicio"><?php
                            $SQL = sprintf("SELECT DISTINCT fecha_reinicio FROM chats_participantes WHERE estado=0 AND fecha_reinicio IS NOT NULL AND idChat=%s ORDER BY fecha_reinicio DESC", GetSQLValueString($idChat, "int"));
                            $rs = mysql_query($SQL, $conexion) or die();
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