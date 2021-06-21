<?
// Initiate an outgoing call, and play a message to the remote party.
//
// 1. Change 46elks API username & password
// 2. Allocate a 46elks number if you haven't already
// 3. Change +46111112222 to the 46elks number you want to make the call from
// 4. Change +46555444333 to your cell phone number in E.164 format
// 5. Change http://myserver.se/message.wav to a WAV or MP3 on your web server
// 6. Run this on your webserver or using PHP cli / cgi
//

function newOutgoingCall ($call) {

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
      'content' => http_build_query($call),
      'timeout' => 10
  )));

  return false !== file_get_contents(
    'https://api.46elks.com/a1/Calls', false, $context );
}

$call = array(
  'from' => '+46111112222',
  'to' => '+46555444333',
  'voice_start' => '{"play":"http://myserver.se/message.wav"}'
);
newOutgoingCall($call);

?>
