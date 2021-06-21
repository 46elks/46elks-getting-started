<?php

function sendcalls ($calls) {
  $username = 'API-USERNAME';
  $password = 'API-PASSWORD';
  $context = stream_context_create(array(
    'http' => array(
      'method' => 'POST',
      'header'  => 'Authorization: Basic '.
                   base64_encode($username.':'.$password). "\r\n".
                   "Content-type: application/x-www-form-urlencoded\r\n",
      'content' => http_build_query($calls),
      'timeout' => 10
  )));
  $response = file_get_contents("https://api.46elks.com/a1/calls",
    false, $context);

  if (!strstr($http_response_header[0],"200 OK"))
    return $http_response_header[0];
  return $response;
}
$calls = array(
  "from" => "+46700000000",   /* Can be up to 11 alphanumeric characters */
  "to" => "+46700000000",  /* The mobile number you want to send to */
  "voice_start" => '{"connect":"+46700000000"}',
  "whenhangup" => "https://yourserver.net"
);
echo sendcalls($calls);

?>
