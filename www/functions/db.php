<?php

if (!function_exists('register_mysql_error')) {

    function register_mysql_error($codigo = "", $mysql_error = "") {
        echo $codigo;

        global $conexion, $database_conexion;

        mysql_select_db($database_conexion, $conexion);
        $SQL = sprintf("INSERT INTO mantenimiento_errores (fecha, mensaje, codigo) VALUES (NOW(), %s, %s)", GetSQLValueString($mysql_error, "text"), GetSQLValueString($codigo, "text"));
        mysql_query($SQL, $conexion) or die();
    }

}