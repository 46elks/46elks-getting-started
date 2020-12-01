<!-- 
    
This PHP script takes care of a view different processes:

1. Receive a call
2. Store the number of the caller
3. Check if the call is being received between Mon-Friday and 9-17 https://www.php.net/manual/en/timezones.php
4. If 'Open' then connect to business owner
5. If 'Closed' play we are closed message and send an SMS to the business owner with caller details

-->

<?php

define('PHONE_NUMBER', 'business owner number');
define('OPEN_FILE', 'https://example.com/we-are-open.mp3');
define('CLOSED_FILE', 'https://example.com/we-are-closed.mp3');

function sendSMS ($sms) {
    $username = "username";
    $password = "password";
    $context = stream_context_create(array(
        'http' => array(
        'method' => 'POST',
        'header'  => 'Authorization: Basic '.
                    base64_encode($username.':'.$password). "\r\n".
                    "Content-type: application/x-www-form-urlencoded\r\n",
        'content' => http_build_query($sms),
        'timeout' => 10
    )));
    $response = file_get_contents("https://api.46elks.com/a1/sms",
        false, $context);
  
    if (!strstr($http_response_header[0],"200 OK"))
        return $http_response_header[0];
    return $response;
    }

// Post incomingCalls
function incomingCalls($callerid)

{   
    $tz_obj = new DateTimeZone('Europe/Stockholm');
    $today = new DateTime("now", $tz_obj);

    $weekday = intval($today->format('N'));
    $hour = intval($today->format('H'));

    // Check in range
    if (
        $weekday >= 1
        && $weekday <= 2
    ) {
        if (
            $hour >= 9
            && $hour <= 17
        ) {
            echo json_encode([
				'play' => OPEN_FILE,
                'next' => ([
					'connect' => PHONE_NUMBER
				])
            ]);
            die();
        }
    };
	$sms = array(
        "from" => "AfterHours",   /* Can be up to 11 alphanumeric characters */
        "to" => PHONE_NUMBER,  /* The mobile number you want to send to */
        "message" => "You received a call after hours from: ".$callerid,
    );
    sendSMS($sms);
    echo json_encode([
        'play' => CLOSED_FILE
    ]);
    die();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $caller = $_POST['from'];
    incomingCalls($caller);
    
}
?>