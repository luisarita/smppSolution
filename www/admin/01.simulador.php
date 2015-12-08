<?php
require_once('../connections/conexion.php');
require_once('../functions/db.php');
require_once('functions.php');

session_start();
if (!isset($_SESSION['idAdmin'])) {
    header("Location: " . initPage());
    exit();
} else if (!permission(1)) {
    header("Location: " . initPage());
    exit();
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
    $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if (isset($_POST['numero']) && (strlen($_POST['numero']) < 10 || strlen($_POST['numero']) > 11 )) {
    $msg = setAlert("El número ingresado no es válido");
} else if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "updateForm")) {
    $updateSQL = sprintf("INSERT INTO comunicacion_recibida_tabla (PROCESADO,DATOS_RECIBIDOS,TELEFONO_ORIGEN_DE_RESPUE,TELEFON_DESTINO_DE_RESPU,FECHA_DE_RECEPCION,ORIGEN_DE_RESPUESTA_RECIB) VALUES('N',%s,%s,%s,NOW(),2)", GetSQLValueString($_POST['mensaje'], "text"), GetSQLValueString($_POST['numero'], "text"), GetSQLValueString($_POST['numero_salida'], "text"));
    mysql_query($updateSQL, $conexion) or die(register_mysql_error("SI001", mysql_error()));
    $insertSQL = sprintf("INSERT INTO mantenimiento_simulaciones (mensaje, numero, numero_salida, fecha, usuario) VALUES (%s,%s,%s,NOW(),%s)", GetSQLValueString($_POST['mensaje'], "text"), GetSQLValueString($_POST['numero'], "text"), GetSQLValueString($_POST['numero_salida'], "text"), $_SESSION['idAdmin']);
    mysql_query($insertSQL, $conexion) or die(register_mysql_error("SI002", mysql_error()));
}

$query_rsNumeros = "SELECT numero FROM numeros";
$rsNumeros = mysql_query($query_rsNumeros, $conexion) or die(register_mysql_error("SI003", mysql_error()));
$row_rsNumeros = mysql_fetch_assoc($rsNumeros);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Simulador de SMS</title>
        <link type="text/css" rel="stylesheet" href="../css/style.css"  />
    </head>

    <body <?php if (isset($msg)) echo $msg; ?>>
        <form id="updateForm" name="updateForm" method="POST" action="<?php echo $editFormAction; ?>">
            <table cellpadding="1" cellspacing="1" width="220px">
                <tr>
                    <th colspan="2" scope="col">Simulador de SMS</th>
                </tr><tr>
                    <td>Número Receptor:</td>
                    <td><select name="numero_salida" id="numero_salida" class="textbox-small"><?php
                        do {
    ?><option value="<?php echo $row_rsNumeros['numero']; ?>" <?php if (isset($_POST['numero_salida']) && $row_rsNumeros['numero'] == $_POST['numero_salida']) echo "selected"; ?>><?php echo $row_rsNumeros['numero'] ?></option><?php
} while ($row_rsNumeros = mysql_fetch_assoc($rsNumeros));
$rows = mysql_num_rows($rsNumeros);
if ($rows > 0) {
    mysql_data_seek($rsNumeros, 0);
    $row_rsNumeros = mysql_fetch_assoc($rsNumeros);
}
?></select>
                    </td>
                </tr>
                <tr>
                    <td>Número Emisor:</td>
                    <td><input type="text" name="numero" id="numero" class="textbox-small" value="<?php if (isset($_POST['numero'])) echo $_POST['numero']; ?>" /></td>
                </tr>
                <tr>
                    <td>Mensaje:</td>
                    <td><textarea name="mensaje" id="mensaje" class="textbox-small" rows="4"></textarea></td>
                </tr><tr>
                    <td colspan="2" style="text-align:right"><input class="button" type="submit" name="submit" id="submit" value="Enviar" /></td>
                </tr>     
                <tr><td colspan="2">&nbsp;</th></tr>   
                <tr><th colspan="2" style="text-align: center"><a href="menu.php">Menu</a></th></tr>   
            </table>
            <input type="hidden" name="MM_update" value="updateForm" />
        </form>
    </body>

</html>
<?php
mysql_free_result($rsNumeros);