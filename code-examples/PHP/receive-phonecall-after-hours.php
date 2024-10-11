<?php
/**
 * This PHP script handles incoming calls to a business and determines whether the business is open
 * or closed based on the day of the week and the time of day. If the business is open, the call is
 * forwarded to the business owner. If the business is closed, the caller is notified with an audio message
 * and an SMS is sent to the business owner with the caller's details.
 *
 * Prerequisites:
 * - A web server running PHP (with cURL enabled for API requests).
 * - API credentials (username and password) for sending SMS via the 46elks API.
 * - Audio files for the "open" and "closed" messages hosted on a publicly accessible URL.
 * - A POST request with the caller's phone number (`from`) sent to this script.
 *
 * Flow:
 * 1. The script receives a POST request with the caller's phone number.
 * 2. It checks if the current time is between 09:00 and 17:00 from Monday to Friday.
 * 3. If the business is open, the call is connected to the business owner and the "open" message is played.
 * 4. If the business is closed, an SMS is sent to the business owner with the caller's number and a "closed" message is played.
 */

define('PHONE_NUMBER', 'business owner number'); // Business owner's phone number
define('OPEN_FILE', 'https://example.com/we-are-open.mp3'); // Open message file URL
define('CLOSED_FILE', 'https://example.com/we-are-closed.mp3'); // Closed message file URL

/**
 * Sends an SMS notification via the 46elks API.
 *
 * @param array $sms - SMS data (to, from, message)
 * @return mixed - API response or error message
 */
function sendSMS($sms) {
    $username = "username"; // 46elks API username
    $password = "password"; // 46elks API password
    
    // Create a stream context for the HTTP POST request with authorization and content-type headers
    $context = stream_context_create(array(
        'http' => array(
            'method' => 'POST',
            'header' => 'Authorization: Basic ' . base64_encode($username . ':' . $password) . "\r\n" .
                        "Content-type: application/x-www-form-urlencoded\r\n",
            'content' => http_build_query($sms), // Convert SMS data to URL-encoded format
            'timeout' => 10 // Timeout after 10 seconds
        )
    ));
    
    // Send the request to the 46elks API and capture the response
    $response = file_get_contents("https://api.46elks.com/a1/sms", false, $context);

    // If the response is not 200 OK, return the error code
    if (!strstr($http_response_header[0], "200 OK"))
        return $http_response_header[0];
    
    return $response; // Return the API response
}

/**
 * Handles the incoming call by checking the time and performing the appropriate action (open or closed).
 *
 * @param string $callerid - The phone number of the caller
 */
function incomingCalls($callerid) {
    // Set the timezone to 'Europe/Stockholm'
    $tz_obj = new DateTimeZone('Europe/Stockholm');
    $today = new DateTime("now", $tz_obj); // Get the current date and time
    
    // Get the current weekday (1 = Monday, 7 = Sunday) and hour (0-23 format)
    $weekday = intval($today->format('N')); // N returns the day of the week as a number (1 = Monday)
    $hour = intval($today->format('H')); // H returns the hour in 24-hour format
    
    // Check if the day is Monday to Friday (1-5) and time is between 09:00 and 17:00
    if ($weekday >= 1 && $weekday <= 5) {
        if ($hour >= 9 && $hour <= 17) {
            // If the business is open, play the "open" message and connect the call to the business owner
            echo json_encode([
				'play' => OPEN_FILE, // Play the "we are open" audio message
                'next' => ([
					'connect' => PHONE_NUMBER // Connect the call to the business owner's phone number
				])
            ]);
            die(); // End the script after responding
        }
    }
    
    // If outside business hours, prepare an SMS to notify the business owner of the missed call
    $sms = array(
        "from" => "AfterHours",   // The sender name (up to 11 characters)
        "to" => PHONE_NUMBER,  // The business owner's phone number
        "message" => "You received a call after hours from: " . $callerid, // SMS content
    );
    
    // Send the SMS notification to the business owner
    sendSMS($sms);
    
    // Play the "closed" message to the caller
    echo json_encode([
        'play' => CLOSED_FILE // Play the "we are closed" audio message
    ]);
    die(); // End the script after responding
}

// Main logic: Check if the request is a POST request (indicating an incoming call)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the caller's phone number from the POST data
    $caller = $_POST['from'];

    // Call the function to handle the incoming call
    incomingCalls($caller);
}
?>
