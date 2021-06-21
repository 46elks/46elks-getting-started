<?
// Example show SMS history using the 46elks service
// Change $username, $password and print_r() to some more useful code

function getHistory ($extra = '') {

  // Set your 46elks API username and API password here
  // You can find them at https://dashboard.46elks.com/
  $username = '';
  $password = '';

  $context = stream_context_create(array(
    'http' => array(
      'method' => 'GET',
      'header'  => "Authorization: Basic ".
                   base64_encode($username.':'.$password). "\r\n",
      'timeout' => 10
  )));

  $text = file_get_contents(
    'https://api.46elks.com/a1/SMS'.$extra, false, $context );

  if (!strstr($http_response_header[0],"200 OK"))
    die ($http_response_header[0]);
  
  return json_decode($text,1);
}

// Iterate over your SMS history, 100 SMS messages per request

$extra = '';
do {

  $response = getHistory($extra);
  $extra = '?start='. $response['next'];

  print_r($response['data']);

} while (isset($response['next']) && sizeof($response['data']) > 0);

?>
