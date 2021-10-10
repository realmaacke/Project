<?php
// forget password sender using a gmail
require_once('PHPMailer/PHPMailerAutoload.php');

$mail = new PHPMailer();
$mail->isSMTP();
$mail->SMTPAuth = true;
$mail->SMTPSecure = 'ssl';
$mail->Host = 'smtp.gmail.com';
$mail->Port = '465';
$mail->isHTML();
$mail->Username = 'combind.network@gmail.com';
$mail->Password = 'Mackan321-';
$mail->setFrom('no-reply@Combined.live');
$mail->Subject = 'Hello world';
$mail->Body = 'Test email';
$mail->addAddress('mackan.pettersson1@outlook.com');
?>