<?php

session_start();
require_once('../connections/conexion.php');
require_once('../functions/functions.php');
require_once('../functions/db.php');
require_once("../functions/PHPExcel.php");
require_once('../conf.php');
require_once('functions.php');

class admin {

    private $template = "30.suscripciones.anulacion.lotes.html";
    private $html = "";
    private $directorio = "../_repositorio/suscripciones-anulacion/";

    function admin() {
        if (!isset($_SESSION['idAdmin'])) {
            header("Location: " . initPage());
            exit();
        } else if (!permission(30)) {
            header("Location: " . initPage());
            exit();
        }
        $this->html = file_get_contents($this->template);
        if (isset($_FILES["flExcel"]['name'])) {
            $conteo = $this->processFile();
            $this->html = str_replace("@@RESULT@@", "<tr><th colspan='2'>Conteo</th></tr><tr><td colspan='2' style='text-align:center'>" . $conteo . "</td></tr><tr><td colspan='2'>&nbsp;</td></tr>", $this->html);
        }
        $this->getPage();
    }

    function processRecords($records, $file) {
        global $conexion;
        global $_CONF;

        $conteo = 0;
        mysql_query("START TRANSACTION;") or die(register_mysql_error("SAL001", mysql_error()));
        //print_r($records); exit();
        foreach ($records as $key => $registro) {
            if (strlen($registro) < $_CONF['num_length']){
                $registro = $_CONF['area_code'] . $registro;
            }
            if (strlen($registro) == $_CONF['num_length']) {
                $sql = sprintf("UPDATE suscripciones_participantes SET estado=0 , obs_anl=%s WHERE numero=%s;", GetSQLValueString("Desuscripcion por lotes " . date("Y-m-d H:i:s"), "text"), $registro);
                mysql_query($sql, $conexion) or die($sql . register_mysql_error("SAL002", mysql_error()));
                ++$conteo;
            }
        }
        $sql = sprintf("INSERT INTO desuscripcion_carga_lote(id, fecha, usuario, conteo_carga, nombre_archivo) VALUES(NULL, NOW(), %s, %s, %s)", GetSQLValueString($_SESSION['usuario'], "text"), GetSQLValueString($conteo, "int"), GetSQLValueString($file, "text"));
        mysql_query($sql, $conexion) or die(register_mysql_error("SAL0004", mysql_error()));
        mysql_query("COMMIT;") or die(register_mysql_error("SAL003", mysql_error()));
        return $conteo;
    }

    function processFile() {
        $file = $this->directorio . date("YmdHis") . "." . substr(strrchr($_FILES["flExcel"]['name'], '.'), 1);
        switch (substr(strrchr($file, '.'), 1)) {
            case "xlsx":
                $version = "Excel2007";
                break;
            case "xls":
                $version = "Excel5";
                break;
            case "txt":
                $version = "TextFile";
                break;
        }

        $records = array();

        if (copy($_FILES['flExcel']['tmp_name'], $file)) {
            if ($file != "" && ($version == "Excel2007" || $version == "Excel5")) {
                $reader = PHPExcel_IOFactory::createReader($version);
                $reader->setReadDataOnly(true);
                $phpExcel = $reader->load($file);
                $workSheet = $phpExcel->getActiveSheet();

                $cont = 1;
                foreach ($workSheet->getRowIterator() as $row) {
                    $records[] = $phpExcel->getActiveSheet()->getCell('A' . $cont)->getValue();
                    ++$cont;
                }
            } else if ($file != "" && ($version == "TextFile")) {
                $f = fopen($file, "r");
                while ($line = fgets($f, 1000)) {
                    $records[] = trim($line);
                }
            }
        } else {
            die("Error al copiar archivo a " . $file);
        }
        return $this->processRecords($records, $file);
    }

    function getPage() {
        $this->html = str_replace("@@RESULT@@", "", $this->html);
        echo $this->html;
    }

}

$a = new admin();