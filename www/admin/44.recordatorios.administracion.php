<?php

session_start();

ini_set("display_errors", 1);

require_once('../connections/conexion.php');
require_once('../functions/functions.php');
require_once('../functions/db.php');
require_once('functions.php');

class admin {

    private $template = '44.recordatorios.html.html';
    private $bandera = 0;

    function admin() {
        if (!isset($_SESSION['idAdmin'])) {
            header("Location: " . initPage());
            exit();
        } else if (!permission(44)) {
            header("Location: " . initPage());
            exit();
        }

        if (isset($_POST['idRecordatorioEdit'])) {
            $_SESSION['idRecordatorioEdit'] = $_POST['idRecordatorioEdit'];
            $this->bandera = 1;
        } else {
            $this->bandera = 0;
        }

        if (isset($_POST['id_estado'])) {
            $this->actualizarRecordatorio();
        } else if (isset($_POST['nombreCrear'])) {
            $this->crearRecordatorio();
        } else if (isset($_POST['nombreEditar'])) {
            $this->editarRecordatorio();
        }
        echo $this->printHTML();
    }

    function printHTML() {
        $html = file_get_contents($this->template);
        $html = str_replace("@@TITLE@@", 'Recordatorios', $html);
        $html = str_replace("@@CONTENT1@@", $this->getContenido(), $html);
        $html = str_replace("@@CONTENT2@@", '', $html);
        $html = str_replace("@@RECORDATORIO@@", (($this->bandera == 0) ? $this->getCrearRecordatorio() : $this->getEditarRecordatorio()), $html);
        return $html;
    }

    // "crearRecordatorio()" inserta el nuevo recordatorio en la base de datos
    function crearRecordatorio() {
        global $conexion;
        $imagen = mysql_escape_string(join(@file($_FILES['imagen']['tmp_name'])));

        $query = sprintf("INSERT INTO recordatorios (id, nombre, numero, estado, usuario, clave, claveAdmin, log_tipo, logo_archivo, mensaje_participante, mensaje_adicional) VALUES (NULL, %s, %s, 1, %s, %s, %s, %s, '@@IMAGEN@@', %s, %s)", GetSQLValueString($_POST['nombreCrear'], "text"), GetSQLValueString($_POST['numeroR'], "int"), GetSQLValueString($_POST['usuario'], "text"), GetSQLValueString($_POST['pass'], "text"), GetSQLValueString($_POST['passAdmin'], "text"), GetSQLValueString($_FILES['imagen']['type'], "text"), GetSQLValueString($_POST['msjParticipante'], "text"), GetSQLValueString($_POST['msjAdicional'], "text"));
        //insertamos el recordatorios
        $query = str_replace("@@IMAGEN@@", $imagen, $query);

        mysql_query("BEGIN");
        mysql_query($query, $conexion) or die(register_mysql_error("CX0001", mysql_error()));
        //recuperamos el ultimo id Creado
        $rs = mysql_query("SELECT LAST_INSERT_ID()", $conexion) or die(register_mysql_error("CX0001", mysql_error()));
        $row = mysql_fetch_array($rs);
        //separamos cada clave
        $claves = explode(",", $_POST['claves']);
        $query = "INSERT INTO claves (clave, idRecordatorio) VALUES(%s, %s)";
        //insertamos las claves
        for ($i = 0; $i < count($claves); $i++) {
            mysql_query(sprintf($query, GetSQLValueString($claves[$i], "text"), GetSQLValueString($row[0], "int")), $conexion) or die(register_mysql_error("CX0001", mysql_error()));
        }
        mysql_query("COMMIT");
    }

