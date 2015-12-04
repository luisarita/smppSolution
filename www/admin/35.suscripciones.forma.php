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

if (isset($_GET['idSuscripcion'])) {
    $_SESSION['idSuscripcion'] = $_GET['idSuscripcion'];
    header("Location: ../suscripciones.php");
    exit();
}
?>