<?php
ini_set("display_errors", 1);
class consumer {  
    function processOutMsg(){
        global $_CONF;

        $recordCount = 0;
        do {
            $sql = "SELECT id, numero, numero_salida, REPLACE(REPLACE(mensaje, 'ñ', 'n'), 'í', 'i') AS mensaje, fecha_salida, tipo_servicio FROM mensajes_pendientes WHERE numero_salida='0000' AND fecha_salida<NOW() AND estado=0 ORDER BY fecha_salida, RAND() LIMIT 1;";
            $rs  = mysql_query($sql) or die(mysql_error());
            $recordCount = mysql_num_rows($rs);
            
            if ($row = mysql_fetch_array($rs)){
                $query1 = sprintf("UPDATE mensajes_pendientes SET estado=2, fecha_envio=NOW(), confirmado=0 WHERE id=%s", $row['id']);
                mysql_query($query1) or die(mysql_error());
                $mensaje = urlencode($row['mensaje']);
                $url = sprintf($_CONF['atp_url'], $_CONF['atp_username'], $_CONF['atp_password'], $row['numero'], $mensaje, $row['tipo_servicio']);

                $resource = fopen($url, "rb"); 
                $response = fread($resource, 8192);
                fclose($resource);
                $query2 = sprintf("UPDATE mensajes_pendientes SET estado=1, confirmado=confirmado+1, respuesta_esme='%s', fecha_confirmacion=NOW() WHERE id=%s", $response, $row['id']);
                mysql_query($query2) or die(mysql_error());
            }
        } while ($recordCount > 0);
    }
}