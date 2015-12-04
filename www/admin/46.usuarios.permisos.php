<?php

session_start();

require_once('../connections/conexion.php');
require_once('../functions/functions.php');
require_once('../functions/db.php');
require_once('functions.php');

class admin {

    private $template = '46.usuarios.permisos.html';

    function admin() {
        if (!isset($_SESSION['idAdmin'])) {
            header("Location: " . initPage());
            exit();
        } else if (!permission(46)) {
            header("Location: " . initPage());
            exit();
        }

        if (isset($_POST['idUsuarioEdit'])) {
            echo $this->printHTML();
        } else {
            $this->guardarPermisos();
            echo $this->printHTML();
        }
    }

    function guardarPermisos() {
        global $conexion;
        global $database_conexion;
        $cont = 0;

        mysql_select_db($database_conexion, $conexion);
        foreach ($_POST as $variable => $valor) {
            $cont++;
            $data = explode("@", $variable);
            if (strlen(str_replace(" ", "", $valor)) > 0)
                $permisos = explode(",", $valor);
            else
                $permisos = null;
            $sql = sprintf("UPDATE accesos_permisos SET estado=0 WHERE idUsuario=%s AND tipoActividad=%s;", GetSQLValueString($data[2], "int"), GetSQLValueString($data[1], "int"));
            mysql_query($sql, $conexion) or die(register_mysql_error("UP0005", mysql_error()));
            for ($i = 0; $i < count($permisos); $i++) {
                if ($data[0] == 'txt') {
                    $sql1 = sprintf("REPLACE INTO accesos_permisos SET idUsuario=%s, tipoActividad=%s, idActividad=%s, estado=1;", GetSQLValueString($data[2], "int"), GetSQLValueString($data[1], "int"), GetSQLValueString($permisos[$i], "int"));
                    mysql_query($sql1, $conexion) or die(register_mysql_error("UP0006", mysql_error()));
                }
            }
        }
        if ($cont > 0)
            mysql_query("COMMIT");
    }

    function getUsuariosPermisos() {
        global $conexion;
        global $database_conexion;

        mysql_select_db($database_conexion, $conexion);
        $sql = sprintf("SELECT id, usuario, nombre_completo, estado FROM accesos_usuarios");
        $rs = mysql_query($sql, $conexion) or die(register_mysql_error("UP0001", mysql_error()));
        $htmlContenido = '<table cellspacing="0" style="width: 100%">
   <tr><th colspan="3">Lista de Usuarios</th></tr>
   <tr>
    <td style="text-align: left" class="content">Usuario</td>
    <td style="text-align: left" class="content">Nombre completo</td>
    <td class="content">&nbsp;</td>
   </tr><tr>
    <td colspan="3">&nbsp;</td>
   </tr>';

        while ($row = mysql_fetch_array($rs)) {
            $htmlContenido .= '
    <tr>
     <td style="text-align:left;">' . $row[1] . '</td>
     <td style="text-align:left;">' . $row[2] . '</td>
     <td style="text-align:center;">
      <form action="" name="editar' . $row[0] . '" method="POST">
       <input type="hidden" name="idUsuarioEdit" value="' . $row[0] . '"/>
       <input class = "button" type = "button" value = "Editar" onClick="document.editar' . $row[0] . '.submit()"/>
      </form>
     </td>
    </tr>';
        }

        $htmlContenido .= '
   <tr>
    <td colspan="3">&nbsp;</td>
   </tr>@@PERMISOS@@</table>';
        return $htmlContenido;
    }

