<?php

## Sending a messages with customized content for each person.
##
## recipients.csv includes a name, phone number, and a code
## for each person you wish to send this message to,
## separated with ";"

function sendSMS ($sms) {
  // Set your 46elks API username and API password here
  // You can find them at https://dashboard.46elks.com/
  $username = '<username>';
  $password = '<password>';
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

  # Read from CSV
  if (($handle = fopen("recipients.csv", "r")) !== FALSE) {
      while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
          $num = count($data);
          for ($c=0; $c < $num; $c++) {
              $this_row = $data[$c];
              list($name, $phone, $code) = explode(";", $this_row);

              $message = "Hi". $name ."! \n
We're excited to have you with us!\n
If you'd like to attend the speakers dinner the evening before,
please confirm and let us know of any food preferences by visiting this link:.\n
46elks.com/futureisbright-speakers-dinner/".$code."\n\n
Regards,\n
Marie Curie
";
            $sms = array(
              'from' => 'CurieFIB',   /* Can be up to 11 alphanumeric characters (a-z,A-Z,0-9) */
              'to' => $phone,  /* The mobile number you want to send to */
              'message' => $message,
              /* 'flashsms' => 'yes', /* Un comment this if you want to send a flash sms. */
          );
          echo sendSMS ($sms) . "\n\n";

          }
      }
      fclose($handle);
  }
?>
