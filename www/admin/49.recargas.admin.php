<?php

session_start();

require_once('../connections/conexion.php');
require_once('../functions/functions.php');
require_once('../functions/db.php');
require_once('functions.php');

class admin {

    private $template = "49.recargas.admin.html";

    function admin() {
        if (!isset($_SESSION['idAdmin'])) {
            header("Location: " . initPage());
            exit();
        } else if (!permission(49)) {
            header("Location: " . initPage());
            exit();
        }
        if (isset($_POST['idRecarga'])) {
            $this->guardarMensajesRecargas();
        }
        echo $this->getHtml();
    }

    function getListaRecargas() {
        global $conexion;
        global $database_conexion;
        mysql_select_db($database_conexion, $conexion);
        $sql = "SELECT id, nombre FROM ac_recargas WHERE activa=1 ORDER BY nombre;";
        $opciones = "";
        $rs = mysql_query($sql, $conexion) or die(register_mysql_error("RA0001", mysql_error()));
        while ($row = mysql_fetch_array($rs)) {
            $opciones .= "<option value='" . $row['id'] . "'>" . $row['nombre'] . "</option>";
        }
        return $opciones;
    }

    function getFormMensajes() {
        global $conexion;
        global $database_conexion;
        mysql_select_db($database_conexion, $conexion);
        $formMensajes = "";
        $sql = sprintf("SELECT id, respuestaGanador, respuesta, respuestaErrorCodigo, respuestaErrorOpcion, respuestaFueraHorario, respuestaDentroHorario, respuestaMotivacional, respuestaBloqueoCodigo, respuestaBloqueoClave, respuestaBlacklist, respuestaRecarga, respuestaAdicional, respuestaBloqueoNumero FROM ac_recargas WHERE id=%s;", GetSQLValueString($_POST['sltRecarga'], "int"));
        $rs = mysql_query($sql, $conexion) or die(register_mysql_error("RA0002", mysql_error()));
        if (mysql_num_rows($rs) > 0) {
            $row = mysql_fetch_array($rs);
            $formMensajes .= "
      <form method='POST'>
       <input type='hidden' name='idRecarga' value='" . $row['id'] . "'/>
       <table>
        <tr>
         <td>Respuesta:</td>
         <td><textarea rows='6' name='msjRespuesta'>" . $row['respuesta'] . "</textarea></td>
        </tr><tr>
         <td>Respuesta Ganador:</td>
         <td><textarea rows='6' name='msjRespuestaGanador'>" . $row['respuestaGanador'] . "</textarea></td>
        </tr><tr>
         <td>Respuesta Error en Código:</td>
         <td><textarea rows='6' name='msjCodigoError'>" . $row['respuestaErrorCodigo'] . "</textarea></td>
        </tr><tr>
         <td>Respuesta Error en Opción:</td>
         <td><textarea rows='6' name='msjOpcionError'>" . $row['respuestaErrorOpcion'] . "</textarea></td>
        </tr><tr>
         <td>Respuesta Fuera de Horario:</td>
         <td><textarea rows='6' name='msjFueraHorario'>" . $row['respuestaFueraHorario'] . "</textarea></td>
        </tr><tr>
         <td>Respuesta Dentro del Horario:</td>
         <td><textarea rows='6' name='msjDentroHorario'>" . $row['respuestaDentroHorario'] . "</textarea></td>
        </tr><tr>
         <td>Respuesta de Código Bloqueado:</td>
         <td><textarea rows='6' name='msjBloqueoCodigo'>" . $row['respuestaBloqueoCodigo'] . "</textarea></td>
        </tr><tr>
         <td>Respuesta de Número Bloqueado:</td>
         <td><textarea rows='6' name='msjBloqueoNumero'>" . $row['respuestaBloqueoNumero'] . "</textarea></td>
        </tr><tr>
         <td>Respuesta de Clave Bloqueada:</td>
         <td><textarea rows='6' name='msjBloqueoClave'>" . $row['respuestaBloqueoClave'] . "</textarea></td>
        </tr><tr>
         <td>Respuesta Blacklist:</td>
         <td><textarea rows='6' name='msjBlacklist'>" . $row['respuestaBlacklist'] . "</textarea></td>
        </tr><tr>
         <td>Respuesta de Recarga:</td>
         <td><textarea rows='6' name='msjRecarga'>" . $row['respuestaRecarga'] . "</textarea></td>
        </tr><tr>
         <td>Respuesta Adicional:</td>
         <td><textarea rows='6' name='msjAdicional'>" . $row['respuestaAdicional'] . "</textarea></td>
        </tr><tr>
         <td>Respuesta Motivacional:</td>
         <td><textarea rows='6' name='msjMotivacional'>" . $row['respuestaMotivacional'] . "</textarea></td>
        </tr>
        <tr><td colspan = '2' style='text-align:right'><input type='submit' class='button' value = 'Guardar'></td></tr>
       </table>
       </form>";
        }
        return $formMensajes;
    }

