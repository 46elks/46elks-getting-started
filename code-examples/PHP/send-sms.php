<?
// Example to send SMS using the 46elks service
// Change $username, $password and the mobile number to send to

function sendSMS ($sms) {

  // Set your 46elks API username and API password here
  // You can find them at https://dashboard.46elks.com/
  $username = '';
  $password = '';

  $context = stream_context_create(array(
    'http' => array(
      'method' => 'POST',
      'header'  => "Authorization: Basic ".
                   base64_encode($username.':'.$password). "\r\n".
                   "Content-type: application/x-www-form-urlencoded\r\n",
      'content' => http_build_query($sms,'','&'),
      'timeout' => 10
  )));

  $response = file_get_contents(
    'https://api.46elks.com/a1/SMS', false, $context );

  if (!strstr($http_response_header[0],"200 OK"))
    return $http_response_header[0];

  return $response;
}


$sms = array(
  'from' => 'PHPElk',   /* Can be up to 11 alphanumeric characters */
  'to' => '+46766861647',  /* The mobile number you want to send to */
  'message' => 'Hello hello!',
  // 'flashsms' => 'yes', Uncomment this if you want to send a flash sms.
  // 'whendelivered':'https://YOUR-SERVER/whendelivered', Uncomment this if you want to setup delivery reports
  // "dryrun":"no" Uncomment this if you want to test the message. Enabling dryrun will show you the expected response without sending the SMS or charging your account

);
echo sendSMS ($sms) . "\n";

?>
