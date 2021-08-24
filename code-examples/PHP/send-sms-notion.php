<?php 

/**
 * 
 * Send SMS from Notion with 46elks
 * 
 * This script will get a list of phone numbers from a Notion database and send an SMS to each number. 
 * The SMS contents will be retrieved from a Notion paragraf block.
 * 
 * All about Notions API can be found at https://developers.notion.com.
 * 
 * Run the script with: http://your-domain.com/send-sms-notion.php?database_id=your_database_id&message_id=your_message_id
 * 
 * Contact help@46elks.com with any questions about this script.
 * 
 */

// Ensure the right input is provided
if (!isset($_GET["database_id"]) 
  || !isset($_GET["message_id"]) 
  || $_GET["database_id"] == "" 
  || $_GET["message_id"] == "" ) :

  exit("You must provide both database_id and message_id in your query string");
endif;

// Settings
$notion_api_key     = "";                   // Your Notion integration token (https://developers.notion.com/docs/getting-started#step-1-create-an-integration)
$notion_api_version = "2021-08-16";         // Don't change this unless you know what you are doing. More about verisoning at https://developers.notion.com/reference/versioning
$database_id        = $_GET["database_id"]; // Notion database id
$message_id         = $_GET["message_id"];  // Notion block id
$from               = "";                   // Your name or phone number 
$elks_api_user      = "";                   // Your 46elks API username
$elks_api_pass      = "";                   // Your 46elks API password


/* --------------------------------------------------------
 -------------------------------------------------------- */

/**
 * 
 * Start the execution
 * 
 */

// Get message from Notion block
$message = get_message($message_id);
if($message == "") exit("No message found.");

// Get numbers from Notion database
$numbers = get_numbers($database_id);
if(empty($numbers)) exit("No numbers found.");

// Send SMS to numbers via 46elks
send_sms_to_numbers($numbers, $message);


/* --------------------------------------------------------
 -------------------------------------------------------- */

/**
 *
 *  Get all numbers from your database in notion
 * 
 * @param $database_id
 * - string
 * - required
 *  
 * @return object
 * 
 * @link https://developers.notion.com/reference/post-database-query
 * 
 */

function get_numbers($database_id) {

  $endpoint = 'https://api.notion.com/v1/databases/'.$database_id."/query";
  
  // Filter when querying the database 
  // Get only the fields where the "Phone number" property is not empty
  $data = [
    "filter" => [
      "property" => "Phone number",
      "text" => [
        "is_not_empty" => true
      ]
    ]
  ];

  // Call Notion API and store and return the results
  $response = post_notion_api($endpoint, $data);
  return $response->results;

}


/* --------------------------------------------------------
 -------------------------------------------------------- */

/**
 * 
 * Traverse the list with numbers and send an SMS to each one
 * 
 * @param $numbers
 * - array
 * - required
 * 
 * @param $message 
 * - string
 * - required
 * 
 * @return string/array
 * - if successful, return string with success message
 * - if unsuccessful, return array with error messages
 * 
 */

function send_sms_to_numbers($numbers, $message){

  global $from;

  // To store any errors that may occur
  $errors = [];

  // Loop through results and send SMS to each number
  foreach ($numbers as $key => $value) :

    $number   = $value->properties->{'Phone number'}->rich_text[0]->plain_text;
    $sms = [
      "from"    => $from,
      "to"      => $number,
      "message" => $message,
    ];

    // Send SMS to current number
    $response = send_sms($sms);
  
    // Check for errors when sending SMS
    if($response !== true) :
      $name = $value->properties->Name->title[0]->plain_text;
      $data = [
        "message" => "Failed to send sms to " .$name."(".$number.")",
        "error" => $response
      ];
      array_push($errors, $data);
    endif;
    
  endforeach;

  if (!empty($errors)) :
    echo "An error occurred for the following numbers when sending the SMS";
    echo "<pre>";
    print_r($errors);
    echo "</pre>";
  else :
    echo "SMS successfuly sent to all people on the list";
  endif;

}


/* --------------------------------------------------------
 -------------------------------------------------------- */

/**
 * 
 * Get SMS contents from Notion block 
 * 
 * @param block_id
 * - string
 * - required
 * 
 * @link https://developers.notion.com/reference/retrieve-a-block
 * 
 */

function get_message($block_id) {

  $endpoint   = 'https://api.notion.com/v1/blocks/'.$block_id;
  $result     = get_notion_api($endpoint);
  return $result->paragraph->text[0]->plain_text;

}


/* --------------------------------------------------------
 -------------------------------------------------------- */

/**
 * 
 * Shortcut for making a GET request to the Notion API
 * 
 */

function get_notion_api($endpoint, $data = []){
  return notion_api($endpoint, $data, "GET");
}


/* --------------------------------------------------------
 -------------------------------------------------------- */

/**
 * 
 * Shortcut for making a POST request to the Notion API
 * 
 */

function post_notion_api($endpoint, $data = []){
  return notion_api($endpoint, $data, "POST");
}


/* --------------------------------------------------------
 -------------------------------------------------------- */

/**
 * 
 * Call the Notion API
 * 
 * @param $endpoint
 * - string
 * - required
 * 
 * @param $data
 *  - array
 *  - optional
 * 
 * @param $method
 * - string
 * - optional (default GET)
 * 
 * @return object
 * 
 */

function notion_api($endpoint, $data = [], $method = "GET"){

  global $notion_api_key;
  global $notion_api_version;

  $context = stream_context_create(array(
    'http' => array(
      'ignore_errors' => true,
      'method' => $method,
      'header'  => "Authorization: Bearer ". $notion_api_key . "\r\n".
                   "Notion-Version: ".$notion_api_version."\r\n".
                   "Content-Type: application/json\r\n",
      'content' => json_encode($data),
      'timeout' => 10
  )));

  $response = file_get_contents($endpoint, false, $context );

  if (!strstr($http_response_header[0],"200 OK")) :
    return $response;
  else:
    return json_decode($response);
  endif;
  
}


/* --------------------------------------------------------
 -------------------------------------------------------- */

/**
 * 
 * Send SMS with 46elks  
 * 
 * @param sms 
 * - array
 * - required
 * 
 * @return boolean/array
 * - if successful, return true
 * - if unsuccessful, return array with errors
 * 
 * @link https://46elks.com/docs/send-sms
 * 
 */

function send_sms($sms) {

  global $elks_api_user;
  global $elks_api_pass;

  $context = stream_context_create(array(
    'http' => array(
      'ignore_errors' => true,
      'method' => 'POST',
      'header'  => 'Authorization: Basic '.
                  base64_encode($elks_api_user.':'.$elks_api_pass). "\r\n".
                  "Content-type: application/x-www-form-urlencoded\r\n",
      'content' => http_build_query($sms),
      'timeout' => 10
  )));
  $response = file_get_contents("https://api.46elks.com/a1/sms",
    false, $context);

  if (!strstr($http_response_header[0],"200 OK")) :
    return $response;
  else: 
    return true;
  endif;
}

?>