<?php
require_once ('../connections/conexion.php');
require_once('functions.php');

ini_set("display_errors", 1);
session_start();
if (!isset($_SESSION['idAdmin'])) {
    header("Location: " . initPage());
    exit();
} else if (!permission(11)) {
    header("Location: " . initPage());
    exit();
}

$servicioSMPP = "SMPP Service";
$servicioMonitor = "SMPP Monitor";

if (isset($_POST["detenerSMPP"])) {
    stop($servicioSMPP);
} else if (isset($_POST["iniciarSMPP"])) {
    start($servicioSMPP);
} else if (isset($_POST["detenerMonitor"])) {
    stop($servicioMonitor);
} else if (isset($_POST["iniciarMonitor"])) {
    start($servicioMonitor);
} else if (isset($_POST["reiniciar"])) {
    start("RestartComputer");
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title></title>
        <link type="text/css" rel="stylesheet" href="../css/style.css"  />
    </head>
    <body>
        <form action="" id="formserv" name="formserv" method="post">
            <table cellpadding="1" cellspacing="1" width="220px">
                <tr><th>Servicio SMPP</th></tr>
                <tr><td>&nbsp;</td></tr>
                <tr><td style="text-align: center"><input name = 'detenerSMPP' type="submit" class="button" value="Detener Servicio" /></td></tr>
                <tr><td style="text-align: center"><input name = 'iniciarSMPP' type="submit" class="button" value="Iniciar Servicio" /></td></tr>
                <tr><td>&nbsp;</td></tr>
                <tr><td style="text-align: center">Buffer de Entrada: <b><?php $msg = readfile("http://localhost:8003/inmsg", "r");
echo substr($msg, 0, strlen($msg) - 1); ?></b></td></tr>
                <tr><td style="text-align: center">Buffer de Salida: <b><?php $msg = readfile("http://localhost:8003/outmsg", "r");
echo substr($msg, 0, strlen($msg) - 1); ?></b></td></tr>
                <tr><td style="text-align: center">Throttle: <b><?php $msg = readfile("http://localhost:8003/seet", "r");
echo substr($msg, 0, strlen($msg) - 1); ?></b></td></tr>
                <tr><td style="text-align: center">Estado Actual SMPP: <b><?php $msg = status($servicioSMPP);
echo $msg ?></b></td></tr>
                <tr><td>&nbsp;</td></tr>
                <tr><th>Servicio SMPP Monitor</th></tr>
                <tr><td>&nbsp;</td></tr>
                <tr><td style="text-align: center"><input name = 'detenerMonitor' type="submit" class="button" value="Detener Servicio" /></td></tr>
                <tr><td style="text-align: center"><input name = 'iniciarMonitor' type="submit" class="button" value="Iniciar Servicio" /></td></tr>
                <tr><td>&nbsp;</td></tr>
                <tr><td style="text-align: center">Estado Actual SMPP Monitor: <b><?php $msg = status($servicioMonitor);
echo $msg ?></b></td></tr>
                <tr><td>&nbsp;</td></tr>    
                <tr><th>Servidor</th></tr>
                <tr><td>&nbsp;</td></tr>
                <tr><td style="text-align: center"><input name = 'reiniciar' type="submit" class="button" value="Reiniciar Servidor" /></td></tr>


                <tr><td>&nbsp;</td></tr>    
                <tr><th style="text-align: center"><a href="11.servicios.php">Refrescar</a></th></tr>   
                <tr><th style="text-align: center"><a href="menu.php">Menu</a></th></tr>   
            </table>
        </form>
    </body>
</html><?php

function status($name) {
    if (function_exists('win32_query_service_status')) {
        $status = win32_query_service_status($name);
        if ($status["CurrentState"] == 1) {
            return "Stopped";
        } else if ($status["CurrentState"] == 2) {
            return "Starting";
        } else if ($status["CurrentState"] == 3) {
            return "Stopping";
        } else if ($status["CurrentState"] == 4) {
            return "Starting";
        } else if ($status["CurrentState"] == 5) {
            return "Waiting";
        }
    } else {
        $obj = new COM('winmgmts://localhost/root/CIMV2');
        $fso = new COM("Scripting.FileSystemObject");
        $services = $obj->ExecQuery(sprintf("SELECT * FROM Win32_Service WHERE DisplayName='%s'", $name));

        foreach ($services as $wmi_call) {
            $status["CurrentState"] = $wmi_call->State;
        }
    }

    return $status["CurrentState"];
}

function stop($name) {
    if (function_exists('win32_stop_service')) {
        win32_stop_service($name);
    } else {
        $obj = new COM('winmgmts://localhost/root/CIMV2');
        $fso = new COM("Scripting.FileSystemObject");
        $services = $obj->ExecQuery(sprintf("SELECT * FROM Win32_Service WHERE DisplayName='%s'", $name));

        foreach ($services as $wmi_call) {
            $status["CurrentState"] = $wmi_call->StopService();
        }
    }
}

function start($name) {
    if (function_exists('win32_start_service')) {
        win32_start_service($name);
    } else {
        $obj = new COM('winmgmts://localhost/root/CIMV2');
        $fso = new COM("Scripting.FileSystemObject");
        $services = $obj->ExecQuery(sprintf("SELECT * FROM Win32_Service WHERE DisplayName='%s'", $name));

        foreach ($services as $wmi_call) {
            $status["CurrentState"] = $wmi_call->StartService();
        }
    }
}

function get_content($url) {
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);

    ob_start();

    curl_exec($ch);
    curl_close($ch);
    $string = ob_get_contents();

    ob_end_clean();

    return $string;
}
?>