<?php

$url = 'https://api.46elks.com/a1/Calls';
$username = '<API Username>';
$password = '<API Password>';
$sms = array('from' => '+46709751949',
             'to' => '+46709751949',
             'voice_start' => '{"connect":"+461890510"}');

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($sms));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_POST, TRUE);
$result = curl_exec($ch);
curl_close($ch);

print $result;

?></code></pre>