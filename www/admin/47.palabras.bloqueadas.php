<?php

session_start();

require_once('../connections/conexion.php');
require_once('../functions/functions.php');
require_once('../functions/db.php');
require_once('functions.php');

class admin {

    private $template = "47.palabras.bloqueadas.html";

    function admin() {
        if (!isset($_SESSION['idAdmin'])) {
            header("Location: " . initPage());
            exit();
        } else if (!permission(47)) {
            header("Location: " . initPage());
            exit();
        }

        if (isset($_POST['txtPalabra'])) {
            $this->agregarPalabra();
        } else if (isset($_POST['txtPalabraEditada'])) {
            $this->actualizarPalabra();
        } else if (isset($_POST['txtEliminar'])) {
            $this->eliminarPalabra();
        }
        echo $this->getHtml();
    }

    function actualizarPalabra() {
        global $conexion;

        $sql = sprintf("UPDATE mensajes_bloqueados SET palabra=%s WHERE id=%s;", GetSQLValueString($_POST['txtPalabraEditada'], "text"), GetSQLValueString($_POST['txtIdPalabra'], "int"));
        mysql_query($sql, $conexion) or die(register_mysql_error("BL0004", mysql_error()));
    }

    function agregarPalabra() {
        global $conexion;

        $sql = sprintf("INSERT INTO mensajes_bloqueados(id, palabra) VALUES (NULL, %s)", GetSQLValueString($_POST['txtPalabra'], "text"));
        mysql_query($sql, $conexion) or die(register_mysql_error("BL0003", mysql_error()));
    }

    function eliminarPalabra() {
        global $conexion;

        $sql = sprintf("DELETE FROM mensajes_bloqueados WHERE id = %s", GetSQLValueString($_POST['txtEliminar'], "int"));
        mysql_query($sql, $conexion) or die(register_mysql_error("BL0005", mysql_error()));
    }

    function getHtml() {
        global $conexion;

        $contenido = "
   <table>
    <tr>
     <th colspan='3'>Agregar expresi&oacute;n</th>
    </tr>
    <tr><td colspan='3'>&nbsp;</td></tr>";

        if (isset($_POST['txtEditar'])) {
            $sql = sprintf("SELECT palabra FROM mensajes_bloqueados WHERE id = %s", GetSQLValueString($_POST['txtEditar'], "int"));
            $rs = mysql_query($sql, $conexion) or die(register_mysql_error("BL0001", mysql_error()));
            $row = mysql_fetch_array($rs);
            $contenido .= "<tr>
     <td  colspan='3'>
      <form method='POST'>
       <input type='hidden' name = 'txtIdPalabra' value = '" . $_POST['txtEditar'] . "'/>
       <input type='text' name='txtPalabraEditada' value ='" . $row[0] . "'/><input  class='button' type='submit' value='Guardar'/>
      </form>
     </td>
    </tr>";
        } else {
            $contenido .= "<tr>
     <td  colspan='3'>
      <form method='POST'>
       <input type='text' name='txtPalabra'/><input  class='button' type='submit' value='Agregar'/>
      </form>
     </td>
    </tr>";
        }

        $contenido .= "<tr><td  colspan='3'>&nbsp;</td></tr>";

        $sql = sprintf("SELECT id, palabra FROM mensajes_bloqueados ORDER BY palabra;");
        $rs = mysql_query($sql, $conexion) or die(register_mysql_error("BL0002", mysql_error()));
        $contenido.= "<tr><th colspan='3'>Listado</th></tr>";

        while ($row = mysql_fetch_array($rs)) {
            $contenido.= "
    <tr>
     <td>" . $row[1] . "</td>
     <td>
      <form method='POST'><input type='hidden' value='" . $row[0] . "' name='txtEditar'><input type='submit' class='msg-button ' value='Editar'></form>
     </td><td>
      <form method='POST'><input type='hidden' value='" . $row[0] . "' name='txtEliminar'><input type='submit' class='warning-button-small' value='Eliminar'></form>
     </td>
    </tr>";
        }
        $contenido .= "</table>";
        $html = file_get_contents($this->template);
        $html = str_replace("@@TITLE@@", "Bloqueo de malas palabras", $html);
        $html = str_replace("@@CONTENT1@@", $contenido, $html);
        return $html;
    }

}

$r = new admin();
?>