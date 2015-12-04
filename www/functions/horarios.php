<?php

function formatearHora($hora) {
    if ($hora == 0)
        $msg = "12:00 AM";
    elseif ($hora == 1)
        $msg = "01:00 AM";
    elseif ($hora == 2)
        $msg = "02:00 AM";
    elseif ($hora == 3)
        $msg = "03:00 AM";
    elseif ($hora == 4)
        $msg = "04:00 AM";
    elseif ($hora == 5)
        $msg = "05:00 AM";
    elseif ($hora == 6)
        $msg = "06:00 AM";
    elseif ($hora == 7)
        $msg = "07:00 AM";
    elseif ($hora == 8)
        $msg = "08:00 AM";
    elseif ($hora == 9)
        $msg = "09:00 AM";
    elseif ($hora == 10)
        $msg = "10:00 AM";
    elseif ($hora == 11)
        $msg = "11:00 AM";
    elseif ($hora == 12)
        $msg = "12:00 PM";
    elseif ($hora == 13)
        $msg = "01:00 PM";
    elseif ($hora == 14)
        $msg = "02:00 PM";
    elseif ($hora == 15)
        $msg = "03:00 PM";
    elseif ($hora == 16)
        $msg = "04:00 PM";
    elseif ($hora == 17)
        $msg = "05:00 PM";
    elseif ($hora == 18)
        $msg = "06:00 PM";
    elseif ($hora == 19)
        $msg = "07:00 PM";
    elseif ($hora == 20)
        $msg = "08:00 PM";
    elseif ($hora == 21)
        $msg = "09:00 PM";
    elseif ($hora == 22)
        $msg = "10:00 PM";
    elseif ($hora == 23)
        $msg = "11:00 PM";
    else
        $msg = '';
    return $msg;
}

function abreviaturaDia($dia) {
    $dias = array('L', 'Ma', 'Mi', 'J', 'V', 'S', 'D');
    return $dias[$dia];
}

function abreviaturaDias($row) {
    $abreviatura = "";
    if ($row['dia_lunes'] == 1)
        $abreviatura .= abreviaturaDia(0);
    if ($row['dia_martes'] == 1)
        $abreviatura .= abreviaturaDia(1);
    if ($row['dia_miercoles'] == 1)
        $abreviatura .= abreviaturaDia(2);
    if ($row['dia_jueves'] == 1)
        $abreviatura .= abreviaturaDia(3);
    if ($row['dia_viernes'] == 1)
        $abreviatura .= abreviaturaDia(4);
    if ($row['dia_sabado'] == 1)
        $abreviatura .= abreviaturaDia(5);
    if ($row['dia_domingo'] == 1)
        $abreviatura .= abreviaturaDia(6);
    return $abreviatura;
}

function selectHoras($comparador, $nombre) {
    $html = "<select name='$nombre' id='$nombre'>";
    for ($i = 0; $i <= 23; ++$i) {
        $html .= "<option value='" . str_pad($i, 2, "0", STR_PAD_LEFT) . "' " . (($comparador == $i ) ? 'selected' : '' ) . ">" . formatearHora($i) . "</option>";
    }
    $html .= "</select>";
    return $html;
}

function inputsDias($row) {
    $dias = array("dia_l", "dia_ma", "dia_mi", "dia_j", "dia_v", "dia_s", "dia_d");

// print_r($row);
    $html = "";
    foreach ($dias as $i => $nombre) {
        $html .= "<input class='checkbox' type='checkbox' name='$nombre' id='$nombre' " . ((isset($row[$nombre]) && $row[$nombre] == 1) ? 'checked' : '') . "/>" . abreviaturaDia($i);
    }
    return $html;
}

?>