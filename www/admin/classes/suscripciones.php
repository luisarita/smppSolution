<?php

class suscripciones {

    function getOpciones() {
        global $conexion;
        global $database_conexion;
        mysql_select_db($database_conexion, $conexion);

        $opciones = "";
        $rs = mysql_query("SELECT id, nombre FROM suscripciones WHERE activa=1 ORDER BY nombre;", $conexion) or die(register_mysql_error("ACS0001", mysql_error()));
        while ($row = mysql_fetch_array($rs)) {
            $opciones .= "<option value='" . $row['id'] . "'>" . $row['nombre'] . "</option>";
        }
        return $opciones;
    }

}
