<?php

require_once('functions.php');
require_once('../connections/conexion.php');
require_once('../functions/db.php');

require_once('../conf.php');
require_once('functions.php');

class admin {

    private $templateHTML = "";
    private $title = "Auditoria";
    private $tipos = array(1 => 'Rifas', 2 => 'Encuestas', 3 => 'Trivias', 4 => 'Suscripciones', 5 => 'Telechats', 6 => 'Diccionarios', 7 => 'Listados', 8 => 'Media', 9 => 'Chats', 10 => 'Telebingos',);

    function admin() {

        $arrStr = explode("/", $_SERVER['SCRIPT_NAME']);
        $arrStr = array_reverse($arrStr);
        $arrStr = explode(".", $arrStr[0]);
        $this->codigo = $arrStr[0];

        session_start();
        if (!isset($_SESSION['idAdmin'])) {
            header("Location: " . initPage());
            exit();
        } else if (!permission($this->codigo)) {
            header("Location: " . initPage());
            exit();
        }
    }

    function getQueryNames($tipo) {
        if ($tipo == 1) {
            $query = "SELECT  id, nombre FROM rifas ORDER BY nombre";
        } else if ($tipo == 2) {
            $query = "SELECT id, nombre FROM encuestas ORDER BY nombre";
        } else if ($tipo == 3) {
            $query = "SELECT  id, nombre FROM trivias ORDER BY nombre";
        } else if ($tipo == 4) {
            $query = "SELECT  id, nombre FROM suscripciones ORDER BY nombre";
        } else if ($tipo == 5) {
            $query = "SELECT  id, nombre FROM telechats ORDER BY nombre";
        } else if ($tipo == 6) {
            $query = "SELECT id, nombre FROM diccionarios ORDER BY nombre";
        } else if ($tipo == 7) {
            $query = "SELECT  id, nombre FROM listados ORDER BY nombre";
        } else if ($tipo == 8) {
            $query = "SELECT  id, nombre FROM ws_media ORDER BY nombre";
        } else if ($tipo == 9) {
            $query = "SELECT  id, nombre FROM chats ORDER BY nombre";
        } else if ($tipo == 10) {
            $query = "SELECT  id, nombre FROM telebingos ORDER BY nombre";
        }
        return $query;
    }

    function getQueryResults($tipo, $actividad) {
        if ($tipo == 1) {
            $query = sprintf("SELECT usuario, fecha FROM auditoria.mantenimiento_actualizaciones WHERE idTabla1=%s AND idTabla2 IS NULL AND idTabla3 IS NULL AND tabla='rifas' ORDER BY id DESC", $actividad);
        } else if ($tipo == 2) {
            $query = sprintf("SELECT usuario, fecha FROM auditoria.mantenimiento_actualizaciones WHERE idTabla1=%s AND idTabla2 IS NULL AND idTabla3 IS NULL AND tabla='encuestas' ORDER BY id DESC", $actividad);
        } else if ($tipo == 3) {
            $query = sprintf("SELECT usuario, fecha FROM auditoria.mantenimiento_actualizaciones WHERE idTabla1=%s AND idTabla2 IS NULL AND idTabla3 IS NULL AND tabla='trivias' ORDER BY id DESC", $actividad);
        } else if ($tipo == 4) {
            $query = sprintf("SELECT usuario, fecha FROM auditoria.mantenimiento_actualizaciones WHERE idTabla1=%s AND idTabla2 IS NULL AND idTabla3 IS NULL AND tabla='suscripciones' ORDER BY id DESC", $actividad);
        } else if ($tipo == 5) {
            $query = sprintf("SELECT usuario, fecha FROM auditoria.mantenimiento_actualizaciones WHERE idTabla1=%s AND idTabla2 IS NULL AND idTabla3 IS NULL AND tabla='telechats' ORDER BY id DESC", $actividad);
        } else if ($tipo == 6) {
            $query = sprintf("SELECT usuario, fecha FROM auditoria.mantenimiento_actualizaciones WHERE idTabla1=%s AND idTabla2 IS NULL AND idTabla3 IS NULL AND tabla='diccionarios' ORDER BY id DESC", $actividad);
        } else if ($tipo == 7) {
            $query = sprintf("SELECT usuario, fecha FROM auditoria.mantenimiento_actualizaciones WHERE idTabla1=%s AND idTabla2 IS NULL AND idTabla3 IS NULL AND tabla='listados' ORDER BY id DESC", $actividad);
        } else if ($tipo == 8) {
            $query = sprintf("SELECT usuario, fecha FROM auditoria.mantenimiento_actualizaciones WHERE idTabla1=%s AND idTabla2 IS NULL AND idTabla3 IS NULL AND tabla='media' ORDER BY id DESC", $actividad);
        } else if ($tipo == 9) {
            $query = sprintf("SELECT usuario, fecha FROM auditoria.mantenimiento_actualizaciones WHERE idTabla1=%s AND idTabla2 IS NULL AND idTabla3 IS NULL AND tabla='chats' ORDER BY id DESC", $actividad);
        } else if ($tipo == 10) {
            $query = sprintf("SELECT usuario, fecha FROM auditoria.mantenimiento_actualizaciones WHERE idTabla1=%s AND idTabla2 IS NULL AND idTabla3 IS NULL AND tabla='telebingos' ORDER BY id DESC", $actividad);
        }
        return $query;
    }

