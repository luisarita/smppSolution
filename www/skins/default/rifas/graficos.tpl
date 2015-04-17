<form id='form' name='form' method='get' action='?MM_ACTION=grafico'>
    <tr><td style='text-align: center'>
        <a href='?MM_ACTION=grafico&height=480&width=640&month={$month}&anio={$year}'>
            <img src='?MM_ACTION=grafico&height=240&width=320&month={$month}&anio={$year}' border='1' align='top'/>
        </a>
    </td><td style='text-align: center'>
        <a href='?MM_ACTION=graficoHorario&height=480&width=640&month={$month}&anio={$year}'>
            <img src='?MM_ACTION=graficoHorario&height=240&width=320&month={$month}&anio={$year}' border='1' align='top'/>
        </a>
    </td></tr>
    <tr><td colspan='2'>&nbsp;</td></tr>
    <tr>
        <td style='text-align: center'>
            <a href='?MM_ACTION=graficoVariable&height=480&width=640&month={$month}&anio={$year}&variable=1'>
                <img src='?MM_ACTION=graficoVariable&height=240&width=320&month={$month}&anio={$year}&variable=1' border='1' align='top'/>
            </a>
        </td>
        <td style='text-align: right; vertical-align: top'>Mes:
            <select name='month' style='width: 220px'>
                {$options1}
            </select><br/><br/>A&ntilde;o:
            <select name='anio' style='width: 220px'>
               {$options2}
           </select>
        </td>
    </tr>
    <tr><td colspan='2'>&nbsp;</td></tr>
    <tr><td colspan='2' style='text-align: right'>
        <input type='hidden' name='height' value='480' />
        <input type='hidden' name='width' value='640' />
        <input class='button' type='button' onClick='this.form.submit()' value='Mostrar' />
    </td></tr>
    <tr><td colspan='2'>&nbsp;</td></tr>
</form>