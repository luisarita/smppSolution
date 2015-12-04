<?php

session_start();
require_once('../connections/conexion.php');
require_once('../functions/functions.php');
require_once('../functions/db.php');
require_once('functions.php');

class admin {

    private $template = "52.mantenimiento.recargas.sorteos.html";
    private $html = "";
    private $idRecarga = -1;
    private $fecha = "";

    function admin() {
        $this->html = file_get_contents($this->template);
        $this->getRecargas();

        if (isset($_POST['txtIdRecarga']) && $_POST['txtIdRecarga'] != -1) {
            $this->guardarSorteo();
        }

        if ((isset($_POST['sltRecarga']) && $_POST['sltRecarga'] != -1) || isset($_POST['txtIdRecarga'])) {
            $this->idRecarga = (isset($_POST['sltRecarga'])) ? $_POST['sltRecarga'] : $_POST['txtIdRecarga'];
            $this->fecha = (isset($_POST['txtFecha']) ? $_POST['txtFecha'] : $_POST['txtFechaB']);
            $this->html = str_replace("@@FECHA@@", $this->fecha, str_replace("value='" . $this->idRecarga . "'", "value='" . $this->idRecarga . "' selected", $this->html));
            $this->getSorteos();
        }
        $this->getPage();
    }

    function guardarSorteo() {
        global $conexion;
        global $database_conexion;
        mysql_select_db($database_conexion, $conexion);
        $sql = sprintf("UPDATE ac_recargas_sorteos SET monto = %s, limiteGanadores = %s, horaInicio = %s, horaFin = %s WHERE idRecarga=%s AND fecha=%s AND monto = %s", GetSQLValueString($_POST['sltMonto'], "int"), GetSQLValueString($_POST['txtLimiteGanadores'], "int"), GetSQLValueString($_POST['sltHoraInicio'], "int"), GetSQLValueString($_POST['sltHoraFin'], "int"), GetSQLValueString($_POST['txtIdRecarga'], "int"), GetSQLValueString($_POST['txtFechaE'], "text"), GetSQLValueString($_POST['txtMontoA'], "text"));
        mysql_query($sql, $conexion) or die(register_mysql_error("MRS003" . mysql_error(), mysql_error()));
    }

    function getSorteos() {
        global $conexion;
        global $database_conexion;
        mysql_select_db($database_conexion, $conexion);
        $listaSorteos = "<table><tr><th colspan = '8'>Lista de Sorteos</th></tr><tr><th>Fecha</th><th>Monto</th><th>Limite de ganadores</th><th>Hora de Inicio</th><th>Hora Final</th><th>Intervalo de sorteo</th><th>Conteo de intervalo</th><th>Acci&oacute;n</th></tr>";
        $sql = sprintf("SELECT monto, limiteGanadores, horaInicio, horaFin, intervaloSorteo, conteoIntervalo, fecha FROM ac_recargas_sorteos WHERE idRecarga=%s AND fecha>=%s ORDER BY fecha DESC", GetSQLValueString($this->idRecarga, "int"), GetSQLValueString($this->fecha, "text"));
        $rs = mysql_query($sql, $conexion) or die(register_mysql_error("MRS002", mysql_error()));

        while ($row = mysql_fetch_array($rs)) {
            $listaSorteos .= "<form method='POST'><tr><td>" . $row['fecha'] . "</td><td><input class='textbox-small' style='text-align:right' type='hidden' name='txtMontoA' value='" . $row["monto"] . "'/><input type='hidden' name='txtFechaE' value='" . $row['fecha'] . "'/><input type='hidden' value='" . $this->fecha . "' name='txtFechaB'/><input type='hidden' name = 'txtIdRecarga' value='" . $this->idRecarga . "'><select name='sltMonto'>" . $this->getMontos($row["monto"]) . "'</select></td><td style='text-align:center'><input class='textbox-small' type='text' value='" . $row["limiteGanadores"] . "' name='txtLimiteGanadores'/></td><td style='text-align:center'><select name='sltHoraInicio'>" . $this->getHoras($row["horaInicio"]) . "</select></td><td style='text-align:center'><select name='sltHoraFin'>" . $this->getHoras($row["horaFin"]) . "</select></td><td style='text-align:center'>" . $row['intervaloSorteo'] . "</td><td style='text-align:center'>" . $row['conteoIntervalo'] . "</td><td style='text-align:center'><input type='submit' value='Guardar' class='button'/></td></tr></form>";
        }
        $listaSorteos .= "</table>";
        $this->html = str_replace("@@SORTEO@@", $listaSorteos, $this->html);
    }

    function getMontos($monto) {
        global $conexion;
        global $database_conexion;
        $options = "";
        mysql_select_db($database_conexion, $conexion);
        $sql = "SELECT monto FROM ac_recargas_montos";
        $rs = mysql_query($sql, $conexion) or die(register_mysql_error("MRS004", mysql_error()));

        while ($row = mysql_fetch_array($rs)) {
            $options .= "<option" . ((doubleval($monto) == doubleval($row["monto"])) ? " selected" : "") . ">" . $row["monto"] . "</option>";
        }
        return $options;
    }

    function getHoras($hora) {
        $horas = "";
        for ($i = 1; $i < 25; $i++) {
            $horas .= "<option value='" . $i . "' " . ((intval($hora) == $i) ? "selected" : "") . ">" . $i . "</option>";
        }
        return $horas;
    }

    function getRecargas() {
        global $conexion;
        global $database_conexion;
        $options = "";
        mysql_select_db($database_conexion, $conexion);
        $sql = "SELECT id, nombre FROM ac_recargas WHERE activa=1;";
        $rs = mysql_query($sql, $conexion) or die(register_mysql_error("MRS001", mysql_error()));

        while ($row = mysql_fetch_array($rs)) {
            $options .= "<option value='" . $row['id'] . "'>" . $row['nombre'] . "</option>";
        }

        $this->html = str_replace("@@OPTION@@", $options, $this->html);
    }

    function getPage() {
        $this->html = str_replace("@@FECHA@@", date("Y-m-d"), str_replace("@@SORTEO@@", "", str_replace("@@TITLE@@", "Mantenimiento De Sorteos", $this->html)));
        echo $this->html;
    }

}

$a = new admin();
?>