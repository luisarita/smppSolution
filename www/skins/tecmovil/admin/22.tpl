<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>{$title}</title>
        <link rel="stylesheet" type="text/css" href="../css/style.css" />
        <style type="text/css">@import url(../lib/calendar/calendar-brown.css);</style>
        <script type="text/javascript" src="../lib/calendar/calendar.js"></script>
        <script type="text/javascript" src="../lib/calendar/lang/calendar-es.js"></script>
        <script type="text/javascript" src="../lib/calendar/calendar-setup.js"></script>
        <script type="text/javascript">
            function loadComplete(){
                Calendar.setup({
                    inputField  : "dateFrom",
                    ifFormat    : "%Y-%m-%d %H:%M:%S",
                    showsTime   : true,
                    button      : "dateFrom_btn",
                    singleClick : true,
                    step        : 1
                });

                Calendar.setup({
                    inputField  : "dateTo",
                    ifFormat    : "%Y-%m-%d %H:%M:%S",
                    showsTime   : true,
                    button      : "dateTo_btn",
                    singleClick : true,
                    step        : 1
                });
            }
        </script>
    </head>
    <body onload="loadComplete()">
        <form id="formGenerate" name="formGenerate" method="POST" action="">
            <table style="width: 220px">
                <tr><th colspan="2">{$title}</th></tr>
                <tr><td colspan="2">&nbsp;</td></tr>
                <tr>
                    <td>{$activityLabel}:</td>
                    <td>
                        <select name="id" id="id">
                            {foreach from=$activityOptions key=k item=v}
                                <option value="{$k}">{$v}</option>
                            {/foreach}
                        </select>
                    </td>
                </tr>
                <tr><td colspan="2">&nbsp;</td></tr>
                <tr>
                    <td>{$dateFromLabel}:</td>
                    <td>
                        <input class="textbox-small" type="text" id="dateFrom" name='dateFrom' value='{$dateFrom}' />&nbsp;
                        <button name="dateFrom_btn" id="desde_btn">..</button>
                    </td>
                </tr>
                <tr><td colspan="2">&nbsp;</td></tr>
                <tr>
                    <td>{$dateToLabel}:</td>
                    <td><input class="textbox-small" type="text" id='dateTo' name='dateTo' value='{$dateTo}' />&nbsp;<button name="dateTo_btn" id="dateTo_btn">..</button></td>
                </tr>
                <tr><td colspan="2">&nbsp;</td></tr>    
                <tr><td colspan="2" style="text-align: right">
                    <input name="accion" type="submit" class="button" id="accion" value="Generar" />
                </td></tr>    
                <tr><td colspan="2">&nbsp;</td></tr>    
                <tr><th colspan="2" style="text-align: center"><a href="menu.php">Menu</a></th></tr>
            </table>
        </form>
    </body>  
</html>