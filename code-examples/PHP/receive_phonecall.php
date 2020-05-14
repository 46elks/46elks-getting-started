<?php

// Example to receive SMS using the 46elks service
// Remember to setup sms_url on your phone number

  $from = $_POST['from'];
  $call_ID = $_POST['callid'];


  // If you want to send an e-mail to yourself with the incoming text message,
  $fullMessage = "You received a call! \n\nCall ID:\n".$call_ID . ".\nFrom this phone number: ". $from;
  $headers = 'From: example@example.com'. "\r\n";
  mail("myself@example.com", "Incoming SMS has arrived", $fullMessage,  $headers);
?>

