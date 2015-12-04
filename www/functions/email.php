<?php

function enviarMail($cuenta, $texto, $asunto) {
    global $_CONF;
    /*
      incluye la clases necesarias para enviar el mail
     */
    include('class.phpmailer.php');
    include('class.smtp.php');

    /*
      conexion con el servidor smtp
     */
    $mail = new PHPMailer();
    $mail->IsSMTP();

    $mail->SMTPAuth = true;
    $mail->SMTPSecure = "ssl";
    $mail->Host = $_CONF['smtp_host'];
    $mail->Port = $_CONF['smtp_port'];
    $mail->Username = $_CONF['smtp_user'];
    $mail->Password = $_CONF['smtp_pass'];

    /*
      Envio del mail
     */
    $mail->From = $_CONF['smtp_user'];
    $mail->FromName = $_CONF['smtp_name'];
    $mail->Subject = $asunto;
    //$mail->AltBody = ;
    $mail->MsgHTML($texto);
    $mail->AddAddress($cuenta);
    echo $cuenta;
    $mail->WordWrap = 70;
    $mail->IsHTML(true);
    if (!$mail->Send()) {
        return false;
    } else {
        return true;
    }
}
