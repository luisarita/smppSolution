<?php

session_start();

ini_set("display_errors", 1);

require_once('../connections/conexion.php');
require_once('../functions/functions.php');
require_once('../functions/db.php');
require_once('functions.php');

class admin {

    private $template = '46.usuarios.administracion.html';

    function admin() {
        if (!isset($_SESSION['idAdmin'])) {
            header("Location: " . initPage());
            exit();
        } else if (!permission(46)) {
            header("Location: " . initPage());
            exit();
        }
        if (isset($_POST['txtUser']) && isset($_POST['txtName']) && isset($_POST['txtPass']) && isset($_POST['idUsuarioEdit'])) {
            $this->editarUsuario();
        } else if (isset($_POST['txtNewUser']) && isset($_POST['txtNewName']) && isset($_POST['txtNewPass'])) {
            $this->crearUsuario();
        } else if (isset($_POST['idEstadoA'])) {
            $this->actualizarUsuario();
        }
        echo $this->printHTML();
    }

    //function para recuperar usuarios

    function getUsuarios() {
        global $conexion;
        global $database_conexion;
        mysql_select_db($database_conexion, $conexion);
        $sql = sprintf("SELECT md5(id), usuario, nombre_completo, estado FROM accesos_usuarios");
        $rs = mysql_query($sql, $conexion) or die(register_mysql_error("UA0001", mysql_error()));
        $htmlContenido = '<table cellspacing="0" style="width: 100%">
    <tr>
     <th colspan="4">Lista de Usuarios</th>
    </tr>
    <tr>
     <td style="text-align: left" class="content">Usuario</td>
     <td style="text-align: left" class="content">Nombre completo</td>
     <td style="text-align: center" class="content">Estado</td>
     <td class="content">&nbsp;</td>
    </tr>
    <tr>
     <td colspan="4">&nbsp;</td>
    </tr>';

        while ($row = mysql_fetch_array($rs)) {
            $htmlContenido .= '
    <tr>
     <td style="text-align:left">' . $row[1] . '</td>
     <td style="text-align:left">' . $row[2] . '</td>
     <td style="text-align:center">
      <form action="" name="activador' . $row[0] . '" method="post">
       <input type="hidden" name="idEstadoA" value="' . $row[0] . '@' . $row[3] . '"/>
       <input style="width:30px" name="idEstado" type="checkbox" onChange="document.activador' . $row[0] . '.submit()" ' . (($row[3] == 1) ? ' checked' : '') . '/>
      </form>
     </td>
     <td style="text-align:center">
      <form action="" name="editar' . $row[0] . '" method="POST">
       <input type="hidden" name="idRecordatorioEdit" value="' . $row[0] . '"/>
       <input class = "button" type = "button" value = "Editar" onClick="document.editar' . $row[0] . '.submit()"/>
      </form>
     </td>
    </tr>';
        }
        $htmlContenido .= '<tr><td colspan="4">&nbsp;</td></tr>@@USUARIO@@</table>';
        return $htmlContenido;
    }

