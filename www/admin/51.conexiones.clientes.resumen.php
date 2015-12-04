<?php

session_start();

require_once('../connections/conexion.php');
require_once('../functions/functions.php');
require_once('../functions/db.php');
require_once('functions.php');
require_once ('../connections/conexion.php');
require_once ('../functions/functions.php');
require_once ('../functions/db.php');
require_once ('../functions/xls.php');
require_once ('../functions/PHPExcel.php');
require_once ('../functions/PHPExcel/Writer/Excel2007.php');

class admin {

    private $template = "51.conexiones.clientes.resumen.html";
    private $html = "";

    function admin() {
        if (!isset($_SESSION['idAdmin'])) {
            header("Location: " . initPage());
            exit();
        } else if (!permission(51)) {
            header("Location: " . initPage());
            exit();
        }
        if (isset($_POST['sltCliente']) && isset($_POST['txtDesde']) && isset($_POST['txtHasta'])) {
            $this->getExcel();
        }
        $this->getPage();
    }

    function getExcel() {
        global $conexion;
        global $database_conexion;
        mysql_select_db($database_conexion, $conexion);
        $cont = 1;
        ini_set('max_execution_time', 0);
        ini_set("memory_limit", "786M");
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("SMPP");
        $objPHPExcel->getProperties()->setLastModifiedBy("SMPP");
        $objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Document");
        $objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Document");
        $objPHPExcel->getProperties()->setDescription("Office 2007 XLSX");
        //generamos el resumen de los entrantes
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setTitle("Entrantes");
        $sql = sprintf("SELECT mensaje, fecha FROM conexiones_entrantes WHERE idCliente = %s AND fecha BETWEEN %s AND %s", GetSQLValueString($_POST['sltCliente'], "int"), GetSQLValueString($_POST['txtDesde'], "text"), GetSQLValueString($_POST['txtHasta'], "text"));
        $rs = mysql_query($sql, $conexion) or die(register_mysql_error("CR002", mysql_error()));
        $objPHPExcel->getActiveSheet()->SetCellValue("A" . $cont, "Mensaje");
        $objPHPExcel->getActiveSheet()->SetCellValue("B" . $cont, "Fecha");
        $cont++;
        while ($row = mysql_fetch_array($rs)) {
            $objPHPExcel->getActiveSheet()->SetCellValue("A" . $cont, $row['mensaje']);
            $objPHPExcel->getActiveSheet()->SetCellValue("B" . $cont, $row['fecha']);
            $cont++;
        }

        //generamos el resumen de los salientes
        $cont = 1;
        $objWorksheet1 = $objPHPExcel->createSheet();
        $objPHPExcel->setActiveSheetIndex(1);
        $objPHPExcel->getActiveSheet()->setTitle("Salientes");
        $sql = sprintf("SELECT mensaje, fecha FROM conexiones_salientes WHERE idCliente=%s AND fecha BETWEEN %s AND %s", GetSQLValueString($_POST['sltCliente'], "int"), GetSQLValueString($_POST['txtDesde'], "text"), GetSQLValueString($_POST['txtHasta'], "text"));
        $rs = mysql_query($sql, $conexion) or die(register_mysql_error("CR003", mysql_error()));
        $objPHPExcel->getActiveSheet()->SetCellValue("A" . $cont, "Mensaje");
        $objPHPExcel->getActiveSheet()->SetCellValue("B" . $cont, "Fecha");
        $cont++;
        while ($row = mysql_fetch_array($rs)) {
            $objPHPExcel->getActiveSheet()->SetCellValue("A" . $cont, $row['mensaje']);
            $objPHPExcel->getActiveSheet()->SetCellValue("B" . $cont, $row['fecha']);
            $cont++;
        }

        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
        $path = "resumen.xlsx";
        $fullpath = tempnam(sys_get_temp_dir(), 'resumen');
        $objWriter->save($fullpath);


        header('Accept-Ranges: bytes');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename=' . $path);
        header('Pragma: no-cache');
        header('Expires: 0');
        if (strcmp($_SERVER['REQUEST_METHOD'], 'HEAD') != 0)
            $i = readfile($fullpath);
        exit();
    }

    function getConexionesClientes() {
        global $conexion;
        global $database_conexion;
        mysql_select_db($database_conexion, $conexion);
        $sql = sprintf("SELECT id, nombre FROM conexiones_clientes");
        $rs = mysql_query($sql, $conexion) or die(register_mysql_error("CR001", mysql_error()));
        $listaConexiones = "";
        while ($row = mysql_fetch_array($rs)) {
            $listaConexiones .= "<option value='" . $row['id'] . "'>" . $row['nombre'] . "</option>";
        }
        $this->html = str_replace("@@listaClientes@@", $listaConexiones, $this->html);
    }

    function getPage() {
        $this->html = file_get_contents($this->template);
        $this->html = str_replace("@@desde@@", date("Y-m-d 00:00:00"), str_replace("@@hasta@@", date("Y-m-d 23:59:59"), str_replace("@@TITLE@@", "Resumen de clientes", $this->html)));
        $this->getConexionesClientes();
        echo $this->html;
    }

}

$a = new admin();
?>