    function editarRecordatorio() {
        global $conexion;
        $query = "UPDATE recordatorios SET nombre=%s, numero=%s, usuario=%s, clave=%s, claveAdmin=%s, mensaje_participante=%s, mensaje_adicional=%s @@IMAGEN@@ WHERE recordatorios.id=%s;";

        //se arma el query para actualizar
        $query = sprintf($query, GetSQLValueString($_POST['nombreEditar'], "text"), GetSQLValueString($_POST['numeroR'], "int"), GetSQLValueString($_POST['usuario'], "text"), GetSQLValueString($_POST['pass'], "text"), GetSQLValueString($_POST['passAdmin'], "text"), GetSQLValueString($_POST['msjParticipante'], "text"), GetSQLValueString($_POST['msjAdicional'], "text"), GetSQLValueString($_SESSION['idRecordatorioEdit'], "int"));

        //se verifica si la imagen se cambiara
        if ($_FILES['imagen']['size'] > 0) {
            $imagen = mysql_escape_string(join(@file($_FILES['imagen']['tmp_name'])));
            $query = str_replace("@@IMAGEN@@", ", logo_archivo='" . $imagen . "'", $query);
        } else {
            $query = str_replace("@@IMAGEN@@", "", $query);
        }

        //actualizamos el registro
        mysql_query("BEGIN");
        mysql_query($query, $conexion) or die(register_mysql_error("CX0001", mysql_error()));

        //eliminamos las claves anteriores para este recordatorio
        $query = "DELETE FROM claves WHERE idRecordatorio = %s";
        $query = sprintf($query, GetSQLValueString($_SESSION['idRecordatorioEdit'], "int"));
        mysql_query($query, $conexion) or die(register_mysql_error("CX0001", mysql_error()));

        // reinsertamos las claves del recordatorio
        //separamos cada clave
        $claves = explode(",", $_POST['claves']);
        $query = "INSERT INTO claves (clave, idRecordatorio) VALUES(%s, %s);";

        //insertamos las claves
        for ($i = 0; $i < count($claves); $i++) {
            mysql_query(sprintf($query, GetSQLValueString($claves[$i], "text"), GetSQLValueString($_SESSION['idRecordatorioEdit'], "int")), $conexion) or die(register_mysql_error("CX0001", mysql_error()));
        }
        mysql_query("COMMIT");
        $_SESSION['idRecordatorioEdit'] = NULL;
    }

    function getNumeros($tipo) {
        global $conexion;
        $htmlOptions = '';
        $query = "SELECT numero FROM numeros";

        $rs = mysql_query($query, $conexion) or die(register_mysql_error("CX0001", mysql_error()));

        while ($row = mysql_fetch_array($rs)) {
            $htmlOptions .= '<option value="' . $row[0] . '"' . (($tipo == $row[0]) ? ' selected="selected"' : '') . '>' . $row[0] . '</option>';
        }
        return $htmlOptions;
    }

    function getContenido() {
        global $conexion;
        $htmlContenido = '
            <table cellspacing="0" style="width: 100%">
             <tr>
              <th colspan="5">Lista de Recordatorios</th>
             </tr><tr>
              <td class="content">Nombre</td>
              <td style="text-align: center" class="content">Numero</td>
              <td style="text-align: center" class="content">Estado</td>
              <td style="text-align: center" class="content">Logo</td>
              <td class="content">&nbsp;</td>
             </tr><tr>
              <td colspan="5">&nbsp;</td>
             </tr>';

        $query = "SELECT id, nombre, numero, estado FROM recordatorios";

        $rs = mysql_query($query, $conexion) or die(register_mysql_error("CX0001", mysql_error()));

        if (mysql_num_rows($rs) > 0) {
            while ($row = mysql_fetch_array($rs)) {
                $htmlContenido .= '
                <tr>
                    <td>' . $row[1] . '</td>
                    <td>' . $row[2] . '</td>
                    <td style="text-align: center">
                     <form action="" name="activador' . $row[0] . '" method="post">
                      <input type="hidden" name="id_estado" value="' . $row[0] . '@' . $row[3] . '"/>
                      <input style="width:30px" name="idEstado" type="checkbox" onChange="document.activador' . $row[0] . '.submit()" ' . (($row[3] == 1) ? ' checked' : '') . '/>
                     </form>
                    </td>
                    <td style="text-align: center"><img src="44.recordatorio.imagen.php?id=' . $row[0] . '" width="30" height = "25" alt="logo" /></td>
                    <td>
                     <form action="" name="editar' . $row[0] . '" method="POST">
                      <input type="hidden" name="idRecordatorioEdit" value="' . $row[0] . '"/>
                      <input class = "button" type = "button" value = "Editar" onClick="document.editar' . $row[0] . '.submit()"/>
                     </form>
                    </td>
                </tr>';
            }
        }
        $htmlContenido .= '<tr>
    <td colspan="5">&nbsp;</td>
   </tr>@@RECORDATORIO@@</table>';
        return $htmlContenido;
    }

