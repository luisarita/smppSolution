<?php
require_once('functions.php');
require_once('../connections/conexion.php');
require_once('../functions/functions.php');
require_once('../functions/db.php');

session_start();
if (!isset($_SESSION['idAdmin'])) {
    header("Location: index.php");
} else if (!permission(14)) {
    header("Location: index.php");
}

mysql_select_db($database_conexion, $conexion);

if (isset($_POST["accion"]) && $_POST["accion"] == "guardar") {
    $chkDevolverAciertos = (isset($_POST['devolveraciertos'])) ? $_POST['devolveraciertos'] : 0;
    $numeros = array(rand(0, 99), rand(0, 99), rand(0, 99), rand(0, 99));
    $sql = sprintf("INSERT INTO telebingos (nombre, fecha_inicio, monto_inicio, numero, mensaje_fallido, mensaje_gane, mensaje_inactivo, mensaje_instrucciones, devolver_aciertos, mensaje_acierto, tasa_mensaje, tasa_segundo, monto_actual, numero1, numero2, numero3, numero4,nombre_pantalla,mensaje_pantalla,usuario,clave) VALUES ('%s', '%s', %s, '%s', '%s', '%s', '%s', '%s', %s, '%s', %s, %s, %s, %s, %s, %s, %s, '%s', '%s', '%s', '%s')", $_POST['nombre'], $_POST['desde'], $_POST['montoinicio'], $_POST['numero'], $_POST['msjfallido'], $_POST['msjgane'], $_POST['msjinactivo'], $_POST['msjinstrucciones'], $chkDevolverAciertos, $_POST['msjacierto'], $_POST['tasamsj'], $_POST['tasaseg'], $_POST['montoactual'], $numeros[0], $numeros[1], $numeros[2], $numeros[3], $_POST['nombre_pantalla'], $_POST['msjpantalla'], $_POST['usuario'], $_POST['clave']);
    mysql_query($sql, $conexion) or die(register_mysql_error("TBA001", mysql_error()));
    $id = lastInsertId();
    agregarClaves($id, $_POST['claves']);
    header("Location: 14.telebingos.php");
    exit();
} elseif (isset($_POST["accion"]) && $_POST["accion"] == "modificar") {
    $chkDevolverAciertos = (isset($_POST['devolveraciertos'])) ? $_POST['devolveraciertos'] : 0;
    $sql = sprintf("UPDATE telebingos SET nombre='%s', fecha_inicio='%s',monto_inicio=%s, numero='%s', mensaje_fallido='%s', mensaje_gane='%s',  mensaje_inactivo='%s', mensaje_instrucciones='%s', devolver_aciertos=%s, mensaje_acierto='%s', tasa_mensaje=%s, tasa_segundo=%s, monto_actual=%s,nombre_pantalla='%s',mensaje_pantalla='%s',usuario='%s',clave='%s' WHERE id=%s", $_POST['nombre'], $_POST['desde'], $_POST['montoinicio'], $_POST['numero'], $_POST['msjfallido'], $_POST['msjgane'], $_POST['msjinactivo'], $_POST['msjinstrucciones'], $chkDevolverAciertos, $_POST['msjacierto'], $_POST['tasamsj'], $_POST['tasaseg'], $_POST['montoactual'], $_POST['nombre_pantalla'], $_POST['msjpantalla'], $_POST['usuario'], $_POST['clave'], intval($_POST['id']));
    mysql_query($sql, $conexion) or die(register_mysql_error("TBA002", mysql_error()));
    agregarClaves($_POST['id'], $_POST['claves']);
    header("Location: 14.telebingos.php");
    exit();
} elseif (isset($_POST["accion"]) && $_POST["accion"] == "eliminar" && isset($_POST['eliminar'])) {
    $selec_eliminar = $_POST['eliminar'];
    for ($i = 0; $i < sizeof($selec_eliminar); ++$i) {
        $sql = sprintf("DELETE FROM telebingos WHERE id=%s;", intval($selec_eliminar[$i]));
        $result = mysql_query($sql, $conexion) or die(register_mysql_error("TBA003", mysql_error()));
    }
    header("Location: 14.telebingos.php");
    exit();
} elseif (isset($_POST["accion"]) && $_POST["accion"] == "revelar") {
    $result = mysql_query("UPDATE telebingos SET revelados=LEAST(revelados+1,4) WHERE id=" . $_POST['id'], $conexion) or die(register_mysql_error("TBA004", mysql_error()));
    header("Location: 14.telebingos.php?id=" . $_POST['id']);
    exit();
}

