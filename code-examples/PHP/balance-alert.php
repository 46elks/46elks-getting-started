<?
// Example of how to check status of account in the 46elks service
// Change $username, $password to your account values.


function checkBalance() {

  // Set your 46elks API username and API password here
  // You can find them at https://dashboard.46elks.com/
  $username = '';
  $password = '';

  $context = stream_context_create(array(
    'http' => array(
      'method' => 'GET',
      'header'  => "Authorization: Basic ".
                   base64_encode($username.':'.$password). "\r\n".
                   "Content-type: application/x-www-form-urlencoded\r\n",
      'timeout' => 10
  )));

  $response = file_get_contents(
    'https://api.46elks.com/a1/Me', false, $context );

  if (!strstr($http_response_header[0],"200 OK"))
    return $http_response_header[0];
  
  return json_decode($response);
}

// Settings
// Email to send notification to:
$to = "your-email@domain.com";
// Limit for sendning alert message:
$limit = 1000;

// Check balance
$accountdata =  checkBalance();

// Convert from credits into SEK:
$balance = $accountdata->balance/10000;

// Check if below limit.
if ($balance < $limit){
	// Send notification email, you could also send a reminder SMS, see send-sms.php.
  // If you don't recieve an email, you might need to use another email library such as PHPMailer where you can add your user/pass.
  // Email providers may block outgoing emails from non-authenticated requests to prevent spam.

	print mail (
	$to, 
	"46elks account Balance low" , 
	"Your account has a balance of: ".$balance." ".$accountdata->currency." to add more credits visit https://dashboard.46elks.com/");
}

?>

