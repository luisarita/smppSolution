<?php

 ini_set("display_errors", 1);
 
 class recordatorio {
  private $templateLogin    = "templates/login.html";
  private $templateHTML     = "recordatorios/html.html";
  
  private $title      = "Recordatorios";
  private $table      = "recordatorios";
  private $sessionVar = "idRecordatorio";
  
  private $variables = array( 1 => '', 2 => '', 3 => '', 4 => '', 5 => '');
  
  function recordatorio($id = -1, $redir = true){
   if (!isset($_SESSION)) session_start();
   if (!isset($_SESSION[$this->sessionVar]))
    if (!$this->doLogin($id, $redir)){
     if ($redir)
      $this->printLogin();
    } else {
     $this->constructor();
   } else {
    $this->constructor();
   }
  }
  function constructor          (){ //constructor 
   global $conexion;
   global $database_conexion;
 
   $this->idRecordatorio = $_SESSION[$this->sessionVar];
   $this->pagina = ( isset($_GET['pagina'])) ? $_GET['pagina'] : 0;
   mysql_select_db($database_conexion, $conexion);
  }
  
  function doLogin ($id, $redir){
   global $conexion;
   global $database_conexion;
  
   $this->doLogout();
   session_start();
  
   if (!isset($_POST['username']) || !isset($_POST['password'])) return false;
   $usuario = (get_magic_quotes_gpc()) ? $_POST['username'] : addslashes($_POST['username']);
   $clave = (get_magic_quotes_gpc()) ? $_POST['password'] : addslashes($_POST['password']);
  
   mysql_select_db($database_conexion, $conexion);
   $sql = sprintf("SELECT id FROM %s WHERE (estado=1 AND usuario='%s' AND clave='%s') AND (id=%s OR -1=%s)", $this->table, $usuario, $clave, $id, $id);
   $rs  = mysql_query($sql, $conexion) or die(register_mysql_error("EL001", mysql_error())); 
   $row = mysql_fetch_assoc($rs);

   if ( mysql_num_rows($rs) == 0 ) return false;
   $_SESSION[$this->sessionVar] = $row['id'];
   $_SESSION['username'] = $_POST['username'];
   if ($redir){
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
   } else {
    return true;
   }
  }
  function doLogOut(){
   unset($_SESSION['su']);
   unset($_SESSION[$this->sessionVar]);
   session_destroy();
  }

  function action(){
   $action = isset($_POST['MM_ACTION']) ? $_POST['MM_ACTION'] : (isset($_GET['MM_ACTION']) ? $_GET['MM_ACTION'] : "");
   switch ($action){
    case "logout":
     $this->doLogout();
     $this->printLogin();
     break;
    case "salvarEvento";
     $this->salvarEvento();
     $this->printHTML();
     break;
    case "cambiarEstadoEvento":
     $this->cambiarEstadoEvento();
     $this->printHTML();
     break;
    case "editarEvento":
     $this->printHTML();
     break;
    /*if(isset($_POST['id_estado'])){
     $this->actualizarEvento();
    }
    if(isset($_POST['mensaje']) && $_SESSION['accion'] == 'crear'  ){
     $this->crearEvento();
    } else if(isset($_POST['mensaje']) && $_SESSION['accion'] == 'editar'  ){
     $this->editarEvento();
    }*/
    default:
     $this->printHTML();
     break;
   }
  }
  
  function printLogin (){
   global $_CONF;
   if (isset($_CONF['skin']) && $_CONF['skin'] != "") $this->templateLogin = "skins/" . $_CONF['skin'] . "/login.html";
  
   $html = file_get_contents($this->templateLogin);
   $html = str_replace("@@TITLE@@",$this->title,$html);
   echo $html;
   exit();
  }
  function printHTML  (){
   global $conexion;
   global $_CONF;

   if (isset($_CONF['skin']) && $_CONF['skin'] != "") $this->templateHTML = "skins/" . $_CONF['skin'] . "/recordatorios.html";
   $html = file_get_contents($this->templateHTML); 
   $html = str_replace("@@TITLE@@", $this->title,$html); 
   $html = str_replace("@@CONTENT1@@", $this->getContenido(), $html);
   $html = $this->getEvento($html);
   if (isset($_CONF['skin']) && $_CONF['skin'] != "") $html = str_replace("@@SKIN@@", $_CONF['skin'], $html);
   echo $html;
  }
 
  function salvarEvento         (){ //funcion que recibe la llamada cuando se creara un nuevo evento
   $mensaje = $_POST['mensaje'];

   if(isset($_POST['numeroP']) && $_POST['enviarA'] == 'uno'){
    $idParticipante = $this->getIdParticipante( $_POST['numeroP']);
   } else {
    $idParticipante = "NULL";
   }
 
   if($_POST['repeticion']=='porHora'){
    $this->insertarEvento($idParticipante, $_POST['minuto'], 'NULL', 'NULL', 'NULL', 'NULL', $mensaje, $_POST['id']);
   } else if ($_POST['repeticion']=='porDia'){
    $this->insertarEvento($idParticipante, $_POST['minuto1'], $_POST['hora'], 'NULL', 'NULL', 'NULL', $mensaje, $_POST['id']);
   } else if ($_POST['repeticion']=='porSemana'){
    $this->insertarEvento($idParticipante, $_POST['minuto2'], $_POST['hora1'], 'NULL', 'NULL', $_POST['dia'], $mensaje, $_POST['id']);    
   } else if ($_POST['repeticion']=='porMes'){
    $this->insertarEvento($idParticipante, $_POST['minuto3'], $_POST['hora2'], $_POST['dia1'], 'NULL', 'NULL', $mensaje, $_POST['id']);
   }
  }

  function insertarEvento       ($idPartcipante, $minuto, $hora, $dia_mes, $mes, $dia, $mensaje, $id){ //insertar recordatorio
   global $conexion;
   
   if ($id == -1){
    $sql = sprintf("INSERT INTO recordatorios_eventos (idParticipante, idRecordatorios, minuto, hora, dia, mes, dia_semana, estado, mensaje, activo) VALUES (%s, %s, %s, %s, %s, %s, %s, 0, %s, 1)",
     $idPartcipante,
     $_SESSION[$this->sessionVar],
     GetSQLValueString($minuto,  "int"),
     GetSQLValueString($hora,    "int"),
     GetSQLValueString($dia_mes, "int"),
     GetSQLValueString($mes,     "int"),
     GetSQLValueString($dia,     "int"),
     GetSQLValueString($mensaje, "text"));
   } else {
    $sql = sprintf("UPDATE recordatorios_eventos SET idParticipante=%s, minuto=%s, hora=%s, dia=%s, mes=%s, dia_semana=%s, mensaje=%s WHERE id=%s AND idRecordatorios=%s;",
     $idPartcipante,
     GetSQLValueString($minuto,  "int"),
     GetSQLValueString($hora,    "int"),
     GetSQLValueString($dia_mes, "int"),
     GetSQLValueString($mes,     "int"),
     GetSQLValueString($dia,     "int"),
     GetSQLValueString($mensaje, "text"),
     GetSQLValueString($id, "int"),
     $_SESSION[$this->sessionVar]);
   }
   mysql_query($sql, $conexion) or die(register_mysql_error("CX0001", mysql_error())); 
  }
  function cambiarEstadoEvento  (){ //funcion que actualiza un estado de un evento de activo a inactivo y viceversa
   global $conexion;
   global $database_conexion;
 
   $query = sprintf("UPDATE recordatorios_eventos SET activo=(activo+1)%%2 WHERE id=%s;", GetSQLValueString($_POST['id'], "int"));
   mysql_query($query, $conexion) or die(register_mysql_error("SS001", mysql_error()));
  }
  function getIdParticipante    ($numero){ //verifica si el participante existe y si no existe lo crea; devolvera el id con el numero de telefono asociado
   global $conexion;
   
   $sql = sprintf("SELECT id FROM recordatorios_participantes WHERE numero=%s AND idRecordatorio=%s;", GetSQLValueString($numero, 'text'), $_SESSION[$this->sessionVar]);
   $rs = mysql_query($sql, $conexion) or die(register_mysql_error("VP001", mysql_error()));
 
   if($row = mysql_fetch_array($rs)){
    return $row['id'];
   } else {
    $sql = sprintf("INSERT INTO recordatorios_participantes (id, idRecordatorio, numero, estado, variable1, variable2, variable3) VALUES (NULL, %s, %s, 1, '', '', '')", GetSQLValueString($_SESSION[$this->sessionVar], "int"), GetSQLValueString($numero, 'text'));

    mysql_query($sql, $conexion) or die(register_mysql_error("VP002", mysql_error()));
    $rs = mysql_query("SELECT LAST_INSERT_ID()",$conexion) or die(register_mysql_error("VP003", mysql_error()));
    $row = mysql_fetch_array($rs);   
    return $row[0];
   }
  }
  function getDestinatario      ($id){ //funcion que retorna el numero del destinatario si este existe, caso contrario retorna null
   global $conexion;
   global $database_conexion;
   $query = sprintf('SELECT numero FROM recordatorios_participantes WHERE id=%s;',$id);
   $rs = mysql_query($query, $conexion) or die(register_mysql_error("CX0002", mysql_error()));
  
   if( $row = mysql_fetch_array($rs)){
    return $row['numero'];
   } else {
    return null;
   }
  } 
  
  function getContenido         (){ //funcion que genera el contenido que muestra lo eventos para un recordatorio especifico
   global $conexion;
   global $database_conexion;
   
   $registros = '';
     
   $query = sprintf("SELECT mensaje, id, idParticipante, activo FROM recordatorios_eventos WHERE estado=0 AND idRecordatorios=%s OR (idRecordatorios IS NULL AND idParticipante IN (SELECT id FROM recordatorios_participantes WHERE idRecordatorio = %s)) AND activo=1 ORDER BY id DESC;",$_SESSION['idRecordatorio'], $_SESSION['idRecordatorio']);
   $rs = mysql_query($query,$conexion) or die(register_mysql_error("CX0003",mysql_error()));
  
   while($row = mysql_fetch_array($rs)){   
    $participante = ( $row['idParticipante'] != '' ) ? $this->getDestinatario($row['idParticipante']) : 'Todos'; 
    $activo = ($row['activo'] == 1) ? 'checked' : '';
    $registros .= sprintf('<tr>
      <td>%s</td>
      <td style="text-align:center">%s</td>
      <td style="text-align: center">
       <form action="" name="" method="post">
        <input type="hidden" name="id" value="%s"/>
        <input type="hidden" name="MM_ACTION" value="cambiarEstadoEvento" />
        <input style="width:30px" name="estado" type="checkbox" onChange="this.form.submit()" %s />
       </form>
      </td>
      <td style="text-align: right">
       <form action="" name="" method="post">
        <input type="hidden" name="id" value="%s"/>
        <input type="hidden" name="MM_ACTION" value="editarEvento" />
        <input class = "button" type="button" value="Editar" onClick="this.form.submit()"/>
       </form>
      </td>
     </tr>', 
     $row['mensaje'], $participante, $row['id'], $activo, $row['id']);
    }   
   
   return $registros;
  }  
  function getEvento            ($html){ //funcion que devuelve el HTML del evento sin datos en los campos, este form es el que se utiliza cuando se creara un nuevo evento

   if (isset($_POST['id'])){
    global $conexion;
    global $database_conexion;
    
    $query = sprintf("SELECT id, idParticipante, mensaje, minuto, hora, dia, mes, dia_semana FROM recordatorios_eventos WHERE id=%s;", GetSQLValueString($_POST['id'], "int"));
    $rs = mysql_query($query,$conexion) or die(register_mysql_error("CX0004", mysql_error()));  
    $row = mysql_fetch_array($rs);
    
    $id         = $row['id'];
    $mensaje    = $row['mensaje'];
    $minuto     = $row['minuto'];
    $hora       = $row['hora'];
    $dia        = $row['dia'];
    $dia_semana = $row['dia_semana'];
    $seleccion = 1;
    if (is_numeric($row['hora'])) ++$seleccion;
    if (is_numeric($row['dia_semana'])) ++$seleccion;
    if (is_numeric($row['dia'])) $seleccion += 2;
    
    $onLoad  = sprintf("activaDesactiva(document.forms[0].repeticion%s.id);", $seleccion);
   } else {
    $id = -1; $mensaje = "";
    $minuto  = 0; $hora = 0; $dia_semana = 0; $dia = 0;
    $onLoad  = "";
   }
   
   $nombres_dias = array( 0 => 'Lunes', 1 => 'Martes', 2 => 'Miercoles', 3 => 'Jueves', 4 => 'Viernes', 5 => 'Sabado', 6 => 'Domingo');
   $minutos = '';  $horas = ''; $dias = ''; $dias_mes = ''; 
   for( $i = 0; $i < 60; ++$i ){  
    $selected = ($minuto == $i) ? "selected" : "";
    $minutos .= sprintf("<option value='%s' %s>%s</option>", str_pad($i, 2, "0", STR_PAD_LEFT), $selected, str_pad($i, 2, "0", STR_PAD_LEFT));
   }
   for( $i = 0; $i < 24; ++$i ){  
    $selected = ($hora == $i) ? "selected" : "";
    $horas .= sprintf("<option value='%s' %s>%s</option>", str_pad($i, 2, "0", STR_PAD_LEFT), $selected, str_pad($i, 2, "0", STR_PAD_LEFT));
   }
   for( $i = 0; $i < 7; ++$i ){  
    $selected = ($dia_semana == $i) ? "selected" : "";
    $dias .= sprintf("<option value='%s' %s>%s</option>", str_pad($i, 2, "0", STR_PAD_LEFT), $selected, substr($nombres_dias[$i],0,2));
   }
   for( $i = 1; $i <= 31; ++$i ){  
    $selected = ($dia == $i) ? "selected" : "";
    $dias_mes .= sprintf("<option value='%s' %s>%s</option>", str_pad($i, 2, "0", STR_PAD_LEFT), $selected, str_pad($i, 2, "0", STR_PAD_LEFT));
   }
   $html = str_replace("@@MENSAJE@@",  $mensaje,  $html);
   $html = str_replace("@@MINUTOS@@",  $minutos,  $html);
   $html = str_replace("@@HORAS@@",    $horas,    $html);
   $html = str_replace("@@DIAS@@",     $dias,     $html);
   $html = str_replace("@@DIAS_MES@@", $dias_mes, $html);
   $html = str_replace("@@ONLOAD@@",   $onLoad,   $html);
   $html = str_replace("@@ID@@",       $id,   $html);
   return $html;
  }
  
  function printButtonVariables (){ 
   $html = "";
   if ($this->aplicarVariables == 1){
    foreach($this->variables as $key => $value){
     if ($value != "") $html .= sprintf("<tr><td><input type='button' class='button' value='%s' onclick='javascript: agregarVariable(\"contenido\", \"@@variable%s@@\")'></td></tr>", $value, $key);
    }
    $html = sprintf("<table cellpadding='1' cellspacing='0'>%s</table>", $html);
   }
   echo $html;
  }  
 }

?>