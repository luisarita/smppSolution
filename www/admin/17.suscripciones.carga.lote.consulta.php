<?php

session_start();

require_once('../connections/conexion.php');
require_once('../functions/functions.php');
require_once('../functions/db.php');
require_once('functions.php');

class admin {

    private $template = "17.suscripciones.carga.lote.consulta.html";
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
        if (isset($_POST['sltSuscripcion'])) {
            $this->getLinks();
        }
        $this->getPage();
    }

    function getLinks() {
        global $conexion;
        $links = "<tr><th colspan=\"4\">Archivos cargados</th></tr>
             <tr><td style=\"text-align:center\">Fecha</td><td style=\"text-align:center\">Usuario</td><td style=\"text-align:center\">Conteo</td><td style=\"text-align:center\">Link</td></tr>";
        $sql = sprintf("SELECT fecha, usuario, conteo_carga, nombre_archivo FROM suscripciones_carga_lote WHERE idSuscripcion = %s", GetSQLValueString($_POST['sltSuscripcion'], "text"));
        $rs = mysql_query($sql, $conexion) or die(register_mysql_error("SC002", mysql_error()));
        while ($row = mysql_fetch_array($rs)) {
            $links .= "<tr><td style=\"text-align:center\">" . $row[0] . "</td><td style=\"text-align:center\">" . $row[1] . "</td><td style=\"text-align:center\">" . $row[2] . "</td><td style=\"text-align:center\"><a href=\"" . $row[3] . "\">Descargar</a></td></tr>";
        }
        $this->html = str_replace("@@CONTENT2@@", $links, $this->html);
    }

    function getPage() {
        global $conexion;
        $opciones = "";
        $sql = sprintf("SELECT id, nombre FROM suscripciones");
        $rs = mysql_query($sql, $conexion) or die(register_mysql_error("SC001", mysql_error()));

        while ($row = mysql_fetch_array($rs)) {
            $opciones .= "<option value='" . $row[0] . "'>" . $row[1] . "</option>";
        }
        echo str_replace("@@CONTENT2@@", "", str_replace("@@CONTENT1@@", $opciones, str_replace("@@TITLE@@", "Consulta de carga por lotes", $this->html)));
    }

}

$a = new admin();
?>
