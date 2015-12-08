<?php
require_once('../connections/conexion.php');
require_once('../functions/functions.php');
require_once('../functions/db.php');
require_once('functions.php');

session_start();
/* if (!isset($_SESSION['idAdmin'])){
  header("Location: " . initPage());
  exit();
  } else if ( !permission( 2 ) ){
  header("Location: " . initPage());
  exit();
  } */
$_SESSION['usuario'] = "admin";
if (isset($_POST['numero']) && isset($_POST['mensaje_respuesta']) && strlen($_POST['mensaje_respuesta']) > 0) {
    $tipo = (isset($_POST['tipo']) && $_POST['tipo'] == 1) ? 1 : 0;
    $monitoreable = (isset($_POST['monitoreable']) && $_POST['monitoreable'] == 1) ? 1 : 0;
    $mensaje = GetSQLValueString($_POST['mensaje_respuesta'], "text");
    $numero = GetSQLValueString($_POST['numero'], "text");
    $usuario = GetSQLValueString($_SESSION['usuario'], "text");
    $espera = GetSQLValueString($_POST['txtEspera'], "int");
    $sql = sprintf("
   INSERT INTO numeros (numero,tipo,monitoreable,mensaje_respuesta,usr_ing,fec_ing, espera) VALUES (%s,%s,%s,%s,%s,NOW(),%s)
   ON DUPLICATE KEY UPDATE tipo=%s, monitoreable=%s, mensaje_respuesta=%s, usr_act=%s, fec_act=NOW(), espera=%s;", $numero, $tipo, $monitoreable, $mensaje, $usuario, $espera, $tipo, $monitoreable, $mensaje, $usuario, $espera);
    mysql_query($sql, $conexion) or die(register_mysql_error("CMR0002", mysql_error()));
}

$numero = (isset($_POST['numero'])) ? $_POST['numero'] : ((isset($_GET['numero'])) ? $_GET['numero'] : -1);

$sql = sprintf("SELECT numero, tipo, monitoreable, mensaje_respuesta, espera FROM numeros WHERE numero='%s';", $numero);
$rs = mysql_query($sql, $conexion) or die(register_mysql_error("CMR0003" . mysql_error(), mysql_error()));
if (!$row = mysql_fetch_array($rs)) {
    $sql = sprintf("SELECT numero, tipo, monitoreable, mensaje_respuesta, espera FROM numeros WHERE numero=(SELECT MIN(numero) FROM numeros);", $numero);
    $rs = mysql_query($sql, $conexion) or die(register_mysql_error("CMR0004", mysql_error()));
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Administraci&oacute;n</title>
        <link type="text/css" rel="stylesheet" href="../css/style.css"  />
        <script>
            function validarNumero(valor) {
                valor = parseInt(valor);
                if (isNaN(valor)) {
                    return 0;
                } else {
                    return valor;
                }
            }
        </script>
    </head>

    <body>
        <form id="formMantenimiento" name="formMantenimiento" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <table cellpadding="1" cellspacing="1" width="220px">
                <tr><th colspan="2">Configuraci&oacute;n de Números</th></tr>
                <tr><td colspan="2">&nbsp;</td></tr>
                <tr>
                    <td>Número:</td>
                    <td><?php
                        if (isset($_POST['button']) && $_POST['button'] == "Nuevo") {
                            ?><input type="text" value="" name="numero" class="textbox-small" maxlength="<?php echo numOutLength(); ?>" /><?php
                        } else {
                            echo printSelectNumeros($row['numero'], "onchange='document.location=\"?numero=\" + this.value'");
                        }
                        ?></td>
                </tr><tr>
                    <td>Espera:</td>
                    <td><?php
                        if (isset($_POST['button']) && $_POST['button'] == "Nuevo") {
                            ?><input type="text" name="txtEspera" class="textbox-medium" value="20" onkeyup="this.value = validarNumero(this.value)"/><?php
                        } else {
                            ?><input type="text" name="txtEspera" class="textbox-medium" value="<?php echo $row["espera"]; ?>" onkeyup="this.value = validarNumero(this.value)"/><?php
                        }
                        ?>
                    </td>
                </tr><tr>
                    <td>Respuesta a Mensajes con clave erronea:</td>
                    <td><textarea name="mensaje_respuesta" id="mensaje_respuesta" rows="10"><?php echo $row['mensaje_respuesta'] ?></textarea></td>
                </tr><tr>
                    <td>Cobrable (MT):</td>
                    <td style="text-align: right"><input class="checkbox" type="checkbox" name="tipo" <?php if ($row['tipo'] == 1) echo "checked"; ?> value='1' /></td>
                </tr><tr>
                    <td>Monitoreable:</td>
                    <td style="text-align: right"><input class="checkbox" type="checkbox" name="monitoreable" <?php if ($row['monitoreable'] == 1) echo "checked"; ?> value='1' /></td>
                </tr>
                <tr><td colspan="2" style="text-align: right"><input class="button" type="submit" name="button" id="button" value="Nuevo" /> <input class="button" type="submit" name="button" id="button" value="Salvar" /></td></tr>
                <tr><td colspan="2">&nbsp;</td></tr>
                <tr><th colspan="2" style="text-align: center"><a href="menu.php">Menu</a></th></tr>   
            </table>
        </form>
    </body>