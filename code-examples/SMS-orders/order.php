<?php 

// =========================
// Please note:
// This script lacks sufficient error checking and SQL-injection prevention.
// If used in production, you need to add it.
// =========================

// Exit if this is not a POST-request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') error("Not a POST request");

// Exit if there is no data sent
if (!isset($_POST) || empty($_POST)) error("No data provided");

// Connect to DB
$host 				= "localhost";
$username 		= "root";
$password 		= "root";
$dbname 			= "sms-orders";

// Create DB connection
$conn = mysqli_connect($host, $username, $password, $dbname);

// Check DB connection and exit if error
if (!$conn) error("DB connection failed: " . mysqli_connect_error());


/* ------------------------------------------------
 ------------------------------------------------ */

 /**
  * 
  * Add new order to database
  * 
  * @param $user (string)
  * @param $message (string)
  * 
  * @return boolean
  * 
  */

function add_order($user, $message){
	global $conn;
	$sql = "INSERT INTO orders (user, message) VALUES ('$user','$message')";

	if (mysqli_query($conn, $sql) === TRUE) :
	  create_log("New record created successfully");
	  return true;
	else :
	  create_log("Error: " . $sql . "<br>" . $conn->error);
	  return false;
	endif;

}


/* ------------------------------------------------
 ------------------------------------------------ */

 /**
  * 
  * Check if user has made an order previously
  * @param $user (string)
  * 
  * @return boolean
  * 
  */

function is_first_order($user){
	global $conn;
	$sql 		= "SELECT id FROM orders WHERE user = '$user' LIMIT 1";
	$result = mysqli_query($conn, $sql);
	return !(mysqli_num_rows($result) > 0);
}


/* ------------------------------------------------
 ------------------------------------------------ */

 /**
	* 
	* Saves an error message in acutom log file and ends the script
	* 
	* Please note:
	* If you are providing an error message in functions like die() or exit() 
	* the message will be sent as a respons to the user (i.e the user 
	* will get an SMS with the error message).
	* To avoid this behaviour, use this custom error-function to save errors 
	* in a log file.
	* 
	* @param $message (string)
	*
	* @return nothing
	* 
	*/

function error($message){
	create_log($message);
	http_response_code(204);
	exit();
}


/* ------------------------------------------------
 ------------------------------------------------ */

 /**
  * 
  * Create custom log-files
  * 
  * @param $data (string or array)
  * 
  * @return nothing
  * 
  */

function create_log($data){

	// Define log file
	$date 					= date("Y-m-d");
	$file_path 			= __DIR__."/".$date."-log.txt";
	$file_contents 	= "";
	
	// Check if log file exists and save its contents
	if (file_exists($file_path)) :
		$contents 			= file_get_contents($file_path);
		$file_contents 	= $contents.PHP_EOL;
	endif;

	// If data is array, loop through the entries
	if(is_array($data)) :
		foreach ($data as $key => $value) :
			$file_contents .= $value.PHP_EOL;
		endforeach;
	else:
		$file_contents .= $data.PHP_EOL;
	endif;

	// Add timestamp to the log entry
	$file_contents .= date("now").PHP_EOL.$file_contents;

	// Save data to log file
	file_put_contents($file_path, $file_contents);
}

http_response_code(200);

// If you need to troubleshoot your script,
// uncomment the line below to see what data is received
// create_log($_POST);

// Get data from POST request and extract its contents.
// The recieved parameters are described in the docs at https://46elks.se/docs/receive-sms
extract($_POST);

// Check user input and respond accordingly.
// Currently, this checks for an exact match with the user input,
// you could of course use regex to parse the input if needed.
switch (strtolower(trim($message))) {
	case 'cucumber':
	 	$response = "We'll send you a shiny, green cucumber 🥒";
		break;
	case 'mushroom':
	 	$response = "We'll send you our best (non-poisonous) mushrooms 🍄";
		break;
	case 'orange':
	 	$response = "Vitamin C is important. We'll send you a bag of oranges 🍊";
		break;
	default:
	 	echo "Sorry, we did not understand your request 🤔 Please try again!";
	 	exit();
		break;
}

// If this is the first time the user orders something,
// respond with a special message
if (is_first_order($from)) $response = "Thank you for your first order! We'll add a special gift for you  🎉";

// Add order to database
// If unsuccessful, inform the user
if (!add_order($from,$message)) $response = "Sorry, something went wrong with your order! Please contact our support 📞";

// Send respond as an SMS to the user
// (it's done automatically with this line)
echo $response;

// Close DB connection
mysqli_close($conn);

?>