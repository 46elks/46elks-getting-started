<?php

// Example to receive email upon incoming call
// Remember to setup voice_start on your phone number
//
// If you don't recieve an email, you might need to use another email library such as PHPMailer where you can add your user/pass.
// Email providers may block outgoing emails from non-authenticated requests to prevent spam.

  $from = $_POST['from'];
  $call_ID = $_POST['callid'];


  // If you want to send an e-mail to yourself with the incoming text message,
  $fullMessage = "You received a call! \n\nCall ID:\n".$call_ID . ".\nFrom this phone number: ". $from;
  $headers = 'From: example@example.com'. "\r\n";
  mail("myself@example.com", "Incoming call", $fullMessage,  $headers);
?>

