<?php

// Example to receive SMS using the 46elks service
// Remember to setup sms_url on your phone number

  $from = $_POST['from'];
  $message = $_POST['message'];


  // If you want to send an e-mail to yourself with the incoming text message,
  $fullMessage = "Incoming SMS! \n\nMessage:\n".$message . ".\nFrom this phone number: ". $from;
  $headers = 'From: example@example.com'. "\r\n";
  mail("myself@example.com", "Incoming SMS has arrived", $fullMessage,  $headers);
?>

