<?php

if (!function_exists('DaysInMonth')) {

    function DaysInMonth($Year, $MonthInYear) {
        if (in_array($MonthInYear, array(1, 3, 5, 7, 8, 10, 12)))
            return 31;
        if (in_array($MonthInYear, array(4, 6, 9, 11)))
            return 30;
        if ($MonthInYear == 2)
            return ( checkdate(2, 29, $Year) ) ? 29 : 28;
        return false;
    }

}

if (!function_exists('getMes')) {

    function getMes($mes) {
        $m = monthArray();
        $m = $m[$mes];
        return $m;
    }

}

if (!function_exists('getMonthArray')) {

    function monthArray() {
        $months = array(
            1 => 'Enero',
            2 => 'Febrero',
            3 => 'Marzo',
            4 => 'Abril',
            5 => 'Mayo',
            6 => 'Junio',
            7 => 'Julio',
            8 => 'Agosto',
            9 => 'Septiembre',
            10 => 'Octubre',
            11 => 'Noviembre',
            12 => 'Diciembre');
        return $months;
    }

}
?>