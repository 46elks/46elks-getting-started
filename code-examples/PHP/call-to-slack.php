<?php

// Slack webhook URL
$slack_url = 'https://hooks.slack.com/services/XXXXXXXXX/XXXXXXXXX/XXXXXXXXXXXXXXXXXXXXXXXX';

// Function to send a message to Slack
function slack($txt, $slack_url) {
    $url = $slack_url;
    $ch = curl_init($url);
    
    // Prepare data
    $data = array(
        'username' => 'incoming_call',
        'text' => $txt,
    );
    
    // Encode data as JSON
    $payload = json_encode($data);

    // Set cURL options
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    // Execute cURL and close the connection
    $result = curl_exec($ch);
    curl_close($ch);
}

// Get incoming caller information from POST data
$incoming_caller = $_POST['from'];

// Send incoming caller information to Slack
slack('incoming_caller: ' . $incoming_caller, $slack_url);

// Output JSON response
echo json_encode(array(
    "connect" => "FIRST NUMBER",
    "timeout" => 20,
    "failed" => array(
        "connect" => "SECOND NUMBER",
        "timeout" => 20,
        "failed" => array(
            "connect" => "THIRD NUMBER",
            "timeout" => 20
        )
    )
));

?>