    function getEditarCrearU() {
        global $conexion;
        global $database_conexion;
        $htmlContenido = '
   <tr>
    <th colspan="4">' . ((isset($_POST['idRecordatorioEdit'])) ? "Editar Usuario" : "Nuevo Usuario" ) . '</th>
   </tr>
   <tr>
    <td colspan="4">&nbsp;</td>
   </tr><tr>
    <td colspan="4" align="center">
     <form style="border: solid 1px #fff;" action="" name="guardarUsuario" method="POST">
      <table style="margin:auto;">';

        if (isset($_POST['idRecordatorioEdit'])) {
            mysql_select_db($database_conexion, $conexion);
            $sql = sprintf("SELECT md5(id), usuario,  nombre_completo, estado FROM accesos_usuarios WHERE md5(id) = %s Limit 1", GetSQLValueString($_POST['idRecordatorioEdit'], "text"));
            $rs = mysql_query($sql, $conexion) or die(register_mysql_error("UA0002", mysql_error()));
            $row = mysql_fetch_array($rs);
            $htmlContenido .= '
    <tr>
     <td style="text-align:right">
      <input type="hidden" name="idUsuarioEdit" value="' . $row[0] . '"/>
      Usuario:
     </td><td>
      <input type="text" name="txtUser" id="txtUser" value="' . $row[1] . '">
     </td>
    </tr><tr>
     <td style="text-align:right">Nombre:</td>
     <td><input type="text" name="txtName" id="txtName" value="' . $row[2] . '"></td>
    </tr><tr>
     <td style="text-align:right">Contrase&ntilde;a:</td>
     <td><input type="password" name="txtPass" id="txtPass" value=""></td>
    </tr><tr>
     <td style="text-align:right">Activo: </td>
     <td style="text-align:left">
      <input style="width:30px" name="idEstadoEdit" type="checkbox" ' . (($row[3] == 1) ? ' checked' : '') . '/>
     </td>
    </tr><tr>
     <td style="text-align:right" colspan="2">
      <input class = "button" type = "button" value = "Guardar" onClick="if(validarFormEdit())document.guardarUsuario.submit()"/>
     </td>
    </tr>';
        } else {
            $htmlContenido .= '
    <tr>
     <td style="text-align:right">Usuario:</td>
     <td><input type="text" name="txtNewUser" id="txtUser" value=""></td>
    </tr><tr>
     <td style="text-align:right">Nombre:</td>
     <td><input type="text" name="txtNewName" id ="txtName" value=""></td>
    </tr><tr>
     <td style="text-align:right">Contrase&ntilde;a:</td>
     <td><input type="password" name="txtNewPass" id="txtPass" value=""></td>
    </tr><tr>
     <td style="text-align:right" colspan="2">
      <input class = "button" type = "button" value = "Crear usuario" onClick="if(validarForm())document.guardarUsuario.submit()"/>
     </td>
    </tr>';
        }
        return $htmlContenido . "</table>
      </form>
     </td>
    </tr>
    <tr><th colspan='4' style='text-align: center'><a href='menu.php'>Menu</a></th></tr>   
    ";
    }

    function actualizarUsuario() {
        $datos = explode("@", $_POST['idEstadoA']);
        global $conexion;
        global $database_conexion;
        mysql_select_db($database_conexion, $conexion);
        $sql = sprintf("UPDATE accesos_usuarios SET estado=%s WHERE md5(id)=%s;", ( ( $datos[1] == 1 ) ? 0 : 1), GetSQLValueString($datos[0], "text"));
        mysql_query($sql, $conexion) or die(register_mysql_error("UA0004", mysql_error()));
        mysql_query("COMMIT");
    }

    function editarUsuario() {
        global $conexion;
        global $database_conexion;
        mysql_select_db($database_conexion, $conexion);
        $sql = sprintf("UPDATE accesos_usuarios SET usuario = %s, nombre_completo = %s, clave = %s, estado = %s WHERE md5(id) = %s", GetSQLValueString($_POST['txtUser'], "text"), GetSQLValueString($_POST['txtName'], "text"), ((strlen(trim($_POST['txtPass'])) > 0) ? GetSQLValueString(md5($_POST['txtPass']), "text") : "clave"), ((isset($_POST['idEstadoEdit'])) ? 1 : 0), GetSQLValueString($_POST['idUsuarioEdit'], "text"));
        mysql_query($sql, $conexion) or die(register_mysql_error("UA0003", mysql_error()));
        mysql_query("COMMIT");
    }

    function crearUsuario() {
        global $conexion;
        global $database_conexion;
        mysql_select_db($database_conexion, $conexion);
        $sql = sprintf("INSERT INTO accesos_usuarios VALUES(null, %s, md5(%s), %s, 1)", GetSQLValueString($_POST['txtNewUser'], "text"), GetSQLValueString($_POST['txtNewPass'], "text"), GetSQLValueString($_POST['txtNewName'], "text"));
        mysql_query($sql, $conexion) or die(register_mysql_error("UA0004", mysql_error()));
        mysql_query("COMMIT");
    }

    function printHTML() {
        $html = file_get_contents($this->template);
        $html = str_replace("@@TITLE@@", 'Administraci&oacute;n de usuarios', $html);
        $html = str_replace("@@CONTENT1@@", $this->getUsuarios(), $html);
        $html = str_replace("@@USUARIO@@", $this->getEditarCrearU(), $html);
        return $html;
    }

}

;

$r = new admin();
?>
