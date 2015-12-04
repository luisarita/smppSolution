<?php

require_once('../functions/functions.php');
require_once('../connections/conexion.php');
require_once('../functions/db.php');
require_once('../conf.php');

class admin {

    function imagen() {
        global $conexion;
        global $database_conexion;

        mysql_select_db($database_conexion, $conexion);
        $resultado = mysql_query(sprintf("SELECT logo_archivo, log_tipo FROM recordatorios WHERE id=%s;", intval($_GET['id'])));
        $imagen = mysql_fetch_assoc($resultado);

        header("Content-type: $imagen[log_tipo]");
        return $imagen['logo_archivo'];
    }

}

$objeto = new admin();
echo $objeto->imagen();
?>