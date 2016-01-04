<?php

session_start();

require_once('../connections/conexion.php');
require_once('../functions/functions.php');
require_once('../functions/db.php');
require_once('functions.php');

class admin {

    private $template = "51.conexiones.clientes.html";
    private $html = "";

    function admin() {

        if (!isset($_SESSION['idAdmin'])) {
            header("Location: " . initPage());
            exit();
        } else if (!permission(51)) {
            header("Location: " . initPage());
            exit();
        }

        if (isset($_POST['txtCrearCliente'])) {
            $this->crearCliente();
        } else if (isset($_POST['txtGuardarCliente'])) {
            $this->actualizarCliente();
        }
        $this->getPage();
    }

    function agregarIps($idCliente, $conexion) {
        $listaCodigos = explode(",", $_POST['txtListaIPs']);
        foreach ($listaCodigos as $indice => $valor) {
            $registro = explode("@", $valor);
            $sql = sprintf("INSERT INTO conexiones_direcciones(id, idCliente, ip, numero) VALUE(NULL, %s, %s, %s)", GetSQLValueString($idCliente, "int"), GetSQLValueString($registro[1], "text"), GetSQLValueString($registro[0], "text"));
            mysql_query($sql, $conexion) or die(register_mysql_error("CC007", mysql_error()));
        }
    }

    function actualizarCliente() {
        global $conexion;

        mysql_query("BEGIN", $conexion) or die(register_mysql_error("CC010", mysql_error()));
        $sql = sprintf("UPDATE conexiones_clientes SET nombre=%s WHERE id=%s", GetSQLValueString($_POST['txtGuardarCliente'], "text"), GetSQLValueString($_POST['txtIdClienteEditar'], "int"));
        mysql_query($sql, $conexion) or die(register_mysql_error("CC001", mysql_error()));
        // Eliminamos las claves anteriores si existe alguna.
        $sql = sprintf("DELETE FROM conexiones_direcciones WHERE idCliente = %s", GetSQLValueString($_POST['txtIdClienteEditar'], "int"));
        mysql_query($sql, $conexion) or die(register_mysql_error("CC006", mysql_error()));

        // Agregamos las claves nuevas
        $this->agregarIps($_POST['txtIdClienteEditar'], $conexion);
        mysql_query("COMMIT", $conexion) or die(register_mysql_error("CC011", mysql_error()));
    }

    function crearCliente() {
        global $conexion;
        $sql = sprintf("INSERT INTO conexiones_clientes(id, nombre) VALUES(NULL, %s)", GetSQLValueString($_POST['txtCrearCliente'], "text"));
        mysql_query($sql, $conexion) or die(register_mysql_error("CC002", mysql_error()));
        //agregamos los valores
        $rs = mysql_query("SELECT LAST_INSERT_ID()", $conexion) or die(register_mysql_error("CC009", mysql_error()));
        $row = mysql_fetch_array($rs);
        //agregamos las claves
        $this->agregarIps($row[0], $conexion);
    }

    function getListaConexiones() {
        global $conexion;
        $lista = "<tr><th colspan ='2'>Lista de Clientes</th></tr>";
        if (isset($_POST['txtEditarCliente'])) {// ingresa aqui si se editara alguno de los clientes
            $sql = sprintf("SELECT id, nombre FROM conexiones_clientes WHERE id = %s", GetSQLValueString($_POST['txtEditarCliente'], "int"));
            $rs = mysql_query($sql, $conexion) or die(register_mysql_error("CC003", mysql_error()));
            $row = mysql_fetch_array($rs);
            $this->html = str_replace("@@campoId@@", "<input type='hidden' name='txtIdClienteEditar' value='" . $row[0] . "'/>", str_replace("@@value2@@", "Guardar", str_replace("@@value1@@", $row[1], str_replace("@@name1@@", "txtGuardarCliente", str_replace("@@tituloForm@@", "Editar cliente", $this->html)))));

            // Recuperar los codigos
            $sql = sprintf("SELECT numero, ip FROM conexiones_direcciones WHERE idCliente = %s", GetSQLValueString($_POST['txtEditarCliente'], "int"));
            $rs = mysql_query($sql, $conexion) or die(register_mysql_error("CC008", mysql_error()));
            $listaOpciones = "";
            while ($row = mysql_fetch_array($rs)) {
                $listaOpciones .= "<option value='" . $row[0] . "@" . $row[1] . "'>" . $row[0] . " - " . $row[1] . "</option>";
            }
            $this->html = str_replace("@@listaCodigos@@", $listaOpciones, $this->html);
        } else {
            $this->html = str_replace("@@campoId@@", "", str_replace("@@value2@@", "Crear", str_replace("@@value1@@", "", str_replace("@@name1@@", "txtCrearCliente", str_replace("@@tituloForm@@", "Agregar cliente", $this->html)))));
        }
        //Recuperamos la lista de conexion
        $rs = mysql_query("SELECT id, nombre FROM conexiones_clientes ORDER BY id DESC", $conexion) or die(register_mysql_error("CC004", mysql_error()));
        while ($row = mysql_fetch_array($rs)) {
            $lista .= "<tr><td>" . $row[1] . "</td><td style='text-align:right'><form method='POST'><input type='hidden' name='txtEditarCliente' value='" . $row[0] . "'><input type='submit' value='Editar' class='msg-button'></form></td></tr>";
        }
        $this->html = str_replace("@@CONTENT1@@", $lista, $this->html);
        // recuperamos el listados de codigos cortos para llenar el select
        $rs = mysql_query("SELECT numero FROM numeros", $conexion) or die(register_mysql_error("CC005", mysql_error()));
        $codigos = "";
        while ($row = mysql_fetch_array($rs)) {
            $codigos .= "<option value='" . $row[0] . "'>" . $row[0] . "</option>";
        }
        $this->html = str_replace("@@codigos@@", $codigos, $this->html);
    }

    function getPage() {
        $this->html = file_get_contents($this->template);
        $this->getListaConexiones();
        echo str_replace("@@TITLE@@", "Conexiones de los clientes", $this->html);
    }

}

$a = new admin();
?>