<?php
require_once('../connections/conexion.php');
require_once('../functions/functions.php');
require_once('../functions/db.php');
require_once('functions.php');

session_start();
if (!isset($_SESSION['idAdmin'])) {
    header("Location: " . initPage());
} else if (!permission(13)) {
    header("Location: " . initPage());
}

if ((isset($_POST["MM_delete"])) && ($_POST["MM_delete"] == "deleteForm")) {
    $numero = trim($_POST['numero']);
    if (strlen($numero) == numLength()) { //echo $numero;
        $insertSQL = sprintf("DELETE FROM mantenimiento_bloqueos WHERE numero=%s", GetSQLValueString($numero, "text"));
        mysql_query($insertSQL, $conexion) or die(register_mysql_error("BL0001", mysql_error()));
    }
} elseif ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "updateForm")) {
    $text = trim($_POST['numero']);
    $textAr = explode("\r\n", $text);
    $textAr = array_filter($textAr, 'trim');

    foreach ($textAr as $index => $numero) {
        $numero = trim($numero);
        if (strlen($numero) == numLength()) { //echo $numero;
            $insertSQL = sprintf("REPLACE INTO mantenimiento_bloqueos (numero, fechaIngreso) VALUES (%s,NOW())", GetSQLValueString($numero, "text"));
            mysql_query($insertSQL, $conexion) or die(register_mysql_error("BL0001", mysql_error()));

            $updateSQL = sprintf("UPDATE suscripciones_participantes SET estado=0 WHERE numero=%s;", GetSQLValueString($numero, "text"));
            mysql_query($updateSQL, $conexion) or die(register_mysql_error("BL0002", mysql_error()));
        }
    }
}

$sql = "SELECT COUNT(DISTINCT numero) AS conteo FROM mantenimiento_bloqueos ORDER BY numero";
$rs = mysql_query($sql, $conexion) or die(register_mysql_error("BL0003", mysql_error()));
$row = mysql_fetch_array($rs);
$conteo = $row['conteo'];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>Blacklist</title>
        <link type="text/css" rel="stylesheet" href="../css/style.css"  />
        <script>
            function validar(form) {
                /* if ( form.numero.value.length != <?php echo numLength(); ?> ){
                 alert('El número debe tener <?php echo numLength(); ?> dígitos. Asegúrese de incluir el código de area.');
                 return false;
                 }*/
                return true;
            }
        </script>
    </head>
    <body>
        <table cellpadding="1" cellspacing="1" width="220px">
            <tr><th colspan="3">Blacklist General</th></tr>
            <tr><td colspan="3">&nbsp;</td></tr>
            <form action="" id="formserv" name="formserv" method="post">
                <tr>
                    <th style="text-align: left">N&uacute;mero:</th>
                    <td><textarea name="numero"></textarea></td>
                </tr><tr>
                    <td colspan="3" style="text-align: right">
                        <input type="hidden" value='updateForm' name="MM_update" />
                        <input type="submit" class="button" onclick="javascript: return validar(this.form)" value="Agregar" />
                    </td>
                </tr>
            </form>
            <tr><td colspan="3">&nbsp;</td></tr>
            <tr>
                <th>Conteo Actual:</th><th><a href="?detalle"><?php echo $conteo; ?></a></th>
            </tr><?php
if (isset($_GET['detalle'])) {
    $sql = "SELECT numero, fechaIngreso AS fecha FROM mantenimiento_bloqueos ORDER BY numero";
    $rs = mysql_query($sql, $conexion) or die(register_mysql_error("BL0003", mysql_error()));
    $row = mysql_fetch_array($rs);
    ?><tr><td colspan="3">&nbsp;</td></tr>
                <tr>
                    <th>N&uacute;mero</th><th>Fecha</th>
                </tr>
                <tr><td colspan="3">&nbsp;</td></tr><?php
            while ($row = mysql_fetch_array($rs)) {
                ?><tr>
                        <td style="text-align: left"><?php echo $row['numero']; ?></td>
                        <td style="text-align: right"><?php echo $row['fecha']; ?></td>
                        <td style="text-align: right">
                            <form action="" id="formserv" name="formserv" method="post">
                                <input type="hidden" value='deleteForm' name="MM_delete" />
                                <input type="hidden" value='<?php echo $row['numero']; ?>' name="numero" />
                                <input type="submit" class="button" value="Eliminar" />
                            </form>
                        </td>
                    </tr><?php
        }
    }
?></table>
    </body>
</html