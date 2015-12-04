<?php
require_once('functions.php');
require_once('../connections/conexion.php');

session_start();
if (!isset($_SESSION['idAdmin'])) {
    header("Location: " . initPage());
    exit();
} else if (!permission(15)) {
    header("Location: " . initPage());
    exit();
}

mysql_select_db($database_conexion, $conexion);

if (isset($_POST["accion"])) {
    $activo = (isset($_POST['activo']) && $_POST['activo'] == 1) ? 1 : 0;
    if ($_POST["accion"] == "guardar") {
        mysql_query("INSERT INTO mantenimiento_usuarios (usuario,clave,activo) VALUES ('" . $_POST['usuario'] . "',MD5('" . $_POST['clave'] . "')," . $activo . ")", $conexion) or die(mysql_error());
    } else if ($_POST["accion"] == "modificar") {
        mysql_query("UPDATE mantenimiento_usuarios SET usuario='" . $_POST['usuario'] . "', clave=MD5('" . $_POST['clave'] . "'), activo=" . $activo . " WHERE id=" . $_POST["id"], $conexion) or die(mysql_error());
    }
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <meta http-equiv="Refresh">
            <title>Usuarios</title>
            <link rel="stylesheet" type="text/css" href="../css/style.css" />
            <script language="javascript">
                function validar(forma) {
                    if (forma.usuario.value == "") {
                        alert("El usuario no puede ser nulo");
                        return false;
                    } else if (forma.clave.value == "") {
                        alert("La clave no puede ser nula");
                        return false;
                    }
                    return true;
                }
            </script>
    </head>   
    <body> 
        <?php
        if (isset($_GET["id"])) {
            $res_opcion = mysql_query("SELECT id, usuario, clave, activo FROM mantenimiento_usuarios WHERE id=" . $_GET["id"] . " ORDER BY usuario", $conexion);
            $row_opcion = mysql_fetch_array($res_opcion);
        }
        ?>
        <table width="220px" cellpadding="0" cellspacing="0">
            <form name="form1" method="post">
                <tr><th colspan="4">Mantenimiento de Usuarios</th></tr>
                <tr><td colspan="4">&nbsp;</td></tr>     
                <tr>
                    <td>Usuario:</td>  
                    <td colspan="3"><input class="textbox-small" type="text" name="usuario" value="<?php echo (isset($row_opcion['usuario'])) ? $row_opcion['usuario'] : ''; ?>"></td>
                </tr><tr>
                    <td>Clave:</td>
                    <td colspan="3"><input class="textbox-small" type="password" name="clave" value=""></td>
                </tr><tr>
                    <td>Estado:</td>
                    <td colspan="3"><input class="checkbox" type="checkbox" name="activo" <?php if (isset($row_opcion['activo']) && $row_opcion['activo'] == 1) echo 'checked'; ?> value = '1'  /></td>
                </tr>     
                <tr><td colspan="4">&nbsp;</td></tr>     
                <tr>
                    <td colspan="4" style="text-align: right">
                        <input type="submit" class="button" name="Submit" value="Guardar Datos" onClick="return validar(this.form)">
                            <input type="hidden" name="accion" value="<?php if (isset($_GET["id"])) echo "modificar";
        else echo "guardar"; ?>">
                                <input type="hidden" name="id" value="<?php if (isset($_GET["id"])) echo $_GET["id"];
        else echo ''; ?>">
                                    </td>
                                    </tr>
                                    <tr><td colspan="4">&nbsp;</td></tr>     
                                    </form>
                                    <tr><td colspan="4" style="text-align: right"><?php if (isset($_GET["id"])) { ?><button class="button" onclick="window.location = '16.permisos.php?id=<?php echo $_GET["id"]; ?>'">Permisos</button><?php } ?></td></tr>
                                    <tr><td colspan="4">&nbsp;</td></tr>     
                                    <form id="formeliminar" name="formeliminar" method="post">
                                        <tr>
                                            <th>Usuario</th>
                                            <th>Estado</th>
                                            <th>Acci�n</th>
                                        </tr>
                                        <tr><td colspan="4">&nbsp;</td></tr><?php
        $res_opciones = mysql_query("SELECT id, usuario, activo FROM mantenimiento_usuarios ORDER BY usuario", $conexion) or die(mysql_error());
        while ($row_opciones = mysql_fetch_array($res_opciones)) {
            ?><tr>
                                                <td><?php echo $row_opciones['usuario']; ?></td>
                                                <td style="text-align: center; vertical-align: middle"><?php if ($row_opciones['activo'] == 1) echo 'Activo';
            else echo 'Inactivo'; ?></td>
                                                <td style="text-align: center; vertical-align: middle">
                                                    <a href='?id=<?php echo $row_opciones['id']; ?>'>Modificar</a>
                                            </tr><?php }
        ?>
                                        <tr><td colspan="4">&nbsp;</td></tr>
                                        <tr><th colspan="4" style="text-align: center"><a href="menu.php">Menu</a></th></tr>   
                                    </form>
                                    </table>
                                    </body>
                                    </html>