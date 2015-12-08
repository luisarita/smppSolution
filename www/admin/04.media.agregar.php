<?php
require_once('../connections/conexion.php');
require_once('../functions/functions.php');
require_once('../conf.php');

$title = "Media";
$editFormAction = $_SERVER['PHP_SELF'];

session_start();
if (!isset($_SESSION['idAdmin'])) {
    header("Location: " . initPage());
    exit();
} else if (!permission(4)) {
    header("Location: " . initPage());
    exit();
}

if (isset($_SERVER['QUERY_STRING'])) {
    $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
    if (!isset($_POST['nombre'])) {
        die('No ingreso un nombre');
    }
    if (!isset($_POST['clave'])) {
        die('No ingreso una clave');
    }
    if (!isset($_POST['usuario'])) {
        die('No ingreso un usuario');
    }
    if (!isset($_POST['password'])) {
        die('No ingreso una contrase�a');
    }
    if (!isset($_FILES['file']['name'])) {
        die('No ingreso un archivo');
    }
    $name = $_FILES['file']['name'];
    $src = $_FILES['file']['tmp_name'];
    $path = select_path($_POST['tipo']);
    $name = str_replace(" ", "_", $name);
    move_uploaded_file($src, $root . $path . $name);
    $path .= $name;

    $insertSQL = sprintf("INSERT INTO ws_media (nombre, descripcion, tipo, path, descarga, numero, usuario, clave) VALUES (%s, %s,%s, %s, %s, %s, %s, %s)", GetSQLValueString($_POST['nombre'], "text"), GetSQLValueString($_POST['descripcion'], "text"), GetSQLValueString($_POST['tipo'], "int"), GetSQLValueString($path, "text"), GetSQLValueString($_POST['descargas'], "int"), GetSQLValueString($_POST['numero'], "text"), GetSQLValueString($_POST['usuario'], "text"), GetSQLValueString($_POST['password'], "text"));
    $result = mysql_query($insertSQL, $conexion) or die();
    $result = mysql_query("SELECT LAST_INSERT_ID()", $conexion) or die();
    $row = mysql_fetch_array($result);
    $idMedia = $row[0];

    foreach ($_POST['clave'] as $key => $value) {
        if ($value != "") {
            $insertSQL = sprintf("INSERT INTO claves ( clave, idMedia ) VALUES ( %s, %s )", GetSQLValueString($value, "text"), GetSQLValueString($idMedia, "int"));
            mysql_query($insertSQL, $conexion) or die();
        }
    }

    if (isset($_FILES['thumbnail'])) {
        $idSQL = mysql_query("SELECT MAX(id) AS id FROM ws_media") or die();
        $idArray = mysql_fetch_array($idSQL);
        $id = $idArray['id'];
        $name = $_FILES['thumbnail']['name'];
        $src = $_FILES['thumbnail']['tmp_name'];
        $ext = substr($name, strrpos($name, '.'));
        $path_tn = $root . $THUMBNAILS . $id . $ext;
        move_uploaded_file($src, $path_tn);
    }

    $header = "Location: " . $editFormAction;
    header($header);
}

$query_rs2 = "SELECT * FROM ws_tipo";
$rs2 = mysql_query($query_rs2, $conexion) or die();
$row_rs2 = mysql_fetch_assoc($rs2);
$totalRows_rs2 = mysql_num_rows($rs2);

$query_rs = "SELECT * FROM numeros";
$rs = mysql_query($query_rs, $conexion) or die();
$row_rs = mysql_fetch_assoc($rs);
$totalRows_rs = mysql_num_rows($rs);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title><?php echo $title ?></title>
        <link rel="stylesheet"  href="../css/style.css" type="text/css" />
        <script language="javascript1.2" src="../scripts/files.js"></script>
    </head>
    <body>
        <form name="form1" method="POST" action="<?php echo $editFormAction; ?>" enctype="multipart/form-data">
            <table width="350" border="0" cellspacing="1" cellpadding="1">
                <tr><th colspan="2" scope="col">Nueva Media</th></tr>
                <tr>
                    <th style="text-align: left">N&uacute;mero:</th>
                    <td><select name="numero" id="numero"><?php
                            do {
                                ?><option value="<?php echo $row_rs['numero'] ?>"><?php echo $row_rs['numero'] ?></option><?php
                            } while ($row_rs = mysql_fetch_assoc($rs));
                            $rows = mysql_num_rows($rs);
                            if ($rows > 0) {
                                mysql_data_seek($rs, 0);
                                $row_rs = mysql_fetch_assoc($rs);
                            }
                            ?>
                        </select></td>
                </tr><tr>
                    <th style="text-align: left">Tipo:</th>
                    <td><select name="tipo" id="tipo"><?php
                            do {
                                ?><option value="<?php echo $row_rs2['id'] ?>"><?php echo $row_rs2['nombre'] ?></option><?php
                            } while ($row_rs2 = mysql_fetch_assoc($rs2));
                            $rows = mysql_num_rows($rs2);
                            if ($rows > 0) {
                                mysql_data_seek($rs2, 0);
                                $row_rs2 = mysql_fetch_assoc($rs2);
                            }
                            ?></select></td>
                </tr><tr>
                    <th style="text-align: left">Nombre:</th>
                    <td><input name="nombre" type="text" id="nombre" /></td>
                </tr><tr>
                    <th style="text-align: left">Descripcion:</th>
                    <td><textarea name="descripcion" id="descripcion"></textarea></td>
                </tr>
                <tr>
                    <th style="text-align: left">Clave:</th>
                    <td>
                        <input name="clave[]" type="text" id="clave[]" />
                        <input name="clave[]" type="text" id="clave[]" />
                        <input name="clave[]" type="text" id="clave[]" />
                        <input name="clave[]" type="text" id="clave[]" />
                    </td>
                </tr>
                <tr>
                    <th style="text-align: left">Archivo:</th>
                    <td><input name="file" type="file" id="file" /></td>
                </tr>
                <tr>
                    <th style="text-align: left">Thumbnail:</th>
                    <td><input name="thumbnail" type="file" id="thumbnail" /></td>
                </tr>
                <tr>
                    <th style="text-align: left">Descargas Permitidas: </th>
                    <td><input name="descargas" type="text" id="descargas" value="1" /></td>
                </tr>
                <tr>
                    <th style="text-align: left">Usuario: </th>
                    <td><input name="usuario" type="text" id="usuario" maxlength="50" value="" /></td>
                </tr>
                <tr>
                    <th style="text-align: left">Contrase&ntilde;a: </th>
                    <td><input name="password" type="password" id="password" maxlength="50" value="" /></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div align="right">
                            <button onclick='javascript: if (removeLeadingAndTrailingChar(file.value) != "" && removeLeadingAndTrailingChar(nombre.value) != "" && removeLeadingAndTrailingChar(clave.value) != "") {
                                        this.form.submit();
                                    } else {
                                        alert("No ha llenado todos los campos")
                                    }'>Agregar</button>
                        </div>
                    </td>
                </tr>
                <tr><th colspan="2" style="text-align: center"><a href="menu.php">Menu</a></th></tr>   
            </table>
            <input type="hidden" name="MM_insert" value="form1">
        </form>
    </body>
</html>