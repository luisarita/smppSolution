<?php

session_start();

require_once('functions.php');
require_once('classes/suscripciones.php');
require_once ('../connections/conexion.php');
require_once ('../functions/functions.php');
require_once ('../functions/db.php');

class admin extends suscripciones {

    private $template = "24.xls.suscripciones.html";

    function admin() {
        if (!isset($_SESSION['idAdmin'])) {
            header("Location: " . initPage());
            exit();
        } else if (!permission(24)) {
            header("Location: " . initPage());
            exit();
        }
        if (isset($_POST['desde']) && isset($_POST['hasta'])) {
            $this->getXLS();
        }
        echo $this->getHtml();
    }

    function getHtml() {
        $desde = strftime("%Y-%m-%d 00:00:00", time());
        $hasta = strftime("%Y-%m-%d 23:59:00", time());
        $html = file_get_contents($this->template);
        $html = str_replace("@@DESDE@@", $desde, $html);
        $html = str_replace("@@HASTA@@", $hasta, $html);
        $html = str_replace("@@OPCIONES@@", $this->getOpciones(), $html);
        return $html;
    }

    function getXLS() {
        require_once ('../functions/xls.php');
        require_once ('../functions/PHPExcel.php');
        require_once ('../functions/PHPExcel/Writer/Excel2007.php');

        global $conexion;
        global $database_conexion;

        if (isset($_POST['desde']) && isset($_POST['hasta'])) {
            ini_set('max_execution_time', 0);
            ini_set("memory_limit", "1024M");

            $desde = $_POST['desde'];
            $hasta = $_POST['hasta'];
            $condition = (isset($_POST['id']) && $_POST['id'] != -1) ? sprintf(" AND r.id=%s", GetSQLValueString($_POST['id'], "int")) : "";

            mysql_select_db($database_conexion, $conexion);
            $sql = sprintf("SELECT r.id, r.nombre, p.fecha, p.numero, '' AS mensaje, estado FROM suscripciones r, suscripciones_participantes p WHERE p.fecha>=%s AND p.fecha<=%s AND p.idSuscripcion=r.id %s ORDER BY r.nombre, p.fecha;", GetSQLValueString($desde, "date"), GetSQLValueString($hasta, "date"), $condition);
            $rs = mysql_query($sql, $conexion) or die(register_mysql_error("XS001", mysql_error()));

            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setCreator("SMPP");
            $objPHPExcel->getProperties()->setLastModifiedBy("SMPP");
            $objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Document");
            $objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Document");
            $objPHPExcel->getProperties()->setDescription("Office 2007 XLSX");

            $i = 1;
            $j = 0;
            $lname = "";
            while ($row = mysql_fetch_array($rs)) {
                $name = fixName($row['nombre']);
                if ($lname != $name) {
                    if ($j > 0)
                        $objWorksheet1 = $objPHPExcel->createSheet();
                    $objPHPExcel->setActiveSheetIndex($j);
                    $objPHPExcel->getActiveSheet()->setTitle($name);
                    $i = 1;
                    ++$j;
                    $lname = $name;
                }

                $objPHPExcel->getActiveSheet()->SetCellValue("A$i", $row['fecha']);
                $objPHPExcel->getActiveSheet()->SetCellValue("B$i", $row['numero']);
                $objPHPExcel->getActiveSheet()->SetCellValue("C$i", $row['mensaje']);
                $objPHPExcel->getActiveSheet()->SetCellValue("D$i", ($row['estado']) ? "Activo" : "Inactivo");
                ++$i;
            }

            $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
            $path = "suscripciones.xlsx";
            $fullpath = tempnam(sys_get_temp_dir(), 'suscripciones');
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
    }

}

$s = new admin();
