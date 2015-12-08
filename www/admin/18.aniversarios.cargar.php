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

$idAniversario = $_GET["id"];
if (isset($_POST["accion"]) && isset($_FILES['archivo'])) {
    $archivo = $_FILES['archivo']['tmp_name'];
    if (!cargar_archivo($archivo)){
        $alert = "El archivo no est� en el formato esperado";
    }
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>Aniversarios - Cargar por Lote</title>
        <link rel="stylesheet"  href="../css/style.css" type="text/css" />
        <script language="javascript1.2" src="../scripts/files.js"></script>
    </head>
    <body <?php if (isset($alert) && $alert != "") echo "onLoad='javascript: alert(\"$alert\")'"; ?>><?php
        if (isset($_GET["id"])) {
            $res_opcion = mysql_query("SELECT nombre FROM aniversarios WHERE id=" . $idAniversario, $conexion) or die();
            $row_opcion = mysql_fetch_array($res_opcion);
        }
        ?><form name="form1" method="post" enctype="multipart/form-data">
            <table cellpadding="1" cellspacing="1" width="220px">
                <tr><th colspan="2">Cargar Aniversarios</th></tr>
                <tr><td colspan="2">&nbsp;</td></tr>
                <tr>
                    <td >Nombre:</td>
                    <td ><?php echo (isset($row_opcion['nombre'])) ? $row_opcion['nombre'] : ''; ?></td>
                </tr><tr>
                    <td>Archivo:</td>
                    <td> <input type="file" name="archivo" id="file" /></td>
                </tr>     
                <tr><td colspan="2" >&nbsp;</td></tr>
                <tr>
                    <td colspan="2" style="text-align: right">
                        <button onclick='javascript: if (removeLeadingAndTrailingChar(file.value) != "" && removeLeadingAndTrailingChar(nombre.value) != "" && removeLeadingAndTrailingChar(clave.value) != "") {
                  this.form.submit();
              } else {
                  alert("No ha llenado todos los campos")
              }'>Cargar</button>
                        <input type="hidden" name="accion" value="<?php if (isset($_GET["id"])) echo "cargar"; ?>">
                            <input type="hidden" name="id" value="<?php if (isset($_GET["id"])) echo $_GET["id"];
        else echo ''; ?>">
                                </td>
                                </tr>
                                <tr><td colspan="2">&nbsp;</td></tr>     
                                </table>
                                </form>
                                </body>
                                </html><?php

                                function cargar_archivo($archivo) {
                                    global $idAniversario;
                                    global $conexion;

                                    if (!validarArchivo($archivo)){
                                        return false;
                                    }
                                    $handle = fopen($archivo, "r");
                                    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                                        $fecha = $data[2];
                                        $ano = substr($fecha, 0, 4);
                                        $cadena = "INSERT INTO aniversarios_participantes (idAniversario, nombre, numero, fecha, ultimo_anio, estado) VALUES ($idAniversario, '" . $data[0] . "','" . $data[1] . "','" . $fecha . "', " . $ano . ", 1)";
                                        $result = mysql_query($cadena, $conexion) or die(mysql_error());
                                    }
                                    fclose($handle);
                                    return true;
                                }

                                function validarArchivo($archivo) {
                                    $handle = fopen($archivo, "r");
                                    while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                                        if (sizeof($data) != 3 || !validarNumero($data[1]) || !validarFecha($data[2])) {
                                            fclose($handle);
                                            return false;
                                        }
                                    }
                                    fclose($handle);
                                    return true;
                                }