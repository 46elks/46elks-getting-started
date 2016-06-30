<?php

$url = 'https://api.46elks.com/a1/Calls';
$username = '<API Username>';
$password = '<API Password>';
$sms = array('from' => '+46709751949',
             'to' => '+46709751949',
             'voice_start' => '{"connect":"+461890510"}');
            
$context = stream_context_create(array(
    'http' => array(
        'method' => 'POST',
        'header'  => "Authorization: Basic ".
            base64_encode($username.':'.$password). "\r\n".
            "Content-type: application/x-www-form-urlencoded\r\n",
        'content' => http_build_query($sms),
        'timeout' => 10
        )
    ));

$response = file_get_contents($url, false, $context );
print $response;

?>