<?php

session_start();

require_once('../connections/conexion.php');
require_once('../functions/functions.php');
require_once('../functions/db.php');
require_once('functions.php');
require_once('../conf.php');

class admin {

    private $idSuscripcion;

    function admin() {
        $this->idSuscripcion = (isset($_POST['id'])) ? $_POST['id'] : -1;
        if (!isset($_SESSION['idAdmin'])) {
            header("Location: " . initPage());
            exit();
        } else if (!permission(43)) {
            header("Location: " . initPage());
            exit();
        }
    }

    function guardarVariables() {
        global $conexion;
        $cont = 1;

        if (isset($_POST['actualizar']) && isset($_POST['id']) && (isset($_POST['variable1']) || isset($_POST['variable2']) || isset($_POST['variable3']) || isset($_POST['variable4']) || isset($_POST['variable5']))) {

            $aplicar = 0;
            $variable = array(1 => "''", 2 => "''", 3 => "''", 4 => "''", 5 => "''");
            $pregunta = array(1 => "''", 2 => "''", 3 => "''", 4 => "''", 5 => "''");
            $preguntaOpcion = array(1 => "-", 2 => "-", 3 => "-", 4 => "-", 5 => "-");
            $letras = array(1 => "a", 2 => "b", 3 => "c", 4 => "d", 5 => "e", 6 => "f", 7 => "g", 8 => "h", 9 => "i", 10 => "j");

            foreach ($_POST as $key => $value) {
                if (substr($key, 0, 8) == "variable") {
                    $value = get_magic_quotes_gpc() ? stripslashes($value) : $value;
                    $value = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($value) : mysql_escape_string($value);
                    $value = "'" . $value . "'";
                    $variable[substr($key, 8, 1)] = $value;
                    if ($value != "''"){
                        $aplicar = 1;
                    }
                } else if (substr($key, 0, 8) == "pregunta") {
                    $value = get_magic_quotes_gpc() ? stripslashes($value) : $value;
                    $value = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($value) : mysql_escape_string($value);
                    $value = "'" . $value . "'";
                    $pregunta[substr($key, 8, 1)] = ($variable[substr($key, 8, 1)] == "''") ? "''" : $value;
                } else if (substr($key, 0, 9) == "txtClaves" && strlen($value) > 0) {
                    $preguntaOpcion[substr($key, 9, 1)] = explode(",", $value);
                }
            }

            $cont = 1;
            $sql = sprintf("UPDATE suscripciones SET variable1=%s, variable2=%s, variable3=%s, variable4=%s, variable5=%s, aplicarVariables=%s WHERE id=%s;", $variable[1], $variable[2], $variable[3], $variable[4], $variable[5], $aplicar, GetSQLValueString($this->idSuscripcion, "int"));
            mysql_query($sql, $conexion) or die(register_mysql_error("SCV001", mysql_error()));

            while ($cont <= 5) {
                $sql = sprintf("REPLACE INTO suscripciones_preguntas (idSuscripcion, preguntas, variable, tipo) VALUES (%s, %s, %s, 0);", GetSQLValueString($this->idSuscripcion, "int"), (($variable[$cont] != "''") ? $pregunta[$cont] : "''"), $cont);
                mysql_query($sql, $conexion) or die(register_mysql_error("SCV002", mysql_error()));

                if (count($preguntaOpcion[$cont]) > 0 && $preguntaOpcion[$cont][0] != "-") {
                    $sql = sprintf("DELETE FROM suscripciones_preguntas_opciones WHERE idSuscripcion=%s AND variable=%s", GetSQLValueString($this->idSuscripcion, "int"), GetSQLValueString($cont, "int"));
                    mysql_query($sql, $conexion) or die(register_mysql_error("SCV002", mysql_error()));

                    if ($pregunta[$cont] != "''") {
                        $sql = sprintf("UPDATE suscripciones_preguntas SET tipo = 1 WHERE idSuscripcion=%s AND variable=%s", GetSQLValueString($this->idSuscripcion, "int"), GetSQLValueString($cont, "int"));
                        mysql_query($sql, $conexion) or die(register_mysql_error("SCV003", mysql_error()));

                        for ($i = 0; $i < count($preguntaOpcion[$cont]); $i++) {
                            $sql = sprintf("INSERT INTO suscripciones_preguntas_opciones (idSuscripcion, variable, letra, valor) VALUES (%s, %s, %s, %s);", GetSQLValueString($this->idSuscripcion, "int"), GetSQLValueString($cont, "int"), GetSQLValueString($letras[$i + 1], "text"), GetSQLValueString($preguntaOpcion[$cont][$i], "text"));
                            mysql_query($sql, $conexion) or die(register_mysql_error("SCV004", mysql_error()));
                        }
                    }
                } else {
                    $sql = sprintf("DELETE FROM suscripciones_preguntas_opciones WHERE idSuscripcion=%s AND variable=%s", GetSQLValueString($this->idSuscripcion, "int"), GetSQLValueString($cont, "int"));
                    mysql_query($sql, $conexion) or die(register_mysql_error("SCV005", mysql_error()));
                    $sql = sprintf("UPDATE suscripciones_preguntas SET tipo=0 WHERE idSuscripcion=%s AND variable=%s", GetSQLValueString($this->idSuscripcion, "int"), GetSQLValueString($cont, "int"));
                    mysql_query($sql, $conexion) or die(register_mysql_error("SCV006", mysql_error()));
                }
                $cont ++;
            }
        }
    }

