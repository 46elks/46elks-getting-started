<?php

## Sending a messages with customized content for each person.
## recipients.csv includes a name, phone number, and a code
## for each person you wish to send this message to separated with ","

# Read from CSV
$file = fopen("recipients.csv", "r");

// If first line contains headlines, then run this first:
// fgetcsv($file, 10000, ",");

while ( $row = fgetcsv($file, 13000, ",") ) {
  list($name, $phone, $code) = $row;

  $message = "Hi". $name ."! \n
    We're excited to have you with us!\n
    If you'd like to attend the dinner the evening before,
    please confirm and let us know of any food preferences by visiting this link:.\n
    46elks.com/futureisbright-speakers-dinner/".$code."\n\n
    Regards,\n
    Marie Curie
    ";
  $message = preg_replace('!\s+!', ' ', $message);

  $sms = array(
    'from' => 'CurieEvent',   // Up to 11 characters (a-z,A-Z,0-9)
    'to' => $phone,
    'message' => $message
  );

  echo sendSMS($sms) . "\n\n";

}

fclose($file);


function sendSMS ($sms) {
  // Set your 46elks API username and API password here
  // You can find them at https://dashboard.46elks.com/
  $username = 'USERNAME';
  $password = 'PASSWORD';
  $context = stream_context_create(array(
    'http' => array(
      'method' => 'POST',
      'header'  => "Authorization: Basic ".
                   base64_encode($username.':'.$password). "\r\n".
                   "Content-type: application/x-www-form-urlencoded\r\n",
      'content' => http_build_query($sms),
      'timeout' => 10
  )));
  $response = file_get_contents(
    'https://api.46elks.com/a1/SMS', false, $context );
  if (!strstr($http_response_header[0],"200 OK"))
    return $http_response_header[0];

  return $response;
}


?>
