<?
// Example to get cost history from the 46elks service
// Change $username, $password and the mobile number to send to


// Set your 46elks API username and API password here
// You can find them at https://dashboard.46elks.com/
$username = '';
$password = '';

// Get the inital SMS list:
$response = file_get_contents('https://'.$username.':'.$password.'@api.46elks.com/a1/SMS');

// Check that the request was ok:
if (!strstr($http_response_header[0],"200 OK"))
    die($http_response_header[0]);

// Decode request:
$decodedlist = json_decode($response);
$list = $decodedlist->data;

// Prepare arrays for output:
$costmonth = array();
$numbermonth = array();

// Loop until all SMS are receaved or max 100 000 messages.
$max = 1000;
while(true){

	if(isset($decodedlist->next) == FALSE)
	{
	
		if($max < 0)
		{
			break;
		}
		
		$max = $max - 1;
		$response = file_get_contents('https://'.$username.':'.$password.'@api.46elks.com/a1/SMS?start='.$decodedlist->next);
		if (!strstr($http_response_header[0],"200 OK"))
    		die($http_response_header[0]);
		$decodedlist = json_decode($response);
		$list = array_merge($list, $decodedlist->data);
	}
	
	else
	{
		break;
	}
}

// Read all items.
foreach($list as $sms)
{
	if(isset($sms->cost))
	{
		$month = substr($sms->created, 0, 7);
		$numstart = substr($sms->to, 0, 4);
		
		if(isset($costmounth[$month]) == FALSE)
			$costmounth[$month] = 0;
		$costmonth[$month] = $costmonth[$month] + $sms->cost;
		
		if(isset($numbermonth[$month]) == FALSE)
			$numbermonth[$month] = array();
		if(isset($numbermonth[$month][$numstart]) == FALSE)
			$numbermonth[$month][$numstart] = 0;
		$numbermonth[$month][$numstart] = $numbermonth[$month][$numstart] + 1;
	}
}

// Print the data to the user:
print "Month\tCost\tNumber\tAmount\n";
foreach($costmonth as $month => $cost)
{
	$cost = $cost/10000;
	print $month."\t".$cost."\n";
	foreach($numbermonth[$month] as $number => $amount )
	{
		print "\t\t". $number."*\t".$amount."\n";
	}
}


?>

