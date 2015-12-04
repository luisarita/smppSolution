<?php

class recarga {
    private $templateLogin     = "templates/login.html";
    private $templateHTML      = "recargas/html.html";
    private $templateBlackList = "recargas/blacklist.html";

    private $title             = "Recargas";
    private $sessionVar        = "idRecarga";
    private $sp_blacklist_add  = "sp_recargas_blacklist_agregar";
    private $sp_blacklist_del  = "sp_recargas_blacklist_eliminar";
    private $url               = "";
    private $pagina            = 0; 
    private $desde;
    private $hasta;
    private $estado            = 4;

    private $idRifa            = 0;

    function recarga    (){
     session_start();
     if (!isset($_SESSION[$this->sessionVar])){
         if (!$this->doLogin()) $this->printLogin();
     }
     $this->constructor();
    }
    function constructor(){
     global $conexion;
     global $database_conexion;

     $this->pagina = ( isset($_GET['pagina'])) ? $_GET['pagina'] : 0;
     mysql_select_db($database_conexion, $conexion);

     $SQL         = sprintf("SELECT idRifa FROM ac_recargas WHERE id=%s;", GetSQLValueString($_SESSION[$this->sessionVar], "int"));
     $rs          = mysql_query( $SQL, $conexion ) or die(register_mysql_error("SS001", mysql_error()));
     $row         = mysql_fetch_array($rs);

     $this->idRifa   = $row['idRifa'];

     $this->desde     = (isset($_POST['desde' ])) ? $_POST['desde' ] : (isset($_GET['desde' ]) ? $_GET['desde' ] : (($this->desde) ? $this->desde : strftime( "%Y-%m-%d %H:00", time() - (strftime("%H", time()) * 60 * 60 )))); 
     $this->hasta     = (isset($_POST['hasta' ])) ? $_POST['hasta' ] : (isset($_GET['hasta' ]) ? $_GET['hasta' ] : strftime( "%Y-%m-%d %H:59", time() + 3600));

     $this->url       = $_SERVER['PHP_SELF'];
    }
 
    function doLogin (){
       global $conexion, $database_conexion;

       $this->doLogout();
       session_start();	 

       if (!isset($_POST['username']) || !isset($_POST['password'])) return false;
       $usuario = (get_magic_quotes_gpc()) ? $_POST['username'] : addslashes($_POST['username']);
       $clave = (get_magic_quotes_gpc()) ? $_POST['password'] : addslashes($_POST['password']);

       mysql_select_db($database_conexion, $conexion);
       $sql = sprintf("SELECT R.id, U.terminaciones, U.admin FROM ac_recargas R, ac_recargas_usuario U WHERE R.id=U.idRecarga AND R.activa=1 AND U.usuario='%s' AND U.clave= MD5('%s');", $usuario, $clave);
       $rs  = mysql_query($sql, $conexion) or die(register_mysql_error("ARL001", mysql_error()));
       $row = mysql_fetch_assoc($rs);

       if ( mysql_num_rows($rs) == 0 ) return false;
       $_SESSION['su']              = $row['admin'];
       $_SESSION[$this->sessionVar] = $row['id'];
       $_SESSION['terminaciones']   = $row['terminaciones'];
       $_SESSION['username'] = $_POST['username'];
       header("Location: " . $_SERVER['PHP_SELF']);
       exit();
    }  
    function doLogOut(){
     unset($_SESSION['su']);
     unset($_SESSION[$this->sessionVar]);
     unset($_SESSION['terminaciones']);
     session_destroy();
    }

    function action(){
     $action = isset($_POST['MM_ACTION']) ? $_POST['MM_ACTION'] : (isset($_GET['MM_ACTION']) ? $_GET['MM_ACTION'] : "");
     switch ($action){
      case "logout":
       $this->doLogout();
       $this->printLogin();
       break;
      case "busqueda":
       $this->printBusqueda();
       break;
      case "blacklist":
       $this->printBlacklist();
       break;
      case "agregarBlackList":
       $this->addBlacklist();
       break;
      case "delBlackList":
       $this->delBlackList();
       break;
      case "manejar":
       $this->printManejar();
       break;
      case "marcar":
       $this->marcar();
       break;
      case "imagen":
       $this->printImagen();
       break;
      default:
       $this->printDetail();
       break;
     }
    }

