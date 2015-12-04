<?php
require_once('../connections/conexion.php');
require_once('../conf.php');
require_once('../functions/functions.php');

$initPage = "index.php";
$title = "Media";
$editFormAction = $_SERVER['PHP_SELF'];

session_start();
if (!isset($_SESSION['idAdmin'])) {
    header("Location: " . $initPage);
}

mysql_select_db($database_conexion, $conexion);
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
    if (!isset($_POST['id']))
        die('No seleccion� una encuesta');
    if (!isset($_FILES['file']['name']))
        die('No ingreso un archivo');
    $name = $_FILES['file']['name'];
    $src = $_FILES['file']['tmp_name'];
    $path = $SURVEY_WALL;
    $name = str_replace(" ", "_", $name);
    move_uploaded_file($src, $root . $path . $name);
    $path .= $name;

    $updateSQL = sprintf("UPDATE encuestas SET wallpaper=%s WHERE id=%s", GetSQLValueString($name, "text"), GetSQLValueString($_POST['id'], "int"));
    $result = mysql_query($updateSQL, $conexion) or die(mysql_error());
    header("Location: " . $editFormAction);
}

$query_rs = "SELECT id, nombre FROM encuestas";
$rs = mysql_query($query_rs, $conexion) or die(mysql_error());
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
        <form name="form1" method="post" action="<?php echo $editFormAction; ?>" enctype="multipart/form-data">
            <table width="350" border="0" cellspacing="1" cellpadding="1">
                <tr><th colspan="2" scope="col">Fondos de Encuestas Media</th></tr>
                <tr>
                    <th style="text-align: left">Encuesta:</th>
                    <td><select name="id" id="id"><?php
                            do {
                                ?><option value="<?php echo $row_rs['id'] ?>"><?php echo $row_rs['nombre'] ?></option><?php
                            } while ($row_rs = mysql_fetch_assoc($rs));
                            $rows = mysql_num_rows($rs);
                            if ($rows > 0) {
                                mysql_data_seek($rs, 0);
                                $row_rs = mysql_fetch_assoc($rs);
                            }
                            ?></select></td>
                </tr><tr>
                    <th style="text-align: left">Archivo:</th>
                    <td><input name="file" type="file" id="file"></td>
                </tr>
                <td colspan="2"><div align="right">
                        <button onclick='javascript: if (removeLeadingAndTrailingChar(file.value) != "") {
                  this.form.submit();
              } else {
                  alert("No ha llenado todos los campos")
              }'>Cargar</button>
                    </div></td>
                </tr>
                <tr><td colspan="2">&nbsp;</td></tr>    
                <tr><th colspan="2" style="text-align: center"><a href="menu.php">Menu</a></th></tr>   

            </table>
            <input type="hidden" name="MM_insert" value="form1">
        </form>
    </body>
</html>
