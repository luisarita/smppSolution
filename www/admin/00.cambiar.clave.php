<?php
require_once('functions.php');
require_once('../connections/conexion.php');

session_start();
if (!isset($_SESSION['idAdmin'])) {
    header("Location: " . initPage());
    exit();
}

$id = $_SESSION['idAdmin'];

if (isset($_POST["accion"]) && $_POST["accion"] == "guardar") {
    $res_opcion = mysql_query("SELECT COUNT(*) AS existe FROM mantenimiento_usuarios WHERE id=" . $id . " AND clave=MD5('" . $_POST['claveanterior'] . "')", $conexion);
    $row_opcion = mysql_fetch_array($res_opcion);
    $encontro = $row_opcion['existe'];
    if ($encontro == 0) {
        $alert = "La contrase�a anterior es incorrecta";
    }
    $result = mysql_query("UPDATE mantenimiento_usuarios SET clave=MD5('" . $_POST['clavenueva'] . "') WHERE id=" . $id, $conexion) or die(mysql_error());
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <meta http-equiv="Refresh">
            <title>Cambiar Clave</title>
            <link rel="stylesheet" type="text/css" href="../css/style.css" />
            <script language="javascript">
                function validar(forma) {
                    if (forma.claveanterior.value === "") {
                        alert("La clave anterior no puede ser nula");
                        return false;
                    } else if (forma.clavenueva.value === "") {
                        alert("La clave nueva no puede ser nula");
                        return false;
                    } else if (forma.clavenuevaconf.value === "") {
                        alert("La confirmaci�n de clave nueva no puede ser nula");
                        return false;
                    } else if (forma.clavenuevaconf.value !== forma.clavenueva.value) {
                        alert("No coinciden la clave nueva y su confirmaci�n");
                        return false;
                    } else if ((forma.clavenuevaconf.value === forma.clavenueva.value) && (forma.claveanterior.value === forma.clavenueva.value)) {
                        alert("La clave nueva debe de ser diferente a la clave anterior");
                        return false;
                    }
                    return true;
                }
            </script>
    </head>   
    <body <?php if (isset($alert) && $alert != "") echo "onLoad='javascript: alert(\"$alert\")'"; ?>>
        <form name="form1" method="post">
            <table width="220px" cellpadding="0" cellspacing="0">
                <tr><th colspan="2">Cambio de Contraseña</th></tr>
                <tr><td colspan="2">&nbsp;</td></tr>     
                <tr>  
                    <td>Clave Anterior:</td>  
                    <td><input class="textbox-small" type="password" name="claveanterior" value=""></td>
                </tr><tr>  
                    <td>Clave Nueva:</td>  
                    <td><input class="textbox-small" type="password" name="clavenueva" value=""></td>
                </tr><tr>  
                    <td>Confirmar Clave:</td>  
                    <td><input class="textbox-small" type="password" name="clavenuevaconf" value=""></td>
                </tr>      
                <tr><td colspan="2">&nbsp;</td></tr>     
                <tr>
                    <td colspan="2" style="text-align: right">
                        <input type="submit" class="button" name="Submit" value="Actualizar" onClick="return validar(this.form)" />
                        <input type="hidden" name="accion" value="guardar" />
                    </td>
                </tr>
                <tr><td colspan="2">&nbsp;</td></tr>
                <tr><th colspan="2" style="text-align: center"><a href="menu.php">Menu</a></th></tr>   
            </table>
        </form>
    </body>
</html>