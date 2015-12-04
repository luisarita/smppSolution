<?php

function fixName($name) {
    $name = str_replace("\\", "", $name);
    $name = str_replace("/", "", $name);
    $name = str_replace("(", "", $name);
    $name = str_replace(")", "", $name);
    $name = str_replace("?", "", $name);
    $name = str_replace("!", "", $name);
    $name = str_replace("ñ", "n", $name);
    $name = str_replace("Ň", "N", $name);
    $name = substr($name, 0, 31);
    return $name;
}
