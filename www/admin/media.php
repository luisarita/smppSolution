<?php
require_once('../Connections/conexion.php');

$title = "Media";
$initPage = "media.html";

session_start();
if (!isset($_SESSION['idAdmin'])) {
    header("Location: " . $initPage);
}
$desde = (isset($_GET['desde'])) ? $_GET['desde'] : strftime("%Y-%m-%d %H:%M", time());
$hasta = (isset($_GET['hasta'])) ? $_GET['hasta'] : strftime("%Y-%m-%d %H:%M", time());

if (!function_exists("GetSQLValueString")) {

    function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") {
        $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

        $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

        switch ($theType) {
            case "text":
                $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
                break;
            case "long":
            case "int":
                $theValue = ($theValue != "") ? intval($theValue) : "NULL";
                break;
            case "double":
                $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
                break;
            case "date":
                $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
                break;
            case "defined":
                $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
                break;
        }
        return $theValue;
    }

}

if (isset($_GET['id'])) {
    mysql_select_db($database_conexion, $conexion);
    $query = "DELETE FROM ws_media WHERE id=" . $_GET['id'];
    $rs = mysql_query($query, $conexion) or die(mysql_error());
}

$maxRows_rsMedia = 10;
$pageNum_rsMedia = 0;
if (isset($_GET['pageNum_rsMedia'])) {
    $pageNum_rsMedia = $_GET['pageNum_rsMedia'];
}
$startRow_rsMedia = $pageNum_rsMedia * $maxRows_rsMedia;

mysql_select_db($database_conexion, $conexion);
$query_rsMedia = "SELECT m.clave, COUNT(*) AS conteo FROM ws_media m, ws_compras_msg c WHERE m.id=c.id_media AND fechaexp >='$desde' AND fechaexp <='$hasta' GROUP BY m.clave";
$query_limit_rsMedia = sprintf("%s LIMIT %d, %d", $query_rsMedia, $startRow_rsMedia, $maxRows_rsMedia);
$rsMedia = mysql_query($query_limit_rsMedia, $conexion) or die(mysql_error());
$row_rsMedia = mysql_fetch_assoc($rsMedia);

if (isset($_GET['totalRows_rsMedia'])) {
    $totalRows_rsMedia = $_GET['totalRows_rsMedia'];
} else {
    $all_rsMedia = mysql_query($query_rsMedia);
    $totalRows_rsMedia = mysql_num_rows($all_rsMedia);
}
$totalPages_rsMedia = ceil($totalRows_rsMedia / $maxRows_rsMedia) - 1;

$query_rsActuales = "SELECT id, nombre, path FROM ws_media ORDER BY nombre";
$rsActuales = mysql_query($query_rsActuales, $conexion) or die(mysql_error());
$row_rsActuales = mysql_fetch_assoc($rsActuales);
$totalRows_rsActuales = mysql_num_rows($rsActuales);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title><?php echo $title ?></title>
        <style type="text/css">
            <!--
            body { background-image: url(../images/bg.jpg); background-repeat:repeat; background-attachment:fixed; }
            th   { font-size: 14px; text-align: center; font-weight: bold; border: solid 1px #FFFFFF; background-color:#FF8811 }
            td   { font-size: 12px; padding-left: 3px; padding-right: 3px; text-align: center; vertical-align: top; }
            a    { font-size: 12px; font-weight: bold; color: #CC0000; text-decoration: none}
            td.content    { padding-bottom: 5px; padding-top: 5px; border-bottom: 1px solid #FFFFFF }
            input.textbox { width: 400px; }
            input.warning-button { color: #FFFFFF; background-color:#FF0000; border: 1px solid #FFFFFF}
            input.msg-button     { color: #FFFFFF; background-color:#AAAA00; border: 1px solid #FFFFFF}
            -->
        </style>
        <link rel="stylesheet" type="text/css" href="lib/calendar/calendar-brown.css" />
        <script type="text/javascript" src="lib/calendar/calendar.js"></script>
        <script type="text/javascript" src="lib/calendar/lang/calendar-es.js"></script>
        <script type="text/javascript" src="lib/calendar/calendar-setup.js"></script>
    </head>
    <body bgcolor="#FFFFFF" text="#FFFFFF" background="images/bg.jpg">
        <font face="Verdana, Arial, Helvetica, sans-serif" color="#FFFFFF" size="-1">
            <form id="frmReinicio" name="frmReinicio" method="GET" action="">

                <table border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td>
                            <table width="350" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <th scope="col">&nbsp;</th>
                                    <th scope="col">Clave</th>
                                    <th scope="col">Conteo</th>
                                </tr><?php
                                for ($i = 1; $i <= $totalRows_rsMedia; ++$i) {
                                    ?><tr>
                                        <td class="content"><?php echo $i; ?></td>
                                        <td class="content"><?php echo $row_rsMedia['clave']; ?></td>
                                        <td class="content"><?php echo $row_rsMedia['conteo'];
                                $row_rsMedia = mysql_fetch_assoc($rsMedia); ?></td>
                                    </tr>
<?php } ?>
                            </table>
                        </td>
                        <td>
                            <table border="0" cellpadding="0" cellspacing="0" width="300">
                                <tr>
                                    <td>Desde:&nbsp;</td>
                                    <td><input type="text" name='desde' value='<?php echo $desde; ?>' /></td>
                                    <td><button id="desde_btn">...</button></td>
                                </tr>
                                <tr>
                                    <td>Hasta:&nbsp;</td>
                                    <td><input type="text" name='hasta' value='<?php echo $hasta; ?>' /></td>
                                    <td><button id="hasta_btn">...</button></td>
                                </tr>
                                <tr>
                                    <td colspan="3">
                                        <div align="right">
                                            <button onClick="this.form.submit()">Filtrar</button>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </form>
            <br />
            <table width="350" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <th>Nombre</th>
                    <th>&nbsp;</th>
                </tr>
<?php do { ?>
                    <form method="get">
                        <tr>
                            <td class="content"><a href="/<?php echo $row_rsActuales['path'] ?>"><?php echo $row_rsActuales['nombre']; ?></a></td>
                            <td class="content">
                                <input type="hidden" name="id" value="<?php echo $row_rsActuales['id']; ?>" />
                                <input type="submit" class="warning-button" onclick="javascript: return confirm('�Desea eliminar este registro?')" value="Eliminar" />
                            </td>
                        </tr>
                    </form>
<?php } while ($row_rsActuales = mysql_fetch_assoc($rsActuales)); ?>
            </table>
        </font>
    </body>
    <script type="text/javascript">
        Calendar.setup({
            inputField: "desde", // id of the input field
            ifFormat: "%Y-%m-%d %H:%M", // format of the input field
            showsTime: true, // will display a time selector
            button: "desde_btn", // trigger for the calendar (button ID)
            singleClick: true, // double-click mode
            step: 1                 // show all years in drop-down boxes (instead of every other year as default)
        });

        Calendar.setup({
            date: "2006/01/11",
            inputField: "hasta", // id of the input field
            ifFormat: "%Y-%m-%d %H:%M", // format of the input field
            showsTime: true, // will display a time selector
            button: "hasta_btn", // trigger for the calendar (button ID)
            singleClick: true, // double-click mode
            step: 1                 // show all years in drop-down boxes (instead of every other year as default)
        });
    </script>
</html>
<?php
mysql_free_result($rsMedia);

mysql_free_result($rsActuales);
?>