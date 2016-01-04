<?php

require_once('functions.php');
require_once ('../connections/conexion.php');
require_once ('../functions/functions.php');
require_once ('../functions/db.php');
require_once ('../functions/xls.php');
require_once ('../functions/PHPExcel.php');
require_once ('../functions/PHPExcel/Writer/Excel2007.php');

session_start();

class admin {

    private $idSuscripcion;
    private $tipos;

    function admin() {
        if (!isset($_SESSION['idAdmin'])) {
            header("Location: " . initPage());
            exit();
        } else if (!permission(24)) {
            header("Location: " . initPage());
            exit();
        }
        $this->idSuscripcion = (isset($_POST['id'])) ? $_POST['id'] : -1;
    }

    function generarExcel() {
        global $conexion;
        $letras = array(1 => 'B', 2 => 'C', 3 => 'D', 4 => 'E', 5 => 'F');
        $numVars = 0;
        $cont = 1;
        $condicion = "";
        $variables = "";
        $tipos = array(1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0);

        if (isset($_POST['accion']) && $this->idSuscripcion != -1) {
            ini_set('max_execution_time', 0);
            ini_set("memory_limit", "786M");
            $vars = $this->getNumVars();

            $sql = sprintf("SELECT tipo FROM suscripciones_preguntas sp WHERE sp.idSuscripcion = %s ORDER BY variable ASC;", GetSQLValueString($this->idSuscripcion, "int"));
            $rs = mysql_query($sql, $conexion);

            while ($row = mysql_fetch_array($rs)) {
                $tipos[$cont] = $row[0];
                $cont++;
            }
            $cont = 1;

            while ($cont <= count($vars)) {
                if ($vars[$cont - 1] != -1) {
                    $numVars = $numVars + 1;
                    $variables .= ", p.variable" . $vars[$cont - 1];
                    //$condicion .= " AND p.variable" . $vars[$cont-1] . " != '' AND p.variable" . $vars[$cont-1] . " IS NOT NULL ";
                }
                $cont++;
            }
            $cont = 1;

            // $sql = sprintf("SELECT r.nombre, p.numero @@variables@@ FROM suscripciones r, suscripciones_participantes p WHERE p.idSuscripcion=r.id AND r.id=%s AND r.aplicarVariables =1 @@condicion@@ ORDER BY r.nombre, p.fecha;", GetSQLValueString($this->idSuscripcion, "int"));
            $sql = sprintf("SELECT r.nombre, p.numero @@variables@@ FROM suscripciones r, suscripciones_participantes p WHERE p.idSuscripcion=r.id AND r.id=%s  @@condicion@@ ORDER BY r.nombre, p.fecha;", GetSQLValueString($this->idSuscripcion, "int"));
            $sql = str_replace("@@condicion@@", $condicion, $sql);
            $sql = str_replace("@@variables@@", $variables, $sql);
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
                $name = fixName($row[0]);
                if ($lname != $name) {
                    if ($j > 0)
                        $objWorksheet1 = $objPHPExcel->createSheet();
                    $objPHPExcel->setActiveSheetIndex($j);
                    $objPHPExcel->getActiveSheet()->setTitle($name);
                    $i = 1;
                    ++$j;
                    $lname = $name;
                }
                $objPHPExcel->getActiveSheet()->SetCellValue("A$i", $row[1]);

                while ($cont <= $numVars) {
                    $data = mysql_fetch_field($rs, $cont + 1);
                    $objPHPExcel->getActiveSheet()->SetCellValue($letras[$cont] . "$i", $this->getRespuesta($this->tipos[substr($data->name, -1, 1) - 1], $row[$cont + 1]));
                    $cont++;
                }
                $cont = 1;
                ++$i;
            }

            $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
            $path = "suscripcion.xlsx";
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

    function getRespuesta($tipo, $dato) {
        global $conexion;
        $cont = 0;

        if ($tipo == 1) {
            $sql = sprintf("SELECT valor FROM suscripciones_preguntas_opciones spo WHERE spo.idSuscripcion = %s AND spo.variable = %s AND spo.letra = %s;", GetSQLValueString($this->idSuscripcion, "int"), GetSQLValueString($tipo, "int"), GetSQLValueString($dato, "text"));
            $rs = mysql_query($sql, $conexion) or die();
            if (mysql_num_rows($rs) == 1) {
                $row = mysql_fetch_array($rs);
                return $row[0];
            } else {
                return "Opcion vacia";
            }
        } else {
            return $dato;
        }
    }

    function getNumVars() {
        global $conexion;
        $vars = array();
        $cont = 0;
        $sql = sprintf("SELECT sp.preguntas, sp.tipo FROM suscripciones_preguntas sp WHERE sp.idSuscripcion=%s ORDER BY variable ASC;", GetSQLValueString($this->idSuscripcion, "int"));

        $rs = mysql_query($sql, $conexion) or die(mysql_error());
        while ($row = mysql_fetch_array($rs)) {
            if (str_replace(" ", "", $row[0]) == "") {
                $vars[$cont] = -1;
                $this->tipos[$cont] = 0;
            } else {
                $vars[$cont] = $cont + 1;
                $this->tipos[$cont] = $row[1];
            }
            $cont++;
        }
        return $vars;
    }

    function getOpciones() {
        global $conexion;

        $opciones = "";
        $rs = mysql_query("SELECT id, nombre FROM suscripciones WHERE activa=1 ORDER BY nombre;", $conexion) or die(register_mysql_error("SCL0003", mysql_error()));
        //$rs = mysql_query("SELECT id, nombre FROM suscripciones WHERE activa=1 AND aplicarVariables=1 ORDER BY nombre;", $conexion) or die(register_mysql_error("SCL0003", mysql_error()));

        $opciones .= (isset($_POST['id']) && $_POST['id'] != 'no') ? '' : '<option value="no">Seleccione la suscripci&oacute;n</option>';
        while ($row = mysql_fetch_array($rs)) {
            $selected = ($this->idSuscripcion == $row['id']) ? "selected" : "";
            $opciones .= sprintf('<option value="%s" %s>%s</option>', $row['id'], $selected, $row['nombre']);
        }
        return $opciones;
    }

    function getPage($title = "Descarga de Suscripciones") {
        $html = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
 <html xmlns="http://www.w3.org/1999/xhtml">
  <head>
   <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
   <title>' . $title . '</title>
   <link rel="stylesheet" type="text/css" href="../css/style.css" />
   <style type="text/css">@import url(../lib/calendar/calendar-brown.css);</style>
   <script type="text/javascript" src="../lib/calendar/calendar.js"></script>
   <script type="text/javascript" src="../lib/calendar/lang/calendar-es.js"></script>
   <script type="text/javascript" src="../lib/calendar/calendar-setup.js"></script>
  </head>
  <body>
   <form id="frmGenerar" name="frmGenerar" method="post">
    <table width="220px" cellpadding="0" cellspacing="0">
     <tr><th colspan="2">' . $title . '</th></tr>
     <tr><td colspan="2">&nbsp;</td></tr>
     <tr><td>Suscripcion:</td><td><select id="id" name = "id" onchange="document.frmGenerar.submit()">' . $this->getOpciones() . '</select></td></tr>
     <tr><td colspan="2">&nbsp;</td></tr>        
     <tr><td colspan="2" style="text-align: right">' . (($this->idSuscripcion == -1) ? '' : '<input name="accion" type="submit" class="button" id="" value="Generar" />') . '</td></tr>
     <tr><td colspan="2">&nbsp;</td></tr>    
     <tr><th colspan="2" style="text-align: center"><a href="menu.php">Menu</a></th></tr>
    </table>
   </form>
  </body>  
 </html>';
        return $html;
    }

}

$excel = new admin();
$excel->generarExcel();
echo $excel->getPage();
?>