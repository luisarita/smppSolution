<?php
require_once('../connections/conexion.php');
require_once('../functions/functions.php');
require_once('../functions/db.php');
require_once('functions.php');

session_start();
if (!isset($_SESSION['idAdmin'])) {
    header("Location: " . initPage());
    exit();
} else if (!permission(42)) {
    header("Location: " . initPage());
    exit();
}

$erroneos = "";
$idRifa = (isset($_POST['id'])) ? $_POST['id'] : -1;
if (isset($_POST["actualizar"])) {
    foreach (explode("\n", $_POST['numero']) as $key => $numero) {
        $numero = trim($numero);
        if (validarNumero($numero)) {
            $SQL = sprintf("REPLACE INTO rifas_participantes (idRifa, numero, fecha, estado) VALUES (%s,%s,NOW(), 1);", $idRifa, $numero);
            mysql_query($SQL, $conexion) or die(register_mysql_error("RCL0001", mysql_error()));
        } else {
            if ($erroneos != "")
                $erroneos .= "\n";
            $erroneos .= $numero;
        }
    }
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>Suscripciones - Carga por Lote</title>
        <link rel="stylesheet" type="text/css" href="../css/style.css" />
        <script language="javascript">
            function validar(forma) {
                if (document.forma.suscripcion.selectedIndex == -1) {
                    alert("Debe de seleccionar una suscripci�n");
                    return false;
                }
                return true;
            }
        </script>
    </head>
    <body>
        <form  id="formactualizar" name="formactualizar" method="post" >
            <table width="220px" cellpadding="0" cellspacing="0">
                <tr><th colspan="2">Carga por Lotes</th></tr>
                <tr><td colspan="0">&nbsp;</td></tr>     
                <tr>
                    <td>Rifa:</td>
                    <td><select name="id" id="id" onChange="this.form.submit()"><?php
                            $res_datos = mysql_query("SELECT id, nombre FROM rifas WHERE estado=1 ORDER BY nombre", $conexion) or die(register_mysql_error("SCL0003", mysql_error()));
                            while ($row_datos = mysql_fetch_array($res_datos)) {
                                $selected = ($idRifa == $row_datos['id']) ? "selected" : "";
                                ?><option value="<?php echo $row_datos['id'] ?>" <?php echo $selected ?> ><?php echo $row_datos['nombre'] ?></option><?php
                            }
                            ?></select></td>
                </tr>
                <tr><td colspan="2">&nbsp;</td></tr>  
                <tr>
                    <td>Numero(s):</td>
                    <td><textarea name="numero" rows='8' class="textbox-fill"><?php echo $erroneos; ?></textarea></td>
                </tr>
                <tr><td colspan="2">&nbsp;</td></tr>    
                <tr><td colspan="2" style="text-align: right">
                        <input name = 'actualizar' type="submit" class="button" value="Agregar" onClick="return validar(this.form)" />
                    </td></tr>
                <tr><td colspan="2">&nbsp;</td></tr>    
                <tr><th colspan="2" style="text-align: center"><a href="menu.php">Menu</a></th></tr>   
            </table>
        </form>
    </body>
</html>