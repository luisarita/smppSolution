<?php
require_once(__DIR__ . '/../conf.php');

$conexion = mysql_connect($_CONF['mysql_host'], $_CONF['mysql_user'], $_CONF['mysql_pass'], false, 128) or trigger_error(mysql_error(), E_USER_ERROR);
mysql_select_db($_CONF['mysql_database']) or die(mysql_error());