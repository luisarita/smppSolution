<?php
require_once('functions.php');
require_once('../connections/conexion.php');

session_start();
if (!isset($_SESSION['idAdmin'])) {
    header("Location: " . initPage());
    exit();
} else if (!permission(18)) {
    header("Location: " . initPage());
    exit();
}

if (isset($_POST["accion"])) {
    if (isset($_POST['activo']) && $_POST['activo'] == 1)
        $activo = 1;
    else
        $activo = 0;
    if ($_POST["accion"] == "guardar") {
        $result = mysql_query("INSERT INTO aniversarios (nombre, numero, activa, mensaje, usuario, clave) VALUES ('" . $_POST['nombre'] . "','" . $_POST['numero'] . "'," . $activo . ",'" . $_POST['mensaje'] . "','" . $_POST['usuario'] . "','" . $_POST['clave'] . "') ", $conexion) or die(mysql_error());
    } elseif ($_POST["accion"] == "modificar") {
        $result = mysql_query("UPDATE aniversarios SET nombre='" . $_POST['nombre'] . "', numero='" . $_POST['numero'] . "', activa=" . $_POST['activa'] . ", mensaje='" . $_POST['mensaje'] . "',usuario='" . $_POST['usuario'] . "',clave = '" . $_POST['clave'] . "',activo = " . $activo . " where id = " . $_POST["id"], $conexion) or die(mysql_error());
    }
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <meta http-equiv="Refresh">
            <title>Aniversarios</title>
            <link rel="stylesheet" type="text/css" href="../css/style.css" />
            <script language="javascript">
                function validar(forma) {
                    if (forma.nombre.value == "") {
                        alert("El nombre no puede ser nulo");
                        return false;
                    } else if (forma.numero.selectedIndex == -1) {
                        alert("Debe seleccionar al menos un n�mero");
                        return false;
                    } else if (forma.mensaje.value == "") {
                        alert("El mensaje no puede ser nulo");
                        return false;
                    } else if (forma.usuario.value == "") {
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
    <body><?php
        if (isset($_GET["id"])) {
            $res_opcion = mysql_query("SELECT id, nombre, numero, activa, mensaje, usuario, clave FROM aniversarios WHERE id=" . $_GET["id"], $conexion);
            $row_opcion = mysql_fetch_array($res_opcion);
        }
        ?>
        <form name="form1" method="post">
            <table cellpadding="1" cellspacing="1" width="220px">
                <tr><th colspan="2">Aniversarios</th></tr>
                <tr><td colspan="2">&nbsp;</td></tr>
                <tr>
                    <td>Nombre:</td>
                    <td><input class="textbox-small" type="text" name="nombre" value="<?php echo (isset($row_opcion['nombre'])) ? $row_opcion['nombre'] : ''; ?>"></td>
                </tr><tr>
                    <td>Numero:</td>
                    <td><select name="numero" class="textbox-small" id="numero" ><?php
                            $res_datos = mysql_query("select numero from numeros ", $conexion) or die(mysql_error());
                            while ($row_datos = mysql_fetch_array($res_datos)) {
                                ?><option value = "<?php echo $row_datos['numero'] ?>" selected="selected" <?php if (isset($row_opcion['numero']) && ($row_datos['numero'] == $row_opcion['numero'])) echo "selected"; ?> ><?php echo $row_datos['numero']; ?></option><?php
                            }
                            ?></select>
                    </td>
                </tr><tr>
                    <td>Activo:</td>
                    <td ><input class="checkbox" type="checkbox" name="activo" <?php if (isset($row_opcion['activa']) && $row_opcion['activa'] == 1) echo 'checked'; ?> value='1'  /></td>       
                </tr><tr>
                    <td>Mensaje:</td>
                    <td><input class="textbox-small" type="text" name="mensaje" value="<?php echo (isset($row_opcion['mensaje'])) ? $row_opcion['mensaje'] : ''; ?>">%N</td>
                </tr><tr>
                    <td>Usuario:</td>  
                    <td><input class="textbox-small" type="text" name="usuario" value="<?php echo (isset($row_opcion['usuario'])) ? $row_opcion['usuario'] : ''; ?>"></td>
                </tr><tr>
                    <td>Clave:</td>
                    <td><input class="textbox-small" type="password" name="clave" value="<?php echo (isset($row_opcion['clave'])) ? $row_opcion['clave'] : ''; ?>"></td>
                </tr>
                <tr><td colspan="2">&nbsp;</td></tr>     
                <tr><td colspan="2" style="text-align: right">
                        <input type="submit" class="button" name="Submit" value="Guardar Datos" onClick="return validar(this.form)">
                            <input type="hidden" name="accion" value="<?php if (isset($_GET["id"])) echo "modificar";
                            else echo "guardar"; ?>">
                                <input type="hidden" name="id" value="<?php if (isset($_GET["id"])) echo $_GET["id"];
                            else echo ''; ?>">
                                    </td></tr>
                                    <tr><td colspan="2">&nbsp;</td></tr>     
                                    </table>
                                    </form>
                                    <form id="formeliminar" name="formeliminar" method="post">
                                        <table cellpadding="1" cellspacing="1" width="220px">
                                            <tr>
                                                <th>Nombre</th>
                                                <th>Estado</th>
                                                <th colspan="2">Acci�n</th>
                                            </tr>
                                            <tr><td colspan="7">&nbsp;</td></tr><?php
                            $res_opciones = mysql_query("SELECT id,nombre,numero,activa,mensaje,usuario,activa FROM aniversarios ORDER BY id", $conexion) or die(mysql_error());
                            while ($row_opciones = mysql_fetch_array($res_opciones)) {
                                ?><tr>
                                                    <td><?php echo $row_opciones['nombre']; ?></td>
                                                    <td><?php if ($row_opciones['activa'] == 1) echo 'Activo';
                                else echo 'Inactivo'; ?></td>
                                                    <td rowspan="1" style="text-align: center; vertical-align: middle">
                                                        <a href='?id=<?php echo $row_opciones['id']; ?>'>Modificar</a>
                                                    </td>
                                                    <td  rowspan="1" style="text-align: center; vertical-align: middle">
                                                        <a href='18.aniversarios.cargar.php?id=<?php echo $row_opciones['id']; ?>'>Cargar</a>
                                                    </td>
                                                </tr><?php
                                        }
                            ?><tr><td colspan="3">&nbsp;</td></tr>
                                        </table>
                                    </form>
                                    </body>
                                    </html>