    function cambiarPermisos() {
        global $conexion;
        global $database_conexion;

        mysql_select_db($database_conexion, $conexion);
        $sql = sprintf("SELECT nombre, tabla, id FROM accesos_actividades;");
        $rs = mysql_query($sql, $conexion) or die(register_mysql_error("UP0002", mysql_error()));
        $htmlPermisos = '<tr>
    <th colspan="3">Editar Permisos</th>
   </tr><tr>
    <td colspan="3">&nbsp;</td>
   </tr><tr>
    <td colspan=3><form method="POST" name="permisos"><table cellspacing=0 style="margin:auto">';
        while ($row = mysql_fetch_array($rs)) {
            $htmlPermisos .= '<tr>
     <td colspan="3"></td>
    </tr><tr>
     <th colspan=3>' . $row[0] . '</th>
    </tr><tr>
     <td class="content" style = "text-align:center">Disponibles</td><td class="content"></td><td class="content" style = "text-align:center">Permitidos</td>
    </tr>';
            $sql1 = sprintf("SELECT a.id, a.nombre  FROM %s a WHERE a.id NOT IN(SELECT ap.idActividad FROM accesos_actividades aa, accesos_permisos ap WHERE ap.estado = 1 AND ap.idUsuario = %s AND ap.tipoActividad=aa.id AND aa.tabla=%s);", $row[1], GetSQLValueString($_POST['idUsuarioEdit'], "int"), GetSQLValueString($row[1], "text"));
            $rs1 = mysql_query($sql1, $conexion) or die(register_mysql_error("UP0003", mysql_error()));
            $htmlPermisos .= "<tr>
    <td><select multiline size=5 id='sltQuitar" . $row[1] . "'>@@listActividades@@</select></td>
    <td><br><input onclick='agregarActividad(\"sltPermitir" . $row[1] . "\",\"sltQuitar" . $row[1] . "\",\"txtPermitidos_" . $row[0] . "\");' class='button'type='button' value='Agregar>>'/><br><br><input onclick='quitarActividad(\"sltPermitir" . $row[1] . "\",\"sltQuitar" . $row[1] . "\",\"txtPermitidos_" . $row[0] . "\")' class='button' type='button' value='<<Quitar'/></td>
    <td>
     <select multiline size=5 id = 'sltPermitir" . $row[1] . "'>@@listPermitidos@@</select>
     <input type='hidden'  id='txtPermitidos_" . $row[0] . "' name = 'txt@" . $row[2] . "@" . $_POST['idUsuarioEdit'] . "'/>
     <script>armarPermitidos('sltPermitir" . $row[1] . "','txtPermitidos_" . $row[0] . "')</script>
    </td></tr>";
            $listPermitidos = "";
            $listActividades = "";
            //listamos todas las actividades disponibles
            while ($row1 = mysql_fetch_array($rs1)) {
                $listActividades .= "<option value=" . $row1[0] . ">" . $row1[1] . "</option>";
            }

            //listamos las actividades de las que el usuario tiene permiso
            $sql1 = sprintf("SELECT ap.idActividad, a.nombre FROM accesos_actividades aa, accesos_permisos ap, %s a WHERE ap.idActividad = a.id AND ap.estado = 1 AND ap.idUsuario = %s AND ap.tipoActividad=aa.id AND aa.tabla = %s;", $row[1], GetSQLValueString($_POST['idUsuarioEdit'], "int"), GetSQLValueString($row[1], "text"));
            $rs1 = mysql_query($sql1, $conexion) or die(register_mysql_error("UP0004", mysql_error()));

            while ($row1 = mysql_fetch_array($rs1)) {
                $listPermitidos .= "<option value=" . $row1[0] . ">" . $row1[1] . "</option>";
            }

            $htmlPermisos = str_replace("@@listPermitidos@@", $listPermitidos, str_replace("@@listActividades@@", $listActividades, $htmlPermisos));
        }

        $htmlPermisos .= "<tr>
    <td colspan=3>&nbsp;</td>
   </tr><tr>
    <td colspan=3 style='text-align:center'><input type='button' class='msg-button' onclick='document.permisos.submit()' value='Guardar' name='guardarPermisos'/></td>
   </tr></table></form></td></tr>";
        return $htmlPermisos;
    }

    function printHTML() {
        $html = file_get_contents($this->template);
        $html = str_replace("@@TITLE@@", 'Permisos de usuario', $html);
        $html = str_replace("@@CONTENT1@@", $this->getUsuariosPermisos(), $html);
        $html = str_replace("@@PERMISOS@@", (isset($_POST['idUsuarioEdit'])) ? $this->cambiarPermisos() : "", $html);
        return $html;
    }

}

$r = new admin();
?>
