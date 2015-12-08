<?php
require_once('../connections/conexion.php');
require_once('../functions/functions.php');
require_once('../conf.php');
require_once('../functions/db.php');
require_once('functions.php');

session_start();
if (!isset($_SESSION['idAdmin'])) {
    header("Location: " . initPage());
    exit();
} else if (!permission(5)) {
    header("Location: " . initPage());
    exit();
}

$desde = (isset($_POST['desde'])) ? $_POST['desde'] : strftime("%Y-%m-%d 00:00:00", time());
$hasta = (isset($_POST['hasta'])) ? $_POST['hasta'] : strftime("%Y-%m-%d 23:59:00", time());

if (isset($_POST['MM_ACTION']) && $_POST['MM_ACTION'] == "Eliminar") {
    $query = sprintf("UPDATE ws_media SET estado=0 WHERE id=%s;", GetSQLValueString($_POST['id'], "int"));
    $rs = mysql_query($query, $conexion) or die(mysql_error()); //die(register_mysql_error("MC0001", mysql_error()));
} else if (isset($_POST['MM_ACTION']) && $_POST['MM_ACTION'] == "Actualizar") {
    if (!isset($_FILES['file']['name']))
        die('No ingreso un archivo');

    $name = $_FILES['file']['name'];
    $src = $_FILES['file']['tmp_name'];
    $path = select_path($_POST['tipo']);
    $name = str_replace(" ", "_", $name);
    move_uploaded_file($src, $root . $path . $name);
    $path .= $name;

    $query = sprintf("UPDATE ws_media SET path=%s WHERE id=%s;", GetSQLValueString($path, "text"), GetSQLValueString($_POST['id'], "int"));
    $rs = mysql_query($query, $conexion) or die(register_mysql_error("MC0002", mysql_error()));
}

$maxRows_rsMedia = 10;
$pageNum_rsMedia = 0;
if (isset($_GET['pageNum_rsMedia'])) {
    $pageNum_rsMedia = $_GET['pageNum_rsMedia'];
}
$startRow_rsMedia = $pageNum_rsMedia * $maxRows_rsMedia;

$query_rsMedia = "SELECT m.clave, COUNT(*) AS conteo FROM ws_media m, ws_compras_msg c WHERE m.estado=0 AND m.id=c.id_media AND fechaexp >='$desde' AND fechaexp <='$hasta' GROUP BY m.clave";
$query_limit_rsMedia = sprintf("%s LIMIT %d, %d", $query_rsMedia, $startRow_rsMedia, $maxRows_rsMedia);
$rsMedia = mysql_query($query_limit_rsMedia, $conexion) or die(register_mysql_error("MC0002", mysql_error()));

if (isset($_GET['totalRows_rsMedia'])) {
    $totalRows_rsMedia = $_GET['totalRows_rsMedia'];
} else {
    $all_rsMedia = mysql_query($query_rsMedia);
    $totalRows_rsMedia = mysql_num_rows($all_rsMedia);
}
$totalPages_rsMedia = ceil($totalRows_rsMedia / $maxRows_rsMedia) - 1;

$query_rsActuales = "SELECT m.id, m.nombre, m.usuario, t.nombre AS tipo, m.clave, m.numero, m.path FROM ws_media m INNER JOIN ws_tipo t ON t.id=m.tipo WHERE estado=1 ORDER BY m.nombre";
$rsActuales = mysql_query($query_rsActuales, $conexion) or die(register_mysql_error("MC0003", mysql_error()));

$sql = "SELECT id, nombre FROM ws_tipo";
$rsTipos = mysql_query($sql, $conexion) or die(register_mysql_error("MC0004", mysql_error()));

