<?php

require_once('../connections/conexion.php');
require_once('../functions/functions.php');
require_once('../functions/db.php');
require_once ('../functions/xls.php');
require_once ('../functions/PHPExcel.php');
require_once ('../functions/PHPExcel/Writer/Excel2007.php');

require_once('../conf.php');
require_once('functions.php');

class admin {

    private $templateHTML = "";
    private $codigo = 0;
    private $idSuscripcion = 0;

    function admin() {

        $arrStr = explode("/", $_SERVER['SCRIPT_NAME']);
        $arrStr = array_reverse($arrStr);
        $arrStr = explode(".", $arrStr[0]);
        $this->codigo = $arrStr[0];

        session_start();
        if (!isset($_SESSION['idAdmin'])) {
            header("Location: " . initPage());
            exit();
        } else if (!permission($this->codigo)) {
            header("Location: " . initPage());
            exit();
        }
    }

    function getLists($id) {
        $this->idSuscripcion = intval($_GET['idSuscripcion']);

        switch ($id) {
            case 0:
                $sql = sprintf("SELECT s.nombre, p.numero, p.fecha, p.cantidad FROM suscripciones s, suscripciones_participantes p WHERE s.id=p.idsuscripcion AND p.idSuscripcion=%s", $this->idSuscripcion); // No finalizar con ;
                break;
            case 1:
                $sql = sprintf("SELECT s.nombre, p.numero, p.fecha, p.cantidad FROM suscripciones s, suscripciones_participantes p WHERE p.fecha - NOW() <= s.duracion AND s.id=p.idsuscripcion AND p.notificado=0 AND p.idSuscripcion=%s AND p.estado=1", $this->idSuscripcion); // No finalizar con ;
                break;
        }
        return $sql;
    }

    function printLists($id) {
        global $_CONF;
        global $conexion;

        $sql = sprintf($this->getLists($id));
        $rs = mysql_query($sql, $conexion) or die();

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

            $objPHPExcel->getActiveSheet()->SetCellValue("A$i", $row['numero']);
            $objPHPExcel->getActiveSheet()->SetCellValue("B$i", $row['fecha']);
            $objPHPExcel->getActiveSheet()->SetCellValue("C$i", $row['cantidad']);
            ++$i;
        }

        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
        $path = "recepcion.xlsx";
        $fullpath = tempnam(sys_get_temp_dir(), 'recepcion');
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

    function getMovements() {
        global $conexion;
        $this->idSuscripcion = intval($_GET['idSuscripcion']);

        $sql = sprintf("SELECT COUNT(DISTINCT numero) AS conteo FROM (%s) dt;", $this->getLists(0));
        $rs = mysql_query($sql, $conexion) or die(mysql_error());
        $row = mysql_fetch_array($rs);
        $conteo1 = $row['conteo'];

        $sql = sprintf("SELECT COUNT(DISTINCT numero) AS conteo FROM (%s) dt;", $this->getLists(1));
        $rs = mysql_query($sql, $conexion) or die(mysql_error());
        $row = mysql_fetch_array($rs);
        $conteo2 = $row['conteo'];

        $sql = sprintf("SELECT id, mensaje, fecha, fecha_envio, estado FROM suscripciones_mensajes WHERE estado IN (1,0) AND idSuscripcion=%s", $this->idSuscripcion); // No finalizar con ;
        $sql = sprintf("SELECT mensaje, fecha, fecha_envio, CASE estado WHEN 1 THEN 'Enviado' WHEN 0 THEN 'Pendiente' END AS estado FROM (%s UNION %s) dt ORDER BY fecha DESC, fecha_envio DESC, estado DESC;", $sql, str_replace("suscripciones_mensajes", "suscripciones_mensajes_bck", $sql));
        $rs = mysql_query($sql, $conexion) or die(); //echo $sql;

        $html = sprintf("
    <table cellpadding='0' cellspacing='0' style='width: 100%%'>
     <tr><th colspan='4' class='subtitulo'>
      Suscriptores Total: <a style='color: #FFF' href='?lists&id=0&idSuscripcion=%s'>%s</a><br/>
      Suscriptores Activos: <a style='color: #FFF' href='?lists&id=1&idSuscripcion=%s'>%s</a>
     </th></tr>
     <tr>
      <th class='subtitulo'>Mensaje</th>
      <th class='subtitulo'>Fecha Programada</th>
      <th class='subtitulo'>Fecha Envio</th>
      <th class='subtitulo'>Estado</th>
     </th></tr>
     <tr><td>&nbsp;</td></tr>
    ", $this->idSuscripcion, number_format($conteo1, 0), $this->idSuscripcion, number_format($conteo2, 0));

        while ($row = mysql_fetch_array($rs)) {
            $html .= sprintf("<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>", $row['mensaje'], $row['fecha'], $row['fecha_envio'], $row['estado']);
        }
        $html .= "
     <tr><td>&nbsp;</td></tr>
    </table>";

        return $html;
    }

    function getContent() {
        global $conexion;
        $sql = "SELECT id, nombre FROM suscripciones WHERE activa=1 ORDER BY nombre;";
        $rs = mysql_query($sql, $conexion) or die();

        $html = "<table>";
        while ($row = mysql_fetch_array($rs)) {
            $html .= sprintf("<tr><td>%s</td><td><a href='35.suscripciones.forma.php?idSuscripcion=%s&actividad' target='mainFrame' >Actividad</a></td><td><a href='?idSuscripcion=%s&movements' target='mainFrame' >Movimientos</a></td></tr>", $row['nombre'], $row['id'], $row['id']);
        }
        $html .= "</table>";

        return $html;
    }

    function printHTML() {
        global $_CONF;
        if (isset($_CONF['skin']) && $_CONF['skin'] != "")
            $this->templateHTML = "../skins/" . $_CONF['skin'] . "/admin/" . $this->codigo . ".html";
        $html = file_get_contents($this->templateHTML);
        $html = str_replace("@@CONTENT@@", $this->getContent(), $html);
        if (isset($_CONF['skin']) && $_CONF['skin'] != "")
            $html = str_replace("@@SKIN@@", $_CONF['skin'], $html);
        return $html;
    }

    function printMovements() {
        global $_CONF;
        if (isset($_CONF['skin']) && $_CONF['skin'] != "")
            $this->templateHTML = "../skins/" . $_CONF['skin'] . "/admin/" . $this->codigo . ".html";
        $html = file_get_contents($this->templateHTML);
        $html = str_replace("@@CONTENT@@", $this->getMovements(), $html);
        if (isset($_CONF['skin']) && $_CONF['skin'] != "")
            $html = str_replace("@@SKIN@@", $_CONF['skin'], $html);
        return $html;
    }

    function printEmpty() {
        global $_CONF;
        if (isset($_CONF['skin']) && $_CONF['skin'] != "")
            $this->templateHTML = "../skins/" . $_CONF['skin'] . "/admin/" . $this->codigo . "b.html";
        $html = file_get_contents($this->templateHTML);
        if (isset($_CONF['skin']) && $_CONF['skin'] != "")
            $html = str_replace("@@SKIN@@", $_CONF['skin'], $html);
        return $html;
    }

}

$a = new admin();
if (isset($_GET['empty'])) {
    echo $a->printEmpty();
} else if (isset($_GET['movements'])) {
    echo $a->printMovements();
} else if (isset($_GET['lists']) && isset($_GET['id']) && is_number($_GET['id'])) {
    echo $a->printLists(intval($_GET['id']));
} else {
    echo $a->printHTML();
}
?>