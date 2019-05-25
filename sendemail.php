<?php
    ini_set( 'display_errors', 1 );
    error_reporting( E_ALL );
    $from = "tester@swsec-eshop.local";
    $to = "gbkaragiannidis@gmail.com";
    $subject = "Software Security e-shop";
    $message = "Mail notifications work!";
    $headers = "From:" . $from;
    mail($to,$subject,$message, $headers);
    echo "Email sent successfully.";
?>
