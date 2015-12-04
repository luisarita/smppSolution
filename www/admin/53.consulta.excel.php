<?php

session_start();

require_once ('../connections/conexion.php');
require_once ('../functions/functions.php');
require_once ('../functions/db.php');
require_once ('../functions/xls.php');
require_once ('../functions/PHPExcel.php');
require_once ('../functions/PHPExcel/Writer/Excel2007.php');
require_once('functions.php');

class admin {

    private $template = "53.consulta.excel.html";
    private $html = "";

    function admin() {
        if (!isset($_SESSION['idAdmin'])) {
            header("Location: " . initPage());
            exit();
        } else if (!permission(17)) {
            header("Location: " . initPage());
            exit();
        }
        $this->html = file_get_contents($this->template);

        if (isset($_FILES["flExcel"])) {
            $this->createTempTable();
            $this->getRegistros();
            $this->getResult();
        }

        $this->getPage();
    }

    function getResult() {
        global $conexion;
        global $database_conexion;
        $resultado = "<table><tr><th colspan='5'>Resultado</th></tr>
   <tr><td style='border:solid 1px #FFF; text-align:center'>Id Actividad</td><td style='border:solid 1px #FFF; text-align:center'>Nombre Actividad</td><td style='border:solid 1px #FFF; text-align:center'>Tipo Actividad</td><td style='border:solid 1px #FFF; text-align:center'>Tipo</td><td style='border:solid 1px #FFF; text-align:center'>Conteo</td></tr>";
        mysql_select_db($database_conexion, $conexion);
        $rs = mysql_query("SELECT idActividad, nombreActividad, tipoActividad, nombreTipo, conteo FROM t1", $conexion);
        while ($row = mysql_fetch_array($rs)) {
            $resultado .= "<tr><td style='text-align:center'>" . $row["idActividad"] . "</td><td style='text-align:center'>" . $row["nombreActividad"] . "</td><td style='text-align:center'>" . $row["tipoActividad"] . "</td><td style='text-align:center'>" . $row["nombreTipo"] . "</td><td style='text-align:center'>" . $row["conteo"] . "</td></tr>";
        }
        $resultado .= "</table>";
        $this->html = str_replace("@@CONTENT1@@", $resultado, $this->html);
    }

    function createTempTable() {
        global $conexion;
        global $database_conexion;
        mysql_select_db($database_conexion, $conexion);
        mysql_query("DROP TEMPORARY TABLE IF EXISTS t1", $conexion) or die(register_mysql_error("CE0001", mysql_error()));
        mysql_query("CREATE TEMPORARY TABLE t1 (idActividad INT, nombreActividad VARCHAR(255), tipoActividad INT, nombreTipo VARCHAR(255), conteo INT, PRIMARY KEY (idActividad, tipoActividad))") or die(register_mysql_error("CE0002", mysql_error()));
        ;
    }

    function getRegistros() {
        global $conexion;
        global $database_conexion;
        mysql_select_db($database_conexion, $conexion);

        $archivo = $_FILES["flExcel"]['name'];
        $version = (substr(strrchr($archivo, '.'), 1) == "xlsx" ) ? "Excel2007" : "Excel5";
        $cont = 1;

        if ($archivo != "") {
            if (copy($_FILES['flExcel']['tmp_name'], $archivo)) {
                error_reporting(E_ALL);
                $reader = PHPExcel_IOFactory::createReader($version);
                $reader->setReadDataOnly(true);
                $phpExcel = $reader->load($archivo);
                $workSheet = $phpExcel->getActiveSheet();
                foreach ($workSheet->getRowIterator() as $row) {
                    $registro[0] = $phpExcel->getActiveSheet()->getCell('A' . $cont)->getValue();
                    $rs = mysql_query("SELECT tablaParticipantes, campo, id, tabla FROM accesos_actividades WHERE tablaParticipantes IS NOT NULL AND LENGTH(tablaParticipantes) > 0", $conexion);
                    while ($row = mysql_fetch_array($rs)) {
                        $sql = sprintf("INSERT INTO t1 SELECT act.id, act.nombre, a.id, a.nombre, 1 FROM %s p, %s act, accesos_actividades a where a.id=%s and p.%s=act.id and p.numero = %s on duplicate key update t1.conteo = t1.conteo+1", $row["tablaParticipantes"], $row["tabla"], $row["id"], $row["campo"], GetSQLValueString($registro[0], "text"));
                        mysql_query($sql, $conexion) or die(register_mysql_error("CE0003" . mysql_error(), mysql_error()));
                    }
                    $cont++;
                }
            }
        }
        unlink($archivo);
    }

    function getPage() {
        echo str_replace("@@CONTENT1@@", "", str_replace("@@TITLE@@", "Consulta Excel", $this->html));
    }

}

$a = new admin();