    //obtenemos las opciones del select
    function getOpciones() {
        global $conexion;

        $opciones = "";
        $rs = mysql_query("SELECT id, nombre FROM suscripciones WHERE activa=1 ORDER BY nombre;", $conexion) or die(register_mysql_error("SCL0003", mysql_error()));

        $opciones .= (isset($_POST['id']) && $_POST['id'] != 'no') ? '' : '<option value="no">Seleccione la suscripci&oacute;n</option>';
        while ($row = mysql_fetch_array($rs)) {
            $selected = ($this->idSuscripcion == $row['id']) ? "selected" : "";
            $opciones .= sprintf('<option value="%s" %s>%s</option>', $row['id'], $selected, $row['nombre']);
        }
        return $opciones;
    }

    function getVariables() {
        global $conexion;

        $cont = 0;
        $opciones = "";
        $opcion = "";
        $opcionArmada = "";

        $query = sprintf("SELECT s.variable1, s.variable2, s.variable3, s.variable4, s.variable5, sp.preguntas, sp.variable, sp.tipo FROM suscripciones s LEFT OUTER JOIN  suscripciones_preguntas sp ON s.id=sp.idSuscripcion WHERE s.id=%s;", GetSQLValueString($this->idSuscripcion, "int"));
        $rs = mysql_query($query, $conexion) or die(register_mysql_error("SCL0003", mysql_error()));

        if (mysql_num_rows($rs) > 0) {
            while ($row = mysql_fetch_array($rs)) {
                $opciones .= sprintf('<tr><td style="text-align:right">Variable %s:</td><td colspan="2"><input type="text" value="%s" name="variable%s" maxlength="32"/></td></tr>
     <tr><td colspan="2">&nbsp;</td></tr>', ($cont + 1), $row[$cont], ($cont + 1));
                $opciones .= sprintf('<tr><td style="text-align: right">Pregunta %s:</td><td colspan="2"><input type="text" value="%s" name="pregunta%s"/></td></tr>', ($cont + 1), (($row[6] == $cont + 1) ? $row[5] : ""), ($cont + 1));
                $opciones .= sprintf('<tr><td style="text-align: right">Tipo:</td><td colspan="2">
       Abierta:<input @@abierta@@ type="radio" name="rpregunta%s" value="abierta%s" id="abierta%s" onchange="habilitarLista(this.id,%s)" style="width:20px;"/>
       Cerrada:<input @@cerrada@@ type="radio" name="rpregunta%s" value="cerrada%s" id="cerrada%s" onchange="habilitarLista(this.id,%s)" style="width:20px;"/>
       </td></tr>
      <tr>
       <td>&nbsp;</td>
       <td>
        <div id="divCerrada%s" @@estilo@@>
         <select name="listOpciones" id="listOpciones%s" size="5">
          @@opciones@@
         </select>
        </div>
       </td><td>
        <div id="divCerradab%s" @@estilo@@>
         <input type="button" class="small-button" value="+" id="btnMas%s" onclick="agregarOpcion(%s)"/><br/><br/>
         <input type="button" class="small-button" value="-" id="btnMenos%s" onclick="eliminarOpcion(%s)"/>
         <input type="hidden" value="@@claves@@" id="txtOpciones%s" name="txtClaves%s" />
        </div>
       </td>
      </tr>
      <tr><td colspan="2">&nbsp;</td></tr>', ($cont + 1), ($cont + 1), ($cont + 1), ($cont + 1), ($cont + 1), ($cont + 1), ($cont + 1), ($cont + 1), ($cont + 1), ($cont + 1), ($cont + 1), ($cont + 1), ($cont + 1), ($cont + 1), ($cont + 1), ($cont + 1), ($cont + 1));

                if ($row[7] == 1) {
                    $opciones = str_replace("@@cerrada@@", "checked='checked'", $opciones);
                    $opciones = str_replace("@@abierta@@", "", $opciones);
                    $opciones = str_replace("@@estilo@@", "", $opciones);

                    $queryOpciones = sprintf("SELECT valor FROM suscripciones_preguntas_opciones WHERE idSuscripcion=%s AND variable=%s;", $this->idSuscripcion, $row[6]);
                    $rsOpciones = mysql_query($queryOpciones, $conexion);

                    while ($rowOpciones = mysql_fetch_array($rsOpciones)) {
                        $opcion .= "<option>" . $rowOpciones[0] . "</option>";
                        $opcionArmada .= $rowOpciones[0] . ",";
                    }
                    $opciones = str_replace("@@claves@@", substr($opcionArmada, 0, -1), $opciones);
                    $opcionArmada = "";
                } else {
                    $opciones = str_replace("@@abierta@@", "checked='checked'", $opciones);
                    $opciones = str_replace("@@cerrada@@", "", $opciones);
                    $opciones = str_replace("@@estilo@@", "style=\"display:none\"", $opciones);
                    $opciones = str_replace("@@claves@@", "", $opciones);
                }
                $opciones = str_replace("@@opciones@@", $opcion, $opciones);
                $opcion = "";
                $cont++;
            }
        }
        return $opciones;
    }

    function getJavascript() {
        global $_CONF;
        return '
    <script type="text/javascript">
     var maxOpciones =' . $_CONF['opc_max_count'] . '
     var contador = 0;
     function habilitarLista(idOpcion, num){
      var opcion = document.getElementById(idOpcion);
      var div = document.getElementById("divCerrada"+ num);
      var div2 = document.getElementById("divCerradab"+ num);

      if(opcion.value == "abierta"+num){ 
       div.style.display="none" 
       div2.style.display="none" 
       document.getElementById("txtOpciones"+num).value = "";
      } else if(opcion.value == "cerrada"+num) {
       div.style.display="" 
       div2.style.display="" 
       armarOpciones(num);
      }
     }

     function armarOpciones(num){
      var opciones = document.getElementById("listOpciones"+num); 
      var claves="";
      for (var i = 0; i < opciones.length; i++) {
       var opt = opciones[i];
       claves += opt.value + ",";
      }
      document.getElementById("txtOpciones"+num).value = claves.slice(0,-1);
     }

     function agregarOpcion(num){
      var claves = document.getElementById("listOpciones"+num);
      var clave = prompt("Ingrese la clave");
   
      if(clave != null && contador < maxOpciones && clave.length > 0){
       var opcion = new Option(clave,clave);
       claves.options[claves.options.length] = opcion;
       armarOpciones(num);
       contador = contador +1;
      }
     }
	 
     function eliminarOpcion(num){
      var claves = document.getElementById("listOpciones"+num);
      if(claves.selectedIndex != -1){
       claves.options[claves.selectedIndex] = null;
       armarOpciones(num);
      } else {
       alert("Seleccione la clave que desea eliminar.");
      }
     }
    </script>';
    }

    function getPage() {
        $html = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
     <head>
      <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
      <title>Suscripciones - Variables</title>
      <link rel="stylesheet" type="text/css" href="../css/style.css" />' . $this->getJavascript() . '
     </head>
     <body>
      <form  id="formactualizar" name="formactualizar" method="post">
       <table width="260px" cellpadding="0" cellspacing="0">
        <tr><th colspan="3">Configuraci&oacute;n de Variables</th></tr>
        <tr><td colspan="3">&nbsp;</td></tr>
        <tr>
         <td>Suscripci&oacute;n:</td>
         <td colspan="2"><select name="id" id="id" onChange="document.formactualizar.submit()">@@OPCIONES@@</select></td>
        </tr>
        <tr><td colspan="3">&nbsp;</td></tr>
		@@variables@@
		<tr><th colspan="3" style="text-align: center"><a href="menu.php">Menu</a></th></tr>
       </table>
      </form>
     </body>
    </html>';

        if ($this->idSuscripcion != -1 && isset($_POST['id']) && $_POST['id'] != "no") {
            $variables = '<tr><th colspan="3">Variables</th></tr><tr><td colspan="3">&nbsp;</td></tr>';
            $variables .= $this->getVariables() . '<tr>
      <td colspan="3" style="text-align: right">
       <input name="actualizar" type="submit" class="button" value="Guardar" onClick="return validar();"/>
      </td>
     </tr><tr>
      <td colspan="3">&nbsp;</td>
     </tr>';
            $html = str_replace("@@variables@@", $variables, $html);
        } else {
            $html = str_replace("@@variables@@", "", $html);
        }

        $html = str_replace("@@OPCIONES@@", $this->getOpciones(), $html);
        return $html;
    }

}

$a = new admin();
$a->guardarVariables();
echo $a->getPage();
?>