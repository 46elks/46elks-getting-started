<?
// Send a reply with information when an incoming SMS is received
//
// 1. Upload the below script to your web server
// 2. Allocate a 46elks number
// 3. Change sms_url on the 46elks number to your script URL
// 4. Send an SMS to your 46elks number with any contents, and get an SMS reply
//
// Hint! To create different commands, check out $_POST['message']

$hours = array(
  'Mon' => '08:00 - 17:00',
  'Tue' => '08:00 - 17:00',
  'Wed' => '08:00 - 19:00',
  'Thu' => '08:00 - 17:00',
  'Fri' => '08:00 - 17:00',
  'Sat' => '10:00 - 16:00'
);

$weekday = date('D');

if (isset($hours[$weekday]))
  echo 'We are open today between '. $hours[$weekday];
else
  echo 'We are closed today';

?>