    function getClaves() {
        global $conexion;
        $query = sprintf("SELECT clave FROM tecmovil.claves WHERE idRecordatorio = %s", GetSQLValueString($_SESSION['idRecordatorio'], "int"));
        $claves = '';
        $clavesList = "";
        $tags = '
   <table cellpadding="0" cellspacing="0">
    <tr>
	 <td rowspan="2">
	  <select size="5" id="listClaves" style="width:100px; vertical-align:text-top;">@@clavesList@@</select>
      <input type="hidden" id="txtClaves" name = "claves" value="@@claves@@"/>
	 </td><td>
	  <input type="button"  style=" width:25px;" value="+" onclick="agregarOpcion()" />
	 </td>
	</tr><td>
     <input type="button" style=" width:25px;" value="-" onclick="eliminarOpcion()" />
    </td></tr>
   </table>';

        $rs = mysql_query($query, $conexion) or die(register_mysql_error("CX0001", mysql_error()));

        while ($row = mysql_fetch_array($rs)) {
            $claves .= $row[0] . ",";
            $clavesList .= '<option value="' . $row[0] . '">' . $row[0] . '</option>';
        }
        $claves = substr($claves, 0, -1);
        return str_replace("@@claves@@", $claves, str_replace("@@clavesList@@", $clavesList, $tags));
    }

    function getEditarRecordatorio() {
        global $conexion;
        $query = sprintf("SELECT id, nombre, mensaje_participante, mensaje_adicional, numero, usuario, clave, claveAdmin FROM recordatorios WHERE id = %s", GetSQLValueString($_POST['idRecordatorioEdit'], "text"));
        $rs = mysql_query($query, $conexion) or die(register_mysql_error("CX0001", mysql_error()));
        $row = mysql_fetch_array($rs);
        $_SESSION['idRecordatorio'] = $row[0];

        $htmlRecordatorio = '
   <tr>
    <th colspan = "5">Editar Recordatorio</th>
   </tr><tr>
    <td>&nbsp;</td>
   </tr><tr>
    <td colspan = "5" class="centered">
     <center>
      <form action="" method="post" enctype="multipart/form-data" name="editarRecordatorio" id="editarRecordatorio" >
       <table>
        <tr>
         <td valign="top" style="text-align:right">Nombre:</td>
         <td valign="top" ><input type="text" name="nombreEditar" id="nombreEditar" value= "' . $row[1] . '"/></td>
         <td rowspan="3" valign="top" style="text-align:right">Mensaje Participante:</td>
         <td rowspan="3" valign="top" ><textarea name="msjParticipante" id="msjParticipante" cols="45" rows="4">' . $row[2] . '</textarea></td>
        </tr><tr>
         <td valign="top" style="text-align:right">Numero:</td>
         <td><select name="numeroR" id="numeroR">' . $this->getNumeros($row[4]) . '</select></td>
        </tr><tr>
         <td valign="top" style="text-align:right">Usuario:</td>
         <td valign="top" ><input type="text" name="usuario" id="usuario" value= "' . $row[5] . '"/></td>
        </tr><tr>
         <td valign="top" style="text-align:right">Contrase&ntilde;a:</td>
         <td valign="top" ><input type="password" name="pass" id="pass" value= "' . $row[6] . '"/></td>
         <td rowspan="3" valign="top" style="text-align:right">Mensaje Adicional:</td>
         <td rowspan="3" valign="top" ><textarea name="msjAdicional" id="msjAdicional" cols="45" rows="4">' . $row[3] . '</textarea></td>
        </tr><tr>
         <td valign="top" style="text-align:right">Contrase&ntilde;a Admin:</td>
         <td valign="top" ><input type="password" name="passAdmin" id="passAdmin" value="' . $row[7] . '"/></td>
        </tr><tr>
         <td valign="top" style="text-align:right">Claves:</td>
         <td valign="top" >' . $this->getClaves() . '</td>
        </tr><tr>
         <td valign="top" style="text-align:right">Logo:</td>
         <td valign="top"><input  type="file" name="imagen" id="imagen" /></td>
         <td valign="top" style="text-align:right" colspan = "2">
          <input class="button" type="button" name="cancelar" id="cancelar" value="Cancelar" onClick = "location.reload(true)" />&nbsp;
          <input class="button" type="button" name="guardar2" id="guardar" value="Guardar" onClick = "if (validarRecordatorioEditar()) { document.editarRecordatorio.submit() }" />
         </td>
        </tr>
       </table>
      </form>
     </center>
    </td>
   </tr><tr>
    <td colspan="5">&nbsp;</td>
   </tr>';

        return $htmlRecordatorio;
    }

