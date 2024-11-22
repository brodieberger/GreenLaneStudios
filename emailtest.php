<?php
    $to      = 'brodieberger@gmail.com';
    $subject = 'TEST EMAIL HELLO';
    $message = 'hello';
    $headers = 'From: brodieberger@hawkislandmarina.net'       . "\r\n" .
                 'X-Mailer: PHP/' . phpversion();

    mail($to, $subject, $message, $headers);
    echo $message;
?>