function agregarClaves($idTelebingo, $claves) {
    global $database_conexion;
    global $conexion;

    mysql_select_db($database_conexion, $conexion);
    $sql = sprintf("DELETE FROM claves WHERE idTelebingo=%s;", $idTelebingo);
    mysql_query($sql, $conexion) or die(register_mysql_error("TBA005", mysql_error()));

    foreach ($claves as $clave) {
        $sql = sprintf("INSERT INTO claves (clave, idtelebingo) VALUES (%s, %s);", GetSQLValueString($clave, "text"), $idTelebingo);
        mysql_query($sql, $conexion) or die(register_mysql_error("TBA006", mysql_error()));
    }
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Telebingos</title>
        <link rel="stylesheet" type="text/css" href="../css/style.css" />
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <style type="text/css">@import url(../lib/calendar/calendar-blue.css);</style>
        <script type="text/javascript" src="../lib/calendar/calendar.js"></script>
        <script type="text/javascript" src="../lib/calendar/lang/calendar-es.js"></script>
        <script type="text/javascript" src="../lib/calendar/calendar-setup.js"></script>
        <script type="text/javascript" src="js/functions.js"></script>
        <script type="text/javascript" src="js/telebingos.js"></script>
    </head>   
    <body><?php
if (isset($_GET["id"])) {
    $sql = sprintf("SELECT id, nombre, DATE_FORMAT(fecha_inicio, '%%Y-%%m-%%d') AS fecha_inicio, monto_inicio, numero, mensaje_acierto, mensaje_fallido, mensaje_inactivo, mensaje_instrucciones, mensaje_gane, devolver_aciertos, tasa_mensaje, tasa_segundo, monto_actual, clave, nombre_pantalla, mensaje_pantalla, usuario FROM telebingos WHERE id=%s", GetSQLValueString($_GET['id'], "int"));
    $res_telebingos = mysql_query($sql, $conexion) or die(mysql_error());
    $row_telebingos = mysql_fetch_array($res_telebingos);
}
?><table width="100%" cellpadding="0" cellspacing="0">
            <tr><td>   
                    <form name="form1" method="post">
                        <table width="600" cellpadding="0" cellspacing="0">
                            <tr><th colspan="2">Telebingo</th></tr>
                            <tr><td colspan="2">&nbsp;</td></tr>     
                            <tr>
                                <td>Nombre:</td>  
                                <td><input class="textbox" type="text" name="nombre" value="<?php echo (isset($row_telebingos['nombre'])) ? $row_telebingos['nombre'] : ''; ?>"></td>
                            </tr>
                            <tr>
                                <td>Nombre Pantalla:</td>
                                <td><input class="textbox" type="text" name="nombre_pantalla" value="<?php echo (isset($row_telebingos['nombre_pantalla'])) ? $row_telebingos['nombre_pantalla'] : ''; ?>"></td>
                            </tr>
                            <tr>
                                <td>Mensaje Fallido:</td>  
                                <td>
                                    <input class="textbox" type="text" name="msjfallido" value="<?php echo (isset($row_telebingos['mensaje_fallido'])) ? $row_telebingos['mensaje_fallido'] : ''; ?>">
                                </td>
                            </tr><tr>
                                <td>Mensaje Gane:</td>  
                                <td>
                                    <input class="textbox" type="text" name="msjgane" value="<?php echo (isset($row_telebingos['mensaje_gane'])) ? $row_telebingos['mensaje_gane'] : ''; ?>">
                                </td>
                            </tr><tr>
                                <td>Mensaje Acierto:</td>  
                                <td>
                                    <input class="textbox" type="text" name="msjacierto" value="<?php echo (isset($row_telebingos['mensaje_acierto'])) ? $row_telebingos['mensaje_acierto'] : ''; ?>">
                                </td>
                            </tr>
                            <tr>
                                <td>Mensaje Inactivo:</td>
                                <td><input class="textbox" type="text" name="msjinactivo" value="<?php echo (isset($row_telebingos['mensaje_inactivo'])) ? $row_telebingos['mensaje_inactivo'] : ''; ?>" /></td>
                            </tr>
                            <tr>
                                <td>Mensaje Instrucciones:</td>
                                <td><input class="textbox" type="text" name="msjinstrucciones" value="<?php echo (isset($row_telebingos['mensaje_instrucciones'])) ? $row_telebingos['mensaje_instrucciones'] : ''; ?>" /></td>
                            </tr>
                            <tr>
                                <td>Mensaje Pantalla:</td>
                                <td><input class="textbox" type="text" name="msjpantalla" value="<?php echo (isset($row_telebingos['mensaje_pantalla'])) ? $row_telebingos['mensaje_pantalla'] : ''; ?>" /></td>
                            </tr>
                            <tr>
                                <td>Fecha Inicial:</td>  
                                <td><?php
        $fecha = (isset($row_telebingos['fecha_inicio'])) ? $row_telebingos['fecha_inicio'] : date('Y-m-d');
?><input id="desde" name="desde" readonly value="<?php echo $fecha; ?>">&nbsp;<button id="desde_btn">...</button>
                                </td>
                            </tr><tr>
                                <td>Monto Inicial:</td>  
                                <td><input class="textbox" type="text" name="montoinicio" value="<?php echo (isset($row_telebingos['monto_inicio'])) ? $row_telebingos['monto_inicio'] : ''; ?>"></td>
                            </tr><tr>
                                <td>Numero:</td>
                                <td>
                                    <select name="numero" id="numero"><?php
                                    $res_numeros = mysql_query("SELECT numero FROM numeros ORDER BY numero", $conexion) or die(mysql_error());
                                    while ($row_numeros = mysql_fetch_array($res_numeros)) {
    ?><option value="<?php echo $row_numeros['numero'] ?>" <?php if (isset($row_telebingos['numero']) && $row_numeros['numero'] == $row_telebingos['numero']) echo "selected"; ?> ><?php echo $row_numeros['numero'] ?></option><?php
                                        }
                                        ?></select>
                                </td>
                            </tr><tr>
                                <td>Clave:</td>
                                <td>
                                    <table width="200" border="0" cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td><select name="claves[]" size="4" id="claves" multiple="multiple"><?php
                                        $id = (isset($row_telebingos['id'])) ? $row_telebingos['id'] : 0;
                                        $res_claves = mysql_query("SELECT id,clave FROM claves WHERE idTelebingo=" . $id, $conexion) or die(mysql_error());
                                        while ($row_claves = mysql_fetch_array($res_claves)) {
                                            ?><option value="<?php echo $row_claves['clave'] ?>" ><?php echo $row_claves['clave'] ?></option><?php
                                                    }
                                                    ?></select></td>
                                            <td>
                                                <input type="button" class="button" name="input" value="+" onclick="agregar()" />
                                                <input type="button" class="button" name="input2" value="-" onclick="eliminar()" />
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr><tr>
                                <td>&nbsp;</td>
                                <td>
                                    <input class="checkbox" type="checkbox" name="devolveraciertos" id="devolveraciertos" <?php if (isset($row_telebingos['devolver_aciertos']) && $row_telebingos['devolver_aciertos'] == 1) echo 'checked'; ?> value='1' />      
                                    Devolver Aciertos
                                </td>
                            </tr><tr>
                                <td>Tasa Mensaje:</td>
                                <td><input class="textbox" type="text" name="tasamsj" value="<?php echo (isset($row_telebingos['tasa_mensaje'])) ? $row_telebingos['tasa_mensaje'] : ''; ?>" /></td>
                            </tr><tr>
                                <td>Tasa Segundo:</td>
                                <td><input class="textbox" type="text" name="tasaseg" value="<?php echo (isset($row_telebingos['tasa_segundo'])) ? $row_telebingos['tasa_segundo'] : ''; ?>" /></td>
                            </tr><tr>
                                <td>Monto Actual:</td>  
                                <td><input class="textbox" type="text" name="montoactual" value="<?php echo (isset($row_telebingos['monto_actual'])) ? $row_telebingos['monto_actual'] : ''; ?>"></td>
                            </tr>
                            <tr>
                                <td>Usuario:</td>
                                <td><input class="textbox" type="text" name="usuario" value="<?php echo (isset($row_telebingos['usuario'])) ? $row_telebingos['usuario'] : ''; ?>" /></td>
                            </tr>
                            <tr>
                                <td>Clave:</td>
                                <td><input class="textbox" type="text" name="clave" value="<?php echo (isset($row_telebingos['clave'])) ? $row_telebingos['clave'] : ''; ?>" /></td>
                            </tr>
                            <tr><td colspan="2">&nbsp;</td></tr>     
                            <tr>
                                <td colspan="2" style="text-align: right">
                                    <input type="submit" class="button" name="Submit" value="Guardar Datos" onClick="return validar(this.form)">
                                        <input type="hidden" name="accion" value="<?php if (isset($_GET["id"])) echo "modificar";
                                                    else echo "guardar"; ?>">
                                            <input type="hidden" name="id" value="<?php if (isset($_GET["id"])) echo $_GET["id"];
                                                    else echo ''; ?>">
                                                </td>
                                                </tr>
                                                <tr><td colspan="2">&nbsp;</td></tr>     
                                                </table>
                                                </form>
                                                <form id="frmRevelar" name="frmRevelar" method="post">
                                                    <table width="600" cellpadding="0" cellspacing="0">
                                                        <tr><td style="text-align: right">
                                                                <input type="submit" class="button" name="Submit" value="Revelar">
                                                                    <input type="hidden" name="id" value="<?php if (isset($_GET["id"])) echo $_GET["id"];
                                                    else echo ''; ?>">
                                                                        <input type="hidden" name="accion" value="revelar">
                                                                            </td></tr>     
                                                                            <tr><td>&nbsp;</td></tr>
                                                                            </table>
                                                                            </form>
                                                                            <form id="formeliminar" name="formeliminar" method="post">
                                                                                <table width="600" cellpadding="0" cellspacing="0">
                                                                                    <tr>
                                                                                        <th>Nombre</th>
                                                                                        <th colspan="2">Acci�n</th>
                                                                                    </tr>
                                                                                    <tr><td colspan="6">&nbsp;</td></tr><?php
                                                                                    $rs = mysql_query("SELECT id, nombre FROM telebingos ORDER BY nombre", $conexion) or die(mysql_error());
                                                                                    while ($row_bingos = mysql_fetch_array($rs)) {
                                                                                        ?><tr>
                                                                                            <td><?php echo $row_bingos['nombre']; ?></td>
                                                                                            <td rowspan="1" style="text-align: center; vertical-align: middle"><input name="eliminar[]" type="checkbox" value="<?php echo $row_bingos['id']; ?>" enabled></td>
                                                                                            <td rowspan="1" style="text-align: center; vertical-align: middle"><a href='?id=<?php echo $row_bingos['id']; ?>'>Modificar</a></td>
                                                                                        </tr><?php }
                                                                                    ?>
                                                                                    <tr><td colspan="6">&nbsp;</td></tr>
                                                                                    <tr>
                                                                                        <td colspan="5" style="text-align: right; vertical-align: middle">
                                                                                            <input type="submit" class="button" onclick='javascript: if (confirm("�Desea Eliminar los Registros Seleccionados?")) {
                      return true;
                  }' value="Eliminar" /> 
                                                                                            <input type="hidden" name="accion" value="eliminar">
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr><td colspan="5">&nbsp;</td></tr>   
                                                                                    <tr><th colspan="5" style="text-align: center"><a href="menu.php">Menu</a></th></tr>   
                                                                                </table>
                                                                            </form>
                                                                            </td></tr>
                                                                            </table>
                                                                            </body>
                                                                            <script>
                                                                                Calendar.setup({
                                                                                    date: "2006/01/11",
                                                                                    inputField: "desde", // id of the input field
                                                                                    ifFormat: "%Y-%m-%d %H:%M", // format of the input field
                                                                                    showsTime: true, // will display a time selector
                                                                                    button: "desde_btn", // trigger for the calendar (button ID)
                                                                                    singleClick: true, // double-click mode
                                                                                    step: 1                 // show all years in drop-down boxes (instead of every other year as default)
                                                                                });
                                                                            </script>
                                                                            </html>