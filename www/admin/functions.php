<?php

if (!function_exists("GetSQLValueString")) {

    function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") {
        $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
        $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);
        switch ($theType) {
            case "text":
                $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
                break;
            case "long":
            case "int":
                $theValue = ($theValue != "") ? intval($theValue) : "NULL";
                break;
            case "double":
                $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
                break;
            case "date":
                $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
                break;
            case "defined":
                $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
                break;
        }
        return $theValue;
    }

}

function lastInsertID() {
    global $conexion;
    global $database_conexion;
    mysql_select_db($database_conexion, $conexion);
    $sql = "SELECT @@last_insert_id AS id";
    $rs = mysql_query($sql, $conexion) or die(mysql_error());
    $row = mysql_fetch_array($rs);
    return $row['id'];
}

function permission($i) {
    global $conexion;
    global $database_conexion;
    mysql_select_db($database_conexion, $conexion);
    $query_Recordset1 = sprintf("SELECT usuario FROM mantenimiento_permisos WHERE usuario=%s AND permiso=%s", $_SESSION['idAdmin'], $i);
    $Recordset1 = mysql_query($query_Recordset1, $conexion) or die(mysql_error());
    $totalRows_Recordset1 = mysql_num_rows($Recordset1);
    if ($totalRows_Recordset1 == 0) {
        return false;
    } else {
        return true;
    }
}

function initPage() {
    return "index.html";
}

function numLength() {
    return 11;
}

function numOutLength() {
    return 7;
}

function is_number($number) {
    $text = (string) $number;
    $textlen = strlen($text);
    if ($textlen == 0) {
        return 0;
    }
    for ($i = 0; $i < $textlen; $i++) {
        $ch = ord($text{$i});
        if (($ch < 48) || ($ch > 57))
            return 0;
    }
    return 1;
}

if (!function_exists("validarNumero")) {

    function validarNumero($numero) {
        if (strlen($numero) != 11) {
            return false;
        } else if (is_numeric($numero) == 0) {
            return false;
        }
        return true;
    }

}

function validarFecha($fecha) {
    $fechas = explode("-", $fecha);
    if (sizeof($fechas) != 3 || !checkdate(intval($fechas[1]), intval($fechas[2]), intval($fechas[0])))
        return false;
    return true;
}

function setAlert($msg) {
    return sprintf("onload=\"javascript: alert('%s')\"", $msg);
}

function printSelectNumeros($numero, $js = "") {
    global $conexion;
    global $database_conexion;
    mysql_select_db($database_conexion, $conexion);

    $query_rsNumeros = "SELECT numero FROM numeros";
    $rsNumeros = mysql_query($query_rsNumeros, $conexion) or die(register_mysql_error("ASN003", mysql_error()));
    $html = "<select name='numero' id='numero' class='textbox-small' $js>";
    while ($row_rsNumeros = mysql_fetch_assoc($rsNumeros)) {
        $selected = ($row_rsNumeros['numero'] == $numero) ? "selected" : "";
        $html .= "<option value='" . $row_rsNumeros['numero'] . "' " . $selected . ">" . $row_rsNumeros['numero'] . "</option>";
    }
    $html .= "</select>";
    return $html;
}