    function getCrearRecordatorio() {
        $htmlRecordatorio = '
   <tr><th colspan="5">Crear Recordatorio</th></tr>
   <tr><td colspan="5">&nbsp;</td></tr>
   <tr>
    <td colspan = "5" class="centered">
     <center>
      <form action="" method="POST" enctype="multipart/form-data" name="crearRecordatorio" id="crearRecordatorio" >
       <table>
        <tr>
         <td valign="top" style="text-align:right">Nombre:</td>
         <td valign="top" ><input type="text" name="nombreCrear" id="nombreCrear" /></td>
         <td rowspan="3" valign="top" style="text-align:right">Mensaje participante:</td>
         <td rowspan="3" valign="top" ><textarea name="msjParticipante" id="msjParticipante" cols="45" rows="4"></textarea></td>
        </tr><tr>
         <td valign="top" style="text-align:right">Numero:</td>
         <td><select name="numeroR" id="numeroR">' . $this->getNumeros(-1) . '</select></td>
        </tr><tr>
         <td valign="top" style="text-align:right">Usuario:</td>
         <td valign="top" ><input type="text" name="usuario" id="usuario" /></td>
        </tr><tr>
         <td valign="top" style="text-align:right">Contrase&ntilde;a:</td>
         <td valign="top" ><input type="password" name="pass" id="pass" /></td>
         <td rowspan="3" valign="top" style="text-align:right">Mensaje adicional:</td>
         <td rowspan="3" valign="top" ><textarea name="msjAdicional" id="msjAdicional" cols="45" rows="4"></textarea></td>
        </tr><tr>
         <td valign="top" style="text-align:right">Contrase&ntilde;a Admin:</td>
         <td valign="top" ><input type="password" name="passAdmin" id="passAdmin" /></td>
        </tr><tr>
         <td valign="top" style="text-align:right">Claves:</td>
         <td valign="top" >
          <table cellpadding="0" cellspacing="0">
           <tr>
            <td rowspan="2">
             <select size="5" id="listClaves" style="width: 100px; vertical-align:text-top;"></select>
             <input type="hidden" id="txtClaves" name = "claves"/>
            </td><td>
             <input type="button" style=" width: 25px;" value="+" onclick="agregarOpcion()" />
		    </td>
           </tr><tr>
            <td><input type="button" style=" width: 25px;" value="-" onclick="eliminarOpcion()" /></td>
           </tr>
          </table>
         </td>
        </tr><tr>
         <td valign="top" style="text-align:right">Logo:</td>
         <td valign="top" colspan = "2"><input  type="file" name="imagen" id="imagen" /></td>
         <td valign="top" style="text-align:right">
          <input class="button" type="button" name="guardar2" id="guardar2" value="Guardar" onclick="if(validarRecordatorioCrear()){document.crearRecordatorio.submit()}" />
         </td>
        </tr>        
       </table>
      </form>
     </center>
    </td>
   </tr>
   <tr><td colspan="5">&nbsp;</td></tr>';

        return $htmlRecordatorio;
    }

    function actualizarRecordatorio() {
        global $conexion;

        $datos = explode("@", $_POST['id_estado']);

        $query = "UPDATE recordatorios SET estado=%s WHERE id=%s";

        if ($datos[1] == 0) {
            $query = sprintf($query, 1, $datos[0]);
        } else {
            $query = sprintf($query, 0, $datos[0]);
        }
        mysql_query($query, $conexion)or die(mysql_error()); //register_mysql_error("SS001", mysql_error()));
    }

}

;

$r = new admin();
$r->printHTML();
?>