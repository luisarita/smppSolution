<?php
require_once('../connections/conexion.php');
require_once('../functions/functions.php');
require_once('../functions/db.php');
require_once('functions.php');

session_start();
if (!isset($_SESSION['idAdmin'])) {
    header("Location: " . initPage());
    exit();
} else if (!permission(35)) {
    header("Location: " . initPage());
    exit();
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Suscripciones</title>
    </head>
    <frameset cols="350,*" frameborder="no" border="0" framespacing="0">
        <frame src="35.suscripciones.listado.php" name="leftFrame" scrolling="Auto" noresize="noresize" id="leftFrame" title="leftFrame" />
        <frame src="35.suscripciones.listado.php?empty" name="mainFrame" id="mainFrame" title="mainFrame" />
    </frameset>
    <noframes><body>
        </body></noframes>
</html>
