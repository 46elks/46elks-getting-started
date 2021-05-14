<?php
	
	// ==============================
	// Your api username and password
	// ==============================
	$username = "API_USERNAME"; 
	$password = "API_PASSWORD";

	// ==============================
	
	if (isset($_POST) && !empty($_POST)) {

		function sendcalls($calls) {

		$context = stream_context_create(array(
			'http' => array(
				'method' => 'POST',
				'header'  => 'Authorization: Basic '.
				base64_encode($username.':'.$password). "\r\n".
				"Content-type: application/x-www-form-urlencoded\r\n",
				'content' => http_build_query($calls),
				'timeout' => 10
			)));
		$response = file_get_contents("https://api.46elks.com/a1/calls",
			false, $context);

		if (!strstr($http_response_header[0],"200 OK"))
			return $http_response_header[0];
		return $response;
	}

	extract($_POST);

	$calls = array(
		"from" => $from,
		"to" => $to,
		"voice_start" => $voice_start
	);

	sendcalls($calls);
	
}
?>