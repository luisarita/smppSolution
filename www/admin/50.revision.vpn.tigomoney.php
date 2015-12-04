<?php

session_start();

require_once('../connections/conexion.php');
require_once('../functions/functions.php');
require_once('../functions/db.php');
require_once('functions.php');

class admin {

    private $template = "50.revision.vpn.tigomoney.html";
    private $url = "http://192.168.217.133:7890/O2CAutoHN/services/SpecialSuperAgentTransaction?wsdl";

    function admin() {
        if (!isset($_SESSION['idAdmin'])) {
            header("Location: " . initPage());
            exit();
        } else if (!permission(50)) {
            header("Location: " . initPage());
            exit();
        }
        echo $this->getHtml();
    }

    function revision() {
        $file_headers = @get_headers($this->url);
        //print_r($file_headers);
        if ($file_headers[0] == 'HTTP/1.1 200 OK') {
            return true;
        } else {
            return false;
        }
    }

    function getHtml() {
        $html = file_get_contents($this->template);
        $html = str_replace("@@TITLE@@", "Revisión VPN Tigo Money", $html);
        $html = str_replace("@@CONTENT1@@", (($this->revision()) ? "Disponible" : "No Disponible"), $html);
        return $html;
    }

}

$r = new admin();
?>