$selectTipos = '<select name="tipo" id="tipo">';
while ($rowTipos = mysql_fetch_assoc($rsTipos)) {
    $selectTipos .= sprintf("<option value='%s'>%s</option>", $rowTipos['id'], $rowTipos['nombre']);
}
$selectTipos .= "</select>";
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>Media</title>
        <link rel="stylesheet" type="text/css" href="../css/style.css" />
        <style type="text/css">@import url(lib/calendar/calendar-brown.css);</style>
        <script type="text/javascript" src="lib/calendar/calendar.js"></script>
        <script type="text/javascript" src="lib/calendar/lang/calendar-es.js"></script>
        <script type="text/javascript" src="lib/calendar/calendar-setup.js"></script>
    </head>
    <body>
        <form id="frmReinicio" name="frmReinicio" method="GET" action="">
            <table width="600px" cellpadding="0" cellspacing="1">
                <tr><th colspan="3">Media</th></tr>
                <tr><td colspan="3">&nbsp;</td></tr>     
                <tr>
                    <td>Desde:&nbsp;</td>
                    <td><input type="text" name='desde' class='textbox-medium' value='<?php echo $desde; ?>' /></td>
                    <td style="text-align: right"><button id="desde_btn">...</button></td>
                </tr><tr>
                    <td>Hasta:&nbsp;</td>
                    <td><input type="text" name='hasta' class='textbox-medium' value='<?php echo $hasta; ?>' /></td>
                    <td style="text-align: right"><button id="hasta_btn">...</button></td>
                </tr><tr>
                    <td colspan="3" style="text-align: right">
                        <button class="button" onClick="this.form.submit()">Filtrar</button>
                    </td>
                </tr>
                <tr><td colspan="3">&nbsp;</td></tr>     
            </table>
        </form>
        <table width='600px' cellspacing="1" cellpadding="0">
            <tr>
                <th scope="col">&nbsp;</th>
                <th scope="col">Clave</th>
                <th scope="col">Conteo</th>
            </tr>
            <tr><td colspan="3">&nbsp;</td></tr><?php
            while ($row_rsMedia = mysql_fetch_assoc($rsMedia)) {
                ?><tr>
                    <td><?php echo ++$i; ?></td>
                    <td><?php echo $row_rsMedia['clave']; ?></td>
                    <td style="text-align: right"><?php echo $row_rsMedia['conteo']; ?></td>
                </tr><?php
            }
            ?><tr><td colspan="3">&nbsp;</td></tr>     
        </table>  
        <table width="600px" cellspacing="1" cellpadding="5">
            <tr><td colspan="6">&nbsp;</td></tr>     
            <tr>
                <th>Nombre</th>
                <th>Tipo</th>
                <th>Usuario</th>
                <th>Clave</th>
                <th>Numero</th>
                <th>&nbsp;</th>
            </tr><?php
            while ($row_rsActuales = mysql_fetch_assoc($rsActuales)) {
                $color = (isset($color) && $color == "#D0D0D0") ? "#A0A0A0" : "#D0D0D0";
                ?><tr style="background-color: <?php echo $color; ?>">
                    <td><a href="/<?php echo $row_rsActuales['path'] ?>"><?php echo $row_rsActuales['nombre']; ?></a></td>
                    <td><?php echo $row_rsActuales['tipo']; ?></td>
                    <td><?php echo $row_rsActuales['usuario']; ?></td>
                    <td><?php echo $row_rsActuales['clave']; ?></td>
                    <td><?php echo $row_rsActuales['numero']; ?></td>
                    <td style="text-align: left;">
                        <form method="post" action="" enctype="multipart/form-data">
                            <input type="hidden" name="id" value="<?php echo $row_rsActuales['id']; ?>" />
                            <?php echo $selectTipos; ?><br/>
                            <input name="file" type="file" id="file" /><br/><br/>
                            <input type="submit" class="msg-button" style="width: 100px" value="Actualizar" name="MM_ACTION" />
                            <input type="submit" class="warning-button-small" onclick="javascript: return confirm('Desea eliminar este registro?')" value="Eliminar" name="MM_ACTION" />
                        </form><br/>
                    </td>
                </tr><?php }
                        ?>
            <tr><th colspan="6" style="text-align: center"><a href="menu.php">Menu</a></th></tr>   
        </table>
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