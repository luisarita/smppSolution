<?php

function select_path($tipo) {
    global $FONDOS_PATH;
    global $PROG_PATH;
    global $RINGM_PATH;
    global $RINGP_PATH;
    global $SCREEN_PATH;
    global $LOGO_PATH;
    global $TEMA_PATH;
    global $THUMBNAILS;
    global $SURVEY_WALL;
    switch ($tipo) {
        case 8: return $SURVEY_WALL;
        case 7: return $FONDOS_PATH;
        case 2: return $PROG_PATH;
        case 3: return $RINGM_PATH;
        case 4: return $RINGP_PATH;
        case 5: return $SCREEN_PATH;
        case 1: return $LOGO_PATH;
        case 6: return $TEMA_PATH;
    }
}

function get_type($filename) {
    switch (strrchr($filename, '.')) {
        case '.jpg':
            return 'image/jpeg';
        case '.gif':
            return 'image/gif';
        case '.wav':
            return 'audio/wav';
        case '.mid':
            return 'audio/midi';
        case '.midi':
            return 'audio/midi';
        case '.mp3':
            return 'audio/x-mp3';
    }
    return 'multipart/mixed';
}

if (!function_exists("GetSQLValueString")) {

    function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") {
        $theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;
        switch ($theType) {
            case "text":
                $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
                break;
            case "long":
            case "int":
                $theValue = ($theValue != "" && trim($theValue) != "NULL") ? intval($theValue) : "NULL";
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

if (!function_exists("GetPostOrGet")) {

    function GetPost($variable, $default = "") {
        if (isset($_POST[$variable])) {
            if (get_magic_quotes_gpc()) {
                return $_POST[$variable];
            } else {
                return addslashes($_POST[$variable]);
            }
        }
        return $default;
    }

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