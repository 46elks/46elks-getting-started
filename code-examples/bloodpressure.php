/*
 * This PHP script is designed to handle 2-way SMS communication for tracking health data using the 46elks API.
 * Patients can send SMS messages with specific keywords (e.g., "blood pressure", "glucose", or "weight") to trigger 
 * a request for their health readings. The script then processes their responses and logs the data in separate 
 * text files based on the type of reading.
 *
 * The workflow is as follows:
 * 1. A patient sends a keyword ("blood pressure", "glucose", or "weight") to the designated phone number (+46701234567).
 * 2. The script sends a follow-up SMS requesting the appropriate reading (e.g., blood pressure reading).
 * 3. The patient replies with the reading (e.g., "120/80" for blood pressure).
 * 4. The script logs the reading in a corresponding text file and sends a confirmation SMS back to the patient.
 *
 * The data types tracked include:
 * - Blood Pressure: Logged in "blood_pressure_log.txt".
 * - Glucose Level: Logged in "glucose_log.txt".
 * - Weight (in kg): Logged in "weight_log.txt".
 *
 * Each reading is stored along with the patient's phone number and a timestamp.
 */


<?php
 
// Function to send SMS using 46elks API
function sendSMS($sms) {
    // Set your 46elks API username and password
    $username = 'xxxxx';
    $password = 'xxxxx';
 
    // Prepare the HTTP context for sending the SMS request
    $context = stream_context_create(array(
        'http' => array(
            'method' => 'POST',
            'header'  => "Authorization: Basic " .
                         base64_encode($username.':'.$password) . "\r\n" .
                         "Content-type: application/x-www-form-urlencoded\r\n",
            'content' => http_build_query($sms),  // Converts array to URL-encoded query string
            'timeout' => 5  // Timeout in seconds
    )));
 
    // Send the request to 46elks SMS API and return response
    return false !== file_get_contents('https://api.46elks.com/a1/SMS', false, $context);
}
 
// This function logs data in a text file (blood pressure, glucose, weight)
function logData($from, $dataType, $message) {
    // Define the log file name and path based on the data type
    $logFile = $dataType . '_log.txt';  // This will create separate log files for each data type (blood pressure, glucose, weight)
 
    // Format the log entry with the sender's phone number, data (e.g., reading), and timestamp
    $logEntry = "From: $from - $dataType: $message - Date: " . date('Y-m-d H:i:s') . "\n";
 
    // Append the log entry to the log file (or create the file if it doesn't exist)
    file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
}
 
// Check if the SMS request contains the 'from' and 'message' keys
if (isset($_POST['from']) && isset($_POST['message'])) {
    $from = $_POST['from'];  // The sender's phone number
    $message = strtolower(trim($_POST['message']));  // Convert the message content to lowercase and trim it
 
    // Step 1: Check for keywords ("blood pressure", "glucose", "weight") and respond accordingly
    if ($message == "blood pressure") {
        // Request the user's blood pressure reading
        sendSMS(array(
            'from' => '+xxxxxxxxxxx',  // Your service number
            'to' => $from,  // The number that sent the "blood pressure" message
            'message' => 'Please reply with your blood pressure reading (e.g., 120/80).'  // Follow-up message
        ));
    } elseif ($message == "glucose") {
        // Request the user's glucose level
        sendSMS(array(
            'from' => '+xxxxxxxxxxx',
            'to' => $from,
            'message' => 'Please reply with your glucose level (e.g., 5.4 mmol/L).'  // Follow-up message for glucose
        ));
    } elseif ($message == "weight") {
        // Request the user's weight
        sendSMS(array(
            'from' => '+xxxxxxxxxxx',
            'to' => $from,
            'message' => 'Please reply with your weight in kilograms (e.g., 70 kg).'  // Follow-up message for weight
        ));
    }
    // Step 2: Check if the message contains valid readings and log them
    // Blood Pressure Format: "120/80"
    elseif (preg_match("/^\d{2,3}\/\d{2,3}$/", $message)) {
        // Log the blood pressure reading
        logData($from, 'blood pressure', $message);
 
        // Send a confirmation SMS to the user acknowledging the blood pressure reading
        sendSMS(array(
            'from' => '+xxxxxxxxxxx',
            'to' => $from,
            'message' => 'Thank you! Your blood pressure reading has been recorded.'
        ));
    }
    // Glucose Format: "X.X mmol/L" (with optional space)
    elseif (preg_match("/^\d{1,2}\.\d\s?mmol\/L$/", $message)) {
        // Log the glucose reading
        logData($from, 'glucose', $message);
 
        // Send a confirmation SMS to the user acknowledging the glucose reading
        sendSMS(array(
            'from' => '+xxxxxxxxxxx',
            'to' => $from,
            'message' => 'Thank you! Your glucose level has been recorded.'
        ));
    }
    // Weight Format: "XX kg" (with optional space)
    elseif (preg_match("/^\d{2,3}\s?kg$/", $message)) {
        // Log the weight reading
        logData($from, 'weight', $message);
 
        // Send a confirmation SMS to the user acknowledging the weight reading
        sendSMS(array(
            'from' => '+xxxxxxxxxxx',
            'to' => $from,
            'message' => 'Thank you! Your weight has been recorded.'
        ));
    }
    // Optional: Handle invalid responses or unexpected messages
    else {
        sendSMS(array(
            'from' => '+xxxxxxxxxxx',
            'to' => $from,
            'message' => 'Invalid response. Please reply with your blood pressure (e.g., 120/80), glucose (e.g., 5.4 mmol/L), or weight (e.g., 70 kg).'
        ));
    }
} else {
    // Log error if 'from' or 'message' keys are missing (for debugging purposes)
    error_log('Missing "from" or "message" in POST data.');
}
 
?>