    function guardarMensajesRecargas() {
        global $conexion;
        global $database_conexion;
        mysql_select_db($database_conexion, $conexion);
        $sql = sprintf("UPDATE ac_recargas SET respuesta = %s, respuestaGanador = %s, respuestaDentroHorario = %s, respuestaFueraHorario  = %s, respuestaErrorCodigo = %s, respuestaBloqueoCodigo = %s, respuestaBlacklist = %s, respuestaRecarga = %s, respuestaAdicional = %s, respuestaErrorOpcion = %s, respuestaMotivacional = %s, respuestaBloqueoClave = %s, respuestaBloqueoNumero = %s WHERE id = %s", GetSQLValueString((($_POST['msjRespuesta'] != NULL) ? $_POST['msjRespuesta'] : ""), "text"), GetSQLValueString((($_POST['msjRespuestaGanador'] != NULL) ? $_POST['msjRespuestaGanador'] : ""), "text"), GetSQLValueString((($_POST['msjDentroHorario'] != NULL) ? $_POST['msjDentroHorario'] : ""), "text"), GetSQLValueString((($_POST['msjFueraHorario'] != NULL) ? $_POST['msjFueraHorario'] : ""), "text"), GetSQLValueString((($_POST['msjCodigoError'] != NULL) ? $_POST['msjCodigoError'] : ""), "text"), GetSQLValueString((($_POST['msjBloqueoCodigo'] != NULL) ? $_POST['msjBloqueoCodigo'] : ""), "text"), GetSQLValueString((($_POST['msjBlacklist'] != NULL) ? $_POST['msjBlacklist'] : ""), "text"), GetSQLValueString((($_POST['msjRecarga'] != NULL) ? $_POST['msjRecarga'] : ""), "text"), GetSQLValueString((($_POST['msjAdicional'] != NULL) ? $_POST['msjAdicional'] : ""), "text"), GetSQLValueString((($_POST['msjOpcionError'] != NULL) ? $_POST['msjOpcionError'] : ""), "text"), GetSQLValueString((($_POST['msjMotivacional'] != NULL) ? $_POST['msjMotivacional'] : ""), "text"), GetSQLValueString((($_POST['msjBloqueoClave'] != NULL) ? $_POST['msjBloqueoClave'] : ""), "text"), GetSQLValueString((($_POST['msjBloqueoNumero'] != NULL) ? $_POST['msjBloqueoNumero'] : ""), "text"), GetSQLValueString($_POST['idRecarga'], "int")); //echo $sql;
        mysql_query($sql, $conexion) or die(register_mysql_error("RA0003", mysql_error()));
    }

    function getHtml() {
        $contenido = ( isset($_POST['sltRecarga'])) ? $this->getFormMensajes() : "";
        $opciones = $this->getListaRecargas();
        $html = file_get_contents($this->template);
        $html = str_replace("@@TITLE@@", "Promocionales - Mantenimiento", $html);
        $html = str_replace("@@OPCIONES@@", $opciones, $html);
        $html = str_replace("@@CONTENT1@@", $contenido, $html);
        return $html;
    }

}

$r = new admin();
?>
