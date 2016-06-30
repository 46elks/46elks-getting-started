<?
// DIY Click-To-Call using the 46elks platform

function newCall ($call) {

  // Set your 46elks API username and API password here
  // You can find them at https://dashboard.46elks.com/
  $username = 'u2c11ef65b429a8e16ccb1f960d02c734';
  $password = 'C0ACCEEC0FAFE879189DD5D57F6EC348';

  $context = stream_context_create(array(
    'http' => array(
      'method' => 'POST',
      'header'  => "Authorization: Basic ".
                   base64_encode($username.':'.$password). "\r\n".
                   "Content-type: application/x-www-form-urlencoded\r\n",
      'content' => http_build_query($call),
      'timeout' => 5
  )));

  return false !== file_get_contents(
    'https://api.46elks.com/a1/Calls', false, $context );
}

$call = array(
  // Put one of your 46elks numbers here
  'from' => '+46766861001',
  // For real world, you might want to restrict this to only allow one country
  'to' => $_POST['mobilenumber'],
  // Destination phone number
  'voice_start' => '{"connect":"+461890510"}'
);

?>

<html>
  <body style="font-family: Verdana; max-width: 380px;">

  <? if(!$_POST['mobilenumber']): ?>

    <h2>Click-To-Call example</h2>
    <p>
      Enter your phone number in the box and you will receive a call. 
      When you answer, you will connected to +461890510 for free.
      <form method="POST" action="?">
        <input type="text" name="mobilenumber" value="+4670">
        <input type="submit" value="Start call">
      </form>
    </p>

  <? else: ?>

    <h1>Initiating call.. Answer your phone.</h1>
    <? newCall ($call); ?>

  <? endif; ?>

  </body>
</html>
