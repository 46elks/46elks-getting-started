<?
// "Roll your own" SMS group messaging on the 46elks platform
// 
// Allocated one or two numbers using the /Numbers REST resource, and update
// the configuration below with your newly allocated numbers. Also be sure
// to set up the numbers for your group members.
//
// Lastly, be sure to change $username and $password in the sendSMS function.


// SMS group definitions
$groups = array(
  '+46761070124' => array(
    'name' => 'The development team',
    'members' => array(
      '+46707808449' => 'sirmike',
      '+46761239871' => 'sl0wcoder',
      '+46739338123' => 'jlundberg'
    )
  ),
  '+46761070125' => array(
    'name' => 'Family',
    'members' => array(
      '+46735207657' => 'Anders',
      '+46707224755' => 'Marie',
      '+46709194878' => 'Kicki',
      '+46703738061' => 'Carina'
    )
  )
);

function sendSMS ($sms) {

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
      'content' => http_build_query($sms),
      'timeout' => 5
  )));

  return false !== file_get_contents(
    'https://api.46elks.com/a1/SMS', false, $context );
}

// Processing below
if (isset($groups[$_POST['to']]['members'][$_POST['from']])) {
  $group = $groups[$_POST['to']];
  
  $author = $group['members'][$_POST['from']];
  
  foreach ($group['members'] as $membernr => $membername) {
    if ($membernr == $_POST['from'])
      continue;

    $sms = array(
      'from' => $_POST['to'],
      'to' => $membernr,
      'message' => $author .': '. $_POST['message']
    );
    
    sendSMS ($sms);
  }
}

?>
