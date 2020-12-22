<?php

$message = 'Good Morning 💪🏼 Here is a link to next weeks class schedule
- www.megagym.com/week14'; #Change this to the message you want to send

$recipients = array(
 '+46766866966', #Change these to numbers in your own lists
 '+46766868609'

);

function sendsms ($sms) {

 // Copy your API username and API password from your account here
 $username = 'u********'; #Change this
 $password = 'F********'; #Change this

 $context = stream_context_create(array(
   'http' => array(
     'method' => 'POST',
     'header'  => "Authorization: Basic ".
                  base64_encode($username.':'.$password). "\r\n".
                  "Content-type: application/x-www-form-urlencoded\r\n",
     'content' => http_build_query($sms),
     'timeout' => 10
 )));

 return false !== file_get_contents(
   'https://api.46elks.com/a1/SMS', false, $context );
}

foreach( $recipients as $mobilenumber ) {
 $sms = array(
   'to' => $mobilenumber,
   'from' => 'MegaGym', #Change this to your business name
   'message' => $message
 );
 echo sendsms ($sms);}
 ?>