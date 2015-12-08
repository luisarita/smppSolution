<?php

session_start();

require_once('../connections/conexion.php');
require_once('../functions/functions.php');
require_once('../functions/db.php');
require_once("../functions/PHPExcel.php");
require_once('functions.php');

class admin {

    private $idSuscripcion;
    private $erroneos;
    private $cargados;
    private $directorio = "../_repositorio/suscripciones/";

    function admin() {
        $this->idSuscripcion = (isset($_POST['id'])) ? $_POST['id'] : -1;
        $this->erroneos = "";
        $this->cargados = 0;

        if (!isset($_SESSION['idAdmin']) || !isset($_SESSION['usuario'])) {
            header("Location: " . initPage());
            exit();
        } else if (!permission(17)) {
            header("Location: " . initPage());
            exit();
        }
    }

    function validarNumero($numero) {
        if (strlen($numero) != 11) {
            return false;
        } else if (is_numeric($numero) == 0) {
            return false;
        }
        return true;
    }

    function cargarDatos() {
        global $conexion;


        if (isset($_POST['id'])) {
            $archivo = $this->directorio . $this->idSuscripcion . "_" . date("YmdHis") . "." . substr(strrchr($_FILES["fileExcel"]['name'], '.'), 1);
            $version = (substr(strrchr($archivo, '.'), 1) == "xlsx" ) ? "Excel2007" : "Excel5";
            $cont = 1;
            $registro = array("", "", "", "", "", "");

            if ($archivo != "") {
                if (copy($_FILES['fileExcel']['tmp_name'], $archivo)) {
                    error_reporting(E_ALL);
                    $reader = PHPExcel_IOFactory::createReader($version);
                    $reader->setReadDataOnly(true);
                    $phpExcel = $reader->load($archivo);
                    $workSheet = $phpExcel->getActiveSheet();

                    foreach ($workSheet->getRowIterator() as $row) {
                        $registro[1] = $phpExcel->getActiveSheet()->getCell('A' . $cont)->getValue();
                        $registro[2] = $phpExcel->getActiveSheet()->getCell('B' . $cont)->getValue();
                        $registro[3] = $phpExcel->getActiveSheet()->getCell('C' . $cont)->getValue();
                        $registro[4] = $phpExcel->getActiveSheet()->getCell('D' . $cont)->getValue();
                        $registro[5] = $phpExcel->getActiveSheet()->getCell('E' . $cont)->getValue();
                        $registro[6] = $phpExcel->getActiveSheet()->getCell('F' . $cont)->getValue();
                        $variable = 0;

                        foreach ($registro as $key => $value) {
                            if ($key > 1) {
                                if (strlen(($value)) > 0) {
                                    $variable = $key - 1;
                                }
                                $value = get_magic_quotes_gpc() ? stripslashes($value) : $value;
                                $value = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($value) : mysql_escape_string($value);
                                $value = "'" . $value . "'";
                                $registro[$key] = $value;
                            }
                        }

                        if ($this->validarNumero($registro[1])) {
                            $query = sprintf("REPLACE INTO suscripciones_participantes (idsuscripcion, numero, fecha, estado, variable1, variable2, variable3, variable4, variable5, variableEnviada, variableLlenada) VALUES (%s, %s, NOW(), 1, %s, %s, %s, %s, %s, %s, %s)", GetSQLValueString($this->idSuscripcion, "text"), $registro[1], $registro[2], $registro[3], $registro[4], $registro[5], $registro[6], GetSQLValueString($variable, "int"), GetSQLValueString($variable, "int"));
                            mysql_query($query, $conexion) or die(register_mysql_error("SCL0001", mysql_error()));
                            $this->cargados += 1;
                        } else {
                            if ($this->erroneos != "") {
                                $this->erroneos .= "<br/>";
                            }
                            $this->erroneos .= $registro[1];
                        }
                        $cont++;
                    }
                    mysql_query("UPDATE suscripciones_participantes p, suscripciones_bloqueos b SET p.estado=0 WHERE p.numero=b.numero;", $conexion) or die(register_mysql_error("SCL0002", mysql_error()));
                    $sql = sprintf("INSERT INTO suscripciones_carga_lote(id, idSuscripcion, fecha, usuario, conteo_carga, nombre_archivo) VALUES(NULL, %s, NOW(), %s, %s, %s)", GetSQLValueString($this->idSuscripcion, "int"), GetSQLValueString($_SESSION['usuario'], "text"), GetSQLValueString($cont - 1, "int"), GetSQLValueString($archivo, "text"));
                    mysql_query($sql, $conexion) or die(register_mysql_error("SCL0004", mysql_error()));
                } else {
                    echo "<script>alert('Error al cargar archivo');</script>";
                }
            }
        }
    }

