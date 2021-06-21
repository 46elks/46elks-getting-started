<?php

// Send an email to oyourself uppon incoming SMS
// Remember to setup sms_url on your phone number
//
// If you don't recieve an email, you might need to use another email library such as PHPMailer where you can add your user/pass.
// Email providers may block outgoing emails from non-authenticated requests to prevent spam.

  $from = $_POST['from'];
  $message = $_POST['message'];


  // If you want to send an e-mail to yourself with the incoming text message,
  $fullMessage = "Incoming SMS! \n\nMessage:\n".$message . ".\nFrom this phone number: ". $from;
  $headers = 'From: example@example.com'. "\r\n";
  mail("myself@example.com", "Incoming SMS has arrived", $fullMessage,  $headers);
?>

