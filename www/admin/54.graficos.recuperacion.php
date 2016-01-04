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

    private $template = "54.graficos.recuperacion.html";
    private $html = "";

    function admin() {
        if (!isset($_SESSION['idAdmin'])) {
            header("Location: " . initPage());
            exit();
        } else if (!permission(54)) {
            header("Location: " . initPage());
            exit();
        }
        if (isset($_POST['txtHasta'])) {
            $this->getDatos();
        }
        $this->getPage();
    }

    function getDatos() {
        global $conexion;

        ini_set('max_execution_time', 0);
        ini_set("memory_limit", "786M");
        $sql = sprintf("INSERT INTO suscripciones_estadisticas SELECT idSuscripcion, DATE(%s), conteo FROM suscripciones_estadisticas WHERE fecha=DATE(DATE_ADD(%s, INTERVAL -1 DAY));", GetSQLValueString($_POST['txtHasta'], "text"), GetSQLValueString($_POST['txtHasta'], "text"));
        mysql_query($sql, $conexion) or die(mysql_error()); //die(register_mysql_error("CR002", mysql_error()));
        $this->getPage("Realizado");
        exit();
    }

    function getPage($texto = "") {
        $this->html = file_get_contents($this->template);
        $this->html = str_replace("@@texto@@", $texto, str_replace("@@hasta@@", date("Y-m-d"), str_replace("@@TITLE@@", "Recuperación de Gráficos", $this->html)));
        echo $this->html;
    }

}

$a = new admin();
?>