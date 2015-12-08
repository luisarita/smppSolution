<?php

require_once ('../lib/smarty/Smarty.class.php');
require_once ('../lib/phpexcel/PHPExcel.php');
require_once ('../lib/phpexcel/PHPExcel.php');
require_once ('../lib/phpexcel/PHPExcel/Writer/Excel2007.php');
require_once ('../lib/phpframework/databases/databases.php');
require_once ('../lib/phpframework/databases/arrays.php');
require_once ('../lib/phpframework/xls/xls.php');

require_once ('../conf.php');
require_once ('../connections/conexion.php');
require_once ('../functions/db.php');
require_once ('functions.php');

session_start();
if (!isset($_SESSION['idAdmin'])) {
    header("Location: " . initPage());
    exit();
} else if (!permission(22)) {
    header("Location: " . initPage());
    exit();
}

$dateFrom = strftime("%Y-%m-%d 00:00:00", time());
$dateTo = strftime("%Y-%m-%d 23:59:59", time());

if (isset($_POST['dateFrom']) && isset($_POST['dateTo'])) {
    ini_set('max_execution_time', 0);
    ini_set("memory_limit", "786M");

    $dateFrom = $_POST['dateFrom'];
    $dateTo = $_POST['dateTo'];
    $id = $_POST['id'];

    $sql = sprintf("SELECT r.id, r.nombre, p.fecha, p.numero, p.mensaje, p.variable1 FROM rifas r, rifas_participantes p WHERE p.fecha>=%s AND p.fecha<=%s AND p.idRifa=r.id AND (-1=%s OR r.id=%s) ORDER BY r.id, p.fecha", GetSQLValueString($dateFrom, "date"), GetSQLValueString($dateTo, "date"), GetSQLValueString($id, "int"), GetSQLValueString($id, "int"));
    $rs = mysql_query($sql, $conexion) or die(register_mysql_error("XR001", mysql_error()));

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
        $name = fixSheetName($row['nombre']);
        if ($lname != $name) {
            if ($j > 0) {
                $objWorksheet1 = $objPHPExcel->createSheet();
            }
            $objPHPExcel->setActiveSheetIndex($j);
            $objPHPExcel->getActiveSheet()->setTitle($name);
            $i = 1;
            ++$j;
            $lname = $name;
        }

        $objPHPExcel->getActiveSheet()->SetCellValue("A$i", $row['fecha']);
        $objPHPExcel->getActiveSheet()->SetCellValue("B$i", $row['numero']);
        $objPHPExcel->getActiveSheet()->SetCellValue("C$i", $row['mensaje']);
        $objPHPExcel->getActiveSheet()->SetCellValue("D$i", $row['variable1']);
        ++$i;
    }

    $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
    $path = "rifas.xlsx";
    $fullpath = tempnam(sys_get_temp_dir(), 'rifas');
    $objWriter->save($fullpath);

    setXLSHeaders($path);

    if (strcmp($_SERVER['REQUEST_METHOD'], 'HEAD') != 0) {
        $i = readfile($fullpath);
    }
    exit();
} else {
    $actividades = queryToArray("SELECT -1, '-- Todas' AS nombre UNION SELECT id, nombre FROM rifas WHERE estado=1 ORDER BY nombre ASC;", $conexion);
    $smarty = new Smarty();
    $smarty->assign("dateFrom", $dateFrom);
    $smarty->assign("dateTo", $dateTo);
    $smarty->assign("activityOptions", $actividades);

    $smarty->assign("title", "Consolidado de Rifas");
    $smarty->assign("activityLabel", "Actividad");
    $smarty->assign("dateFromLabel", "Desde");
    $smarty->assign("dateToLabel", "Hasta");
    $smarty->display(sprintf("../skins/%s/admin/22.tpl", $_CONF['skin']));
}