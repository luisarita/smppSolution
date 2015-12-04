<?php
require_once('../connections/conexion.php');
require_once('functions.php');
session_start();
if (!isset($_SESSION['idAdmin'])) {
    header("Location: " . initPage());
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Administraci&oacute;n</title>
        <link type="text/css" rel="stylesheet" href="../css/style.css"  />
    </head>
    <body>
        <table cellpadding="1" cellspacing="1" width="220px">
            <tr><th>Men&uacute; Principal</th></tr>
            <tr><td class="menuitem"><a href="00.cambiar.clave.php">Cambiar Clave</a></td></tr><?php if (permission(15)) { ?><tr><td class="menuitem"><a href="15.usuarios.php">Usuarios</a></td></tr><?php } ?><?php if (permission(1)) { ?><tr><td class="menuitem"><a href="01.simulador.php">Simulador</a></td></tr><?php }
if (permission(2)) {
    ?><tr><td class="menuitem"><a href="02.conf.numeros.php">Configuraci&oacute;n de Números de Salida</a></td></tr><?php }
if (permission(3)) {
    ?><tr><td class="menuitem"><a href="03.conf.horas.salida.php">Horarios Suscripciones</a></td></tr><?php }
        if (permission(4)) {
    ?><tr><td class="menuitem"><a href="04.media.agregar.php">Agregar Media</a></td></tr><?php }
        if (permission(5)) {
    ?><tr><td class="menuitem"><a href="05.media.php">Consultar Media</a></td></tr><?php }
        if (permission(6)) {
    ?><tr><td class="menuitem"><a href="06.rifas.respuestas.busqueda.php">B&uacute;squeda de Respuestas Radio Chat</a></td></tr><?php }
        if (permission(7)) {
    ?><tr><td class="menuitem"><a href="fondos.encuestas.php">Fondos de Encuestas</a></td></tr><?php }
        if (permission(8)) {
    ?><tr><td class="menuitem"><a href="08.respuestas.php">Respuestas</a></td></tr><?php }
        if (permission(9)) {
    ?><tr><td class="menuitem"><a href="09.consulta.suscripcion.php">Consulta Suscripciones</a></td></tr><?php }
        if (permission(10)) {
    ?><tr><td class="menuitem"><a href="consulta.simulacion.php">Consulta Simulador</a></td></tr><?php }
        if (permission(11)) {
    ?><tr><td class="menuitem"><a href="11.servicios.php">Servicio SMPP</a></td></tr><?php }
        if (permission(12)) {
    ?><tr><td class="menuitem"><a href="12.confirmaciones.php">Confirmaciones</a></td></tr><?php }
        if (permission(13)) {
    ?><tr><td class="menuitem"><a href="13.blacklist.php">Blacklist Suscripciones</a></td></tr><?php }
        if (permission(13)) {
    ?><tr><td class="menuitem"><a href="13.blacklist.general.php">Blacklist General</a></td></tr><?php }
        if (permission(14)) {
    ?><tr><td class="menuitem"><a href="14.telebingos.php">TeleBingos</a></td></tr><?php }
        if (permission(17)) {
    ?><tr><td class="menuitem"><a href="17.suscripciones.carga.lotes.php">Suscripción por lotes</a></td></tr><?php }
        if (permission(17)) {
    ?><tr><td class="menuitem"><a href="17.suscripciones.carga.lote.consulta.php">Histórico de Suscripción por lotes</a></td></tr><?php }
        if (permission(30)) {
    ?><tr><td class="menuitem"><a href="30.suscripciones.anulacion.lotes.php">Desuscripción por lotes</a></td></tr><?php }
        if (permission(18)) {
    ?><tr><td class="menuitem"><a href="18.aniversarios.php">Aniversarios</a></td></tr><?php }
        if (permission(19)) {
    ?><tr><td class="menuitem"><a href="19.numeros.confirmaciones.php">Numeros para Confirmaci&oacute;n</a></td></tr><?php }
        if (permission(20)) {
    ?><tr><td class="menuitem"><a href="20.rifas.recuperacion.php">Recuperación de Rifas</a></td></tr><?php }
        if (permission(21)) {
    ?><tr><td class="menuitem"><a href="21.encuestas.recuperacion.php">Recuperación de Encuestas</a></td></tr><?php }
        if (permission(29)) {
    ?><tr><td class="menuitem"><a href="29.telechats.recuperacion.php">Recuperación de Telechats</a></td></tr><?php }
        if (permission(22)) {
    ?><tr><td class="menuitem"><a href="22.xls.rifas.php">Consolidado de Rifas</a></td></tr><?php }
        if (permission(23)) {
    ?><tr><td class="menuitem"><a href="23.xls.encuestas.php">Consolidado de Encuestas</a></td></tr><?php }
        if (permission(24)) {
    ?><tr><td class="menuitem"><a href="24.xls.suscripciones.php">Consolidado de Suscripciones</a></td></tr><?php }
        if (permission(25)) {
    ?><tr><td class="menuitem"><a href="25.xls.diccionarios.php">Consolidado de Diccionarios</a></td></tr><?php }
        if (permission(26)) {
    ?><tr><td class="menuitem"><a href="26.xls.listados.php">Consolidado de Listados</a></td></tr><?php }
        if (permission(27)) {
    ?><tr><td class="menuitem"><a href="27.xls.telechats.php">Consolidado de Telechats</a></td></tr><?php }
        if (permission(28)) {
    ?><tr><td class="menuitem"><a href="28.xls.trivias.php">Consolidado de Trivias</a></td></tr><?php }
        if (permission(36)) {
            ?><tr><td class="menuitem"><a href="36.xls.shortcodes.php">Consolidado Recepcion</a></td></tr><?php }
        if (permission(31)) {
            ?><tr><td class="menuitem"><a href="31.suscripciones.anuladas.php">Anulaciones de Suscripciones</a></td></tr><?php }
        if (permission(32)) {
            ?><tr><td class="menuitem"><a href="32.consulta.envios.php">Consulta de Envios</a></td></tr><?php }
        if (permission(33)) {
            ?><tr><td class="menuitem"><a href="33.consulta.cola.php">Consulta de Cola</a></td></tr><?php }
        if (permission(34)) {
            ?><tr><td class="menuitem"><a href="34.consulta.cola.historico.php">Consulta Historica de Cola</a></td></tr><?php }
        if (permission(35)) {
            ?><tr><td class="menuitem"><a href="35.suscripciones.frames.php">Envío de Suscripciones</a></td></tr><?php }
        if (permission(37)) {
            ?><tr><td class="menuitem"><a href="37.consulta.chats.rifas.php">Consulta Respuestas RadioChats</a></td></tr><?php }
        if (permission(38)) {
            ?><tr><td class="menuitem"><a href="38.consulta.chats.telechat.php">Consulta Respuestas Telechats</a></td></tr><?php }
        if (permission(39)) {
            ?><tr><td class="menuitem"><a href="39.chats.recuperacion.php">Recuperación de Chats</a></td></tr><?php }
        if (permission(40)) {
            ?><tr><td class="menuitem"><a href="40.consulta.chats.chat.php">Consulta Respuestas Chats</a></td></tr><?php }
        if (permission(41)) {
            ?><tr><td class="menuitem"><a href="41.auditoria.php">Auditoria</a></td></tr><?php }
        if (permission(42)) {
            ?><tr><td class="menuitem"><a href="42.rifas.carga.lotes.php">Rifas por lotes</a></td></tr><?php }
        if (permission(43)) {
            ?><tr><td class="menuitem"><a href="43.suscripciones.configuracion.variables.php">Suscripciones - Configuracion de Variables</a></td></tr><?php }
        if (permission(44)) {
            ?><tr><td class="menuitem"><a href="44.recordatorios.administracion.php">Recordatorios - Administración</a></td></tr><?php }
        if (permission(45)) {
            ?><tr><td class="menuitem"><a href="45.xls.suscripciones.php">Descarga de Suscripciones</a></td></tr><?php }
        if (permission(46)) {
            ?><tr><td class="menuitem"><a href="46.usuarios.administracion.php">Usuarios de Acceso</a></td></tr><?php }
        if (permission(46)) {
            ?><tr><td class="menuitem"><a href="46.usuarios.permisos.php">Usuarios de Acceso - Permisos</a></td></tr><?php }
        if (permission(47)) {
            ?><tr><td class="menuitem"><a href="47.palabras.bloqueadas.php">Palabras Bloqueadas</a></td></tr><?php }
        if (permission(49)) {
            ?><tr><td class="menuitem"><a href="49.recargas.admin.php">Promocionales - Mantenimientos</a></td></tr><?php }
        if (permission(50)) {
            ?><tr><td class="menuitem"><a href="50.revision.vpn.tigomoney.php">Revisión VPN Tigo Money</a></td></tr><?php }
        if (permission(51)) {
            ?><tr><td class="menuitem"><a href="51.conexiones.clientes.php">Conexiones Clientes</a></td></tr><?php }
        if (permission(51)) {
            ?><tr><td class="menuitem"><a href="51.conexiones.clientes.resumen.php">Resumen Conexiones Clientes</a></td></tr><?php }
        if (permission(52)) {
            ?><tr><td class="menuitem"><a href="52.mantenimiento.recargas.sorteos.php">Recargas - Sorteos</a></td></tr><?php }
        if (permission(53)) {
            ?><tr><td class="menuitem"><a href="53.consulta.excel.php">Consultas Excel</a></td></tr><?php }
        if (permission(54)) {
            ?><tr><td class="menuitem"><a href="54.graficos.recuperacion.php">Recuperaciones de Gráficos</a></td></tr><?php }
?></table>
    </body>
</html>