    function marcar      (){	 
     global $conexion;
     global $database_conexion;

     mysql_select_db($database_conexion, $conexion);
     $sql = sprintf("CALL sp_recargas_marcar(%s, %s);", $_SESSION[$this->sessionVar], $_GET['id']);
     mysql_query($sql, $conexion) or die (register_mysql_error("ARBL01", mysql_error())); 

     header("Location: ?MM_ACTION=manejar");
     exit();
    }
    function addBlackList(){
     global $conexion;
     global $database_conexion;

     mysql_select_db($database_conexion, $conexion);
     $sql = sprintf("CALL %s(%s, %s);", $this->sp_blacklist_add, $_SESSION[$this->sessionVar], $_POST['numero']);
     mysql_query($sql, $conexion) or die (register_mysql_error("ARM001", mysql_error())); 

     header("Location: ?MM_ACTION=blacklist");
     exit();
    }
    function delBlackList(){
     global $conexion;
     global $database_conexion;

     mysql_select_db($database_conexion, $conexion);
     $sql = sprintf("CALL %s(%s, %s);", $this->sp_blacklist_del, $_SESSION[$this->sessionVar], $_GET['numero']);
     mysql_query($sql, $conexion) or die (register_mysql_error("ARM001", mysql_error())); 

     header("Location: ?MM_ACTION=blacklist");
     exit();
    }

