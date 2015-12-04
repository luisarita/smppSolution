<?php
require_once('functions.php');
require_once('../connections/conexion.php');

session_start();
if (!isset($_SESSION['idAdmin'])) {
    header("Location: " . initPage());
    exit();
} else if (!permission(19)) {
    header("Location: " . initPage());
    exit();
}

$aplicacion = 'SMPPAPP';
$nombre = 'SMS_NUMBER';
$valor = (isset($_POST['valor'])) ? $_POST['valor'] : '';
$WshShell = new COM("WScript.Shell");

if (isset($_POST["accion"]) && $_POST["accion"] == 'leer') {
    $valor = put_registre($aplicacion, $nombre, $valor);
}
$valor = rtv_registre($aplicacion, $nombre);

function rtv_registre($aplicacion, $nom) {
    global $WshShell;
    try {
        $r = "HKEY_LOCAL_MACHINE\\SOFTWARE\\" . $aplicacion . "\\" . $nom;
        $valor = $WshShell->RegRead($r);
        return($valor);
    } catch (Exception $e) {
        print_r($e->getMessage());
        //echo $e->message;
    }
}

function put_registre($aplicacion, $nom, $valor, $tipus = "REG_SZ") {
    global $WshShell;
    $r = "HKEY_LOCAL_MACHINE\\SOFTWARE\\" . $aplicacion . "\\" . $nom;
    $valor = $WshShell->RegWrite($r, $valor, $tipus);
    return($valor);
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <meta http-equiv="Refresh">
            <link rel="stylesheet" type="text/css" href="../css/style.css" />
            <title>Actualización de Numeros para Corrida</title>
    </head>   
    <body >
        <form name="form1" method="post">
            <table width="220px" cellpadding="0" cellspacing="0">
                <tr><th colspan="2">Actualización de Numeros para Corrida</th></tr>
                <tr><td colspan="2">&nbsp;</td></tr>     
                <tr><td colspan="2"><b>Instrucciones</b>: Ingrese uno o m&aacute;s n&uacute;meros separados por comas. Asegurese de incluir el código de país. Debe reiniciar el servicio para que los cambios se apliquen</td></tr>     
                <tr><td colspan="2">&nbsp;</td></tr>     
                <tr>  
                    <td>N&uacute;meros para Corrida:</td>  
                    <td><textarea name="valor" rows="8" class="textbox-fill"><?php echo $valor ?></textarea></td>
                </tr>
                <tr><td colspan="2">&nbsp;</td></tr>     
                <tr>
                    <td colspan="2" style="text-align: right">
                        <input type="submit" class="button" name="Submit" value="Actualizar" onClick="">
                            <input type="hidden" name="accion" value="leer" >
                                </td>
                                </tr>
                                <tr><td colspan="2">&nbsp;</td></tr>     
                                <tr><th colspan="2" style="text-align: center"><a href="menu.php">Menu</a></th></tr>
                                </table>
                                </form>
                                </body>
                                </html>