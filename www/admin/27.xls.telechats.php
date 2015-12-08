<?php
require_once('functions.php');
require_once ('../connections/conexion.php');
require_once ('../functions/functions.php');
require_once ('../functions/db.php');
require_once ('../functions/xls.php');
require_once ('../functions/PHPExcel.php');
require_once ('../functions/PHPExcel/Writer/Excel2007.php');

session_start();
if (!isset($_SESSION['idAdmin'])) {
    header("Location: " . initPage());
    exit();
} else if (!permission(27)) {
    header("Location: " . initPage());
    exit();
}

$desde = strftime("%Y-%m-%d 00:00:00", time());
$hasta = strftime("%Y-%m-%d 23:59:00", time());

if (isset($_POST['desde']) && isset($_POST['hasta'])) {
    ini_set('max_execution_time', 0);
    ini_set("memory_limit", "786M");

    $desde = $_POST['desde'];
    $hasta = $_POST['hasta'];

    $sql = sprintf("SELECT r.id, r.nombre, p.fecha, p.numero, p.mensaje FROM telechats r, telechats_mensajes p WHERE p.fecha>=%s AND p.fecha<=%s AND p.idTelechat=r.id ORDER BY r.nombre, p.fecha", GetSQLValueString($desde, "date"), GetSQLValueString($hasta, "date"));
    $rs = mysql_query($sql, $conexion) or die(register_mysql_error("XT001", mysql_error()));

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
        ++$i;
    }

    $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
    $path = "telechats.xlsx";
    $fullpath = tempnam(sys_get_temp_dir(), 'telechats');
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
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>Consolidado de Telechats</title>
        <link rel="stylesheet" type="text/css" href="../css/style.css" />
        <style type="text/css">@import url(../lib/calendar/calendar-brown.css);</style>
        <script type="text/javascript" src="../lib/calendar/calendar.js"></script>
        <script type="text/javascript" src="../lib/calendar/lang/calendar-es.js"></script>
        <script type="text/javascript" src="../lib/calendar/calendar-setup.js"></script>
    </head>
    <body>
        <form id="frmGenerar" name="frmGenerar" method="post">
            <table width="220px" cellpadding="0" cellspacing="0">
                <tr><th colspan="2">Consolidado de Telechats</th></tr>
                <tr><td colspan="0">&nbsp;</td></tr>     
                <tr>
                    <td>Desde:</td>
                    <td><input class="textbox-small" type="text" id='desde' name='desde' value='<?php echo $desde ?>' />&nbsp;<button name="desde_btn" id="desde_btn">..</button></td>
                </tr>
                <tr><td colspan="2">&nbsp;</td></tr>  
                <tr>
                    <td>Hasta:</td>
                    <td><input class="textbox-small" type="text" id='hasta' name='hasta' value='<?php echo $hasta ?>' />&nbsp;<button name="hasta_btn" id="hasta_btn">..</button></td>
                </tr>
                <tr><td colspan="2">&nbsp;</td></tr>    
                <tr><td colspan="2" style="text-align: right"><input name="accion" type="submit" class="button" id="" value="Generar" /></td></tr>    
                <tr><td colspan="2">&nbsp;</td></tr>    
                <tr><th colspan="2" style="text-align: center"><a href="menu.php">Menu</a></th></tr>
            </table>
        </form>
    </body>  
    <script type="text/javascript">
        Calendar.setup({
            inputField: "desde",
            ifFormat: "%Y-%m-%d %H:%M:%S",
            showsTime: true,
            button: "desde_btn",
            singleClick: true,
            step: 1
        });

        Calendar.setup({
            inputField: "hasta",
            ifFormat: "%Y-%m-%d %H:%M:%S",
            showsTime: true,
            button: "hasta_btn",
            singleClick: true,
            step: 1
        });
    </script>
</html>