    function printBlackListItems (){
     global $conexion;
     global $database_conexion;

     mysql_select_db($database_conexion, $conexion);
     $sql = sprintf("SELECT numero, fecha FROM ac_recargas_blacklist WHERE idRecarga=%s AND estado=1 ORDER BY fecha;", $_SESSION[$this->sessionVar]);

     $rs  = mysql_query($sql, $conexion) or die (register_mysql_error("ARBLL01", mysql_error())); 

     $detail = ""; $i = 1;
     while ($row = mysql_fetch_array($rs)){
      $button  = sprintf("<input type='button' onClick='javascript: eliminar(%s);' value='Eliminar' class='msg-button' />", $row['numero']);
      $detail .= "<tr><td colspan='4'>&nbsp;</td></tr>";
      $detail .= sprintf("<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>", $i, $row['numero'], $row['fecha'], $button);
      ++$i;
     }

     return $detail;
    }
    function printBlacklist      (){
     global $_CONF;

     if (!isset($_SESSION['su']) || $_SESSION['su'] != 1) $this->printManejar();	 

     $html = file_get_contents($this->templateBlackList);
     $html = str_replace("@@TITLE@@"         , $this->title, $html);
     $html = str_replace("@@LONGITUD@@"      , $_CONF['num_length'], $html);
     $html = str_replace("@@LISTADO@@"       , $this->printBlackListItems(), $html);

     echo $html;
     exit();
    }
    function printBusqueda       (){
     global $conexion;
     global $database_conexion;

     mysql_select_db($database_conexion, $conexion);
     $sql = sprintf("SELECT COUNT(*) AS conteo FROM ac_recargas_participantes WHERE idRecarga=%s AND estado=1;", $_SESSION[$this->sessionVar]); 

     $rs  = mysql_query($sql, $conexion) or die (register_mysql_error("ARB001", mysql_error())); 
     $row = mysql_fetch_array($rs);
     if ($row["conteo"] > 0) {
      $sound = "<bgsound src='../snd/alarm1.wav' loop='1'>";
     }
     $html = "
      <!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
       <html xmlns='http://www.w3.org/1999/xhtml'>
        <head>
         <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
         <meta http-equiv='Refresh' content='60;?MM_ACTION=busqueda'>
        </head>
        <body>";
     if (isset($sound)) $html .= $sound;
     $html .= "
        </body>
      </html>";
     echo $html;
     exit();
    }
    function printParticipantes  (){
     global $conexion;
     global $database_conexion;

     mysql_select_db($database_conexion, $conexion);
     $sql = sprintf("SELECT p.id, p.numero, p.mensaje, p.fecha_mensaje, pr.descripcion AS pregunta, o.descripcion AS opcion, p.estado FROM ac_recargas_participantes p LEFT OUTER JOIN ac_recargas_preguntas pr ON pr.id=p.idPregunta LEFT OUTER JOIN ac_recargas_opciones o ON p.idOpcion=o.id WHERE p.idRecarga=%s AND p.estado=%s AND SUBSTRING(p.numero,LENGTH(p.numero),1) IN (%s);", $_SESSION[$this->sessionVar], $this->estado, $_SESSION['terminaciones']);

     $rs  = mysql_query($sql, $conexion) or die (mysql_error()); //(register_mysql_error("ARB001", mysql_error())); 

     $detail = ""; $i = 1;
     while ($row = mysql_fetch_array($rs)){
      $button  = ($row['estado'] == 4) ? sprintf("<input type='button' onClick='javascript: marcar(%s);' value='Marcar' class='msg-button' />", $row['id']) : "";
      $detail .= "<tr><td colspan='5'>&nbsp;</td></tr>";
      $detail .= sprintf("<tr style=''><td style='vertical-align: middle'>%s</td><td style='vertical-align: middle'>%s</td><td style='vertical-align: middle'>%s</td><td style='vertical-align: middle'>%s</td><td style='text-align: right'>%s</td></tr>", $i, $row['numero'], $row['mensaje'], $row['fecha_mensaje'], $button);
      if (isset($_SESSION['su']) && $_SESSION['su'] == 1){
       $detail .= sprintf("<tr style=''><td style='vertical-align: middle'>&nbsp;</td><td style='vertical-align: middle' colspan='2'>%s - <b>%s</b></td><td>&nbsp;</td></tr>", $row['pregunta'], $row['opcion']);    
      }
      ++$i;
     }

     return $detail;
    }
    function printManejar        (){
     global $_CONF;

     if (isset($_CONF['skin']) && $_CONF['skin'] != ""){
         $this->templateHTML = "skins/" . $_CONF['skin'] . "/recargas.html";
     }
     $html = file_get_contents($this->templateHTML);
     $html = str_replace("@@TITLE@@"         , $this->title,$html);
     $html = str_replace("@@PARTICIPANTES@@" , $this->printParticipantes(),$html);
     if (isset($_CONF['skin']) && $_CONF['skin'] != "") $html = str_replace("@@SKIN@@", $_CONF['skin'], $html);


     echo $html;
    }
    function printImagen              (){
        global $conexion;
        $rs = mysql_query(sprintf("SELECT logo_archivo, logo_tipo FROM rifas WHERE id=%s;", GetSQLValueString($this->idRifa,"int")), $conexion) or die(register_mysql_error("SI001", mysql_error()));
        $row    = mysql_fetch_assoc($rs);
        $data   = $row[ "logo_archivo" ];
        $type   = $row[ "logo_tipo" ];
        header( "Content-type: $type");
        echo $data;
    }
    function printLogin          (){
        global $_CONF;
        if (isset($_CONF['skin']) && $_CONF['skin'] != "") $this->templateLogin = "skins/" . $_CONF['skin'] . "/login.html";

        $html = file_get_contents($this->templateLogin);
        $html = str_replace("@@TITLE@@",$this->title,$html);
        echo $html;
        exit();
    }
    function printDetail         (){
     $html = "
      <!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Frameset//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd\">
      <html xmlns='http://www.w3.org/1999/xhtml'>
       <head>
        <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
        <title>Recargas</title>
       </head>
       <frameset cols='0,*' frameborder='no' border='0' framespacing='0'>
        <frame src='?MM_ACTION=busqueda' name='leftFrame' scrolling='No' noresize='noresize' id='leftFrame' title='leftFrame' />
        <frame src='?MM_ACTION=manejar' name='mainFrame' id='mainFrame' title='mainFrame' />
       </frameset>
       <noframes>
        <body>
        </body>
       </noframes>
      </html>";
     echo $html;
    }
}