    //obtenemos las opciones del select
    function getOpciones() {
        global $conexion;

        $opciones = "";
        $rs = mysql_query("SELECT id, nombre FROM suscripciones WHERE activa=1 ORDER BY nombre;", $conexion) or die(register_mysql_error("SCL0003", mysql_error()));
        while ($row = mysql_fetch_array($rs)) {
            $selected = ($this->idSuscripcion == $row['id']) ? "selected" : "";
            $opciones .= '<option value="' . $row['id'] . '"' . $selected . '>' . $row['nombre'] . '</option>';
        }
        return $opciones;
    }

    function getPage() {
        $html = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
        <html xmlns="http://www.w3.org/1999/xhtml">
        <head>
         <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
         <title>Suscripciones - Carga por Lote</title>
         <link rel="stylesheet" type="text/css" href="../css/style.css" />
         <script language="javascript">
          function validar(){
           if ( document.getElementById(\'id\').selectedIndex != -1){
            if ( document.getElementById(\'fileExcel\').value != \'\'){
             return true;
            } else {
             alert("No ha seleccionado el archivo de Excel");
             return false;
            }
           } else {
            alert("No ha seleccionado ninguna sucripcion");;
            return false;
           }
          }

          function validarFile(){
           var archivo =  document.getElementById(\'fileExcel\').value;
           extension = (archivo.substring(archivo.lastIndexOf("."))).toLowerCase();
           if( extension == ".xls" || extension == ".xlsx"){
           } else {
            alert("Solo se permiten archivos de excel en formato \".xls(Excel 2003) y .xlsx(Excel 2007)\"");
            document.getElementById(\'fileExcel\').value = \'\';
           }
          }
         </script>
        </head>
        <body>
         <form  id="formactualizar" name="formactualizar" method="post" enctype="multipart/form-data" >
          <table width="220px" cellpadding="0" cellspacing="0">
           <tr><th colspan="2">Carga por Lotes</th></tr>
           <tr><td colspan="0">&nbsp;</td></tr>
           <tr>
            <td>Suscripci&oacute;n:</td>
            <td><select name="id" id="id">@@OPCIONES@@</select></td>
           </tr>
           <tr><td colspan="2">&nbsp;</td></tr>
           <tr>
            <td>Archivo de Excel:</td>
            <td><input type="file" name="fileExcel" id="fileExcel" onchange="validarFile()"/></td>
           </tr>
           <tr><td colspan="2">&nbsp;</td></tr>
           <tr><td colspan="2" style="text-align: right">
            <input name = \'actualizar\' type="submit" class="button" value="Agregar" onClick="return validar()" />
               </td></tr>
           <tr><td colspan="2">&nbsp;</td></tr>
           <tr><th colspan="2" style="text-align: center"><a href="menu.php">Menu</a></th></tr>';
        if (isset($_POST['id'])) {
            $html .= sprintf('
          <tr><td colspan="2">&nbsp;</td></tr>
          <tr><th colspan="2" style="text-align: center">Registros cargados</th></tr>
          <tr><td colspan="2">&nbsp;</td></tr>
          <tr><td colspan="2" style="text-align: center">%s</td></tr>
          <tr><td colspan="2">&nbsp;</td></tr>
          <tr><th colspan="2" style="text-align: center">Registros erroneos</th</tr>
          <tr><td colspan="2">&nbsp;</td></tr>
          <tr><td colspan="2" style="text-align: center">%s</td></tr>', $this->cargados, (($this->erroneos != '' ) ? $this->erroneos : ""));
        }
        $html .= "</table>
          </form>
         </body>
        </html>";

        $html = str_replace("@@OPCIONES@@", $this->getOpciones(), $html);
        return $html;
    }

}

$a = new admin();
$a->cargarDatos();
echo $a->getPage();