    function getContent() {
        global $_CONF;
        global $conexion;

        $tipo = (isset($_POST['tipo'])) ? intval($_POST['tipo']) : '1';
        $actividad = (isset($_POST['actividad'])) ? intval($_POST['actividad']) : -1;

        $html = "
    <form action='' id='formactualizar' name='formactualizar' method='post'>
    <table cellpadding='0' cellspacing='0' width='100%'>
     <tr>
      <td>Tabla:</td>
      <td>
       <select name='tipo' id='tipo' onChange='this.form.actividad.selectedIndex = 0; this.form.submit()'>";

        foreach ($this->tipos as $key => $value) {
            $html .= sprintf("<option value='%s' %s>%s</option>", $key, ($key == $tipo) ? "selected" : "", $value);
        }

        $html .= "
       </select>
      </td>
     </tr>
     <tr><td colspan='2'>&nbsp;</td></tr>
     <tr>
      <td>Actividad:</td>
      <td>
       <select name='actividad' id='actividad' onChange='this.form.submit()'>
        <option value='-1'>Favor seleccione una actividad</option>";

        $actividad_info = mysql_query($this->getQueryNames($tipo), $conexion) or die(register_mysql_error("AU0001", mysql_error()));
        while ($row_respuestas = mysql_fetch_assoc($actividad_info)) {
            $html .= sprintf("<option value='%s' %s>%s</option>", $row_respuestas['id'], ($row_respuestas['id'] == $actividad) ? "selected" : "", $row_respuestas['nombre']);
        }

        $html .= "</select>
       </td>
      </tr>
      <tr><td colspan='2'>&nbsp;</td></tr>
     </table>
    </form>";

        $html .= $this->getResult($tipo, $actividad);
        return $html;
    }

    function getResult($tipo, $actividad) {
        global $_CONF;
        global $conexion;

        $html = "";
        if ($actividad != '-1') {
            $html = "<table cellpadding='0' cellspacing='0' align='center' width='100%'>";

            $rs = mysql_query($this->getQueryResults($tipo, $actividad), $conexion) or die(register_mysql_error("AU0002", mysql_error()));
            if (mysql_num_rows($rs) > 0) {
                $html .= "<tr><th align='center' class='subtitulo'>Usuario</th><th align='center' class='subtitulo'>Fecha</th></tr>";
                while ($row = mysql_fetch_array($rs)) {
                    $usuario = preg_split("/@/", $row[0], 0);
                    $html .= "<tr><td>" . $usuario[0] . "</td><td>" . $row[1] . "</td></tr>";
                }
            } else {
                $html .= "<tr><th colspan='2' class='subtitulo'>No hay registros disponibles para esta actividad.</th></tr>";
            }
            $html .= "</table>";
        }
        return $html;
    }

    function printHTML() {
        global $_CONF;

        if (isset($_CONF['skin']) && $_CONF['skin'] != "")
            $this->templateHTML = "../skins/" . $_CONF['skin'] . "/admin/" . $this->codigo . ".html";
        $html = file_get_contents($this->templateHTML);
        $html = str_replace("@@CONTENT@@", $this->getContent(), $html);
        $html = str_replace("@@TITLE@@", $this->title, $html);
        if (isset($_CONF['skin']) && $_CONF['skin'] != "")
            $html = str_replace("@@SKIN@@", $_CONF['skin'], $html);
        return $html;
    }

}

$a = new admin();
echo $a->printHTML();
?>