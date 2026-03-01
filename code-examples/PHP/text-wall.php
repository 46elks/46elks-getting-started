<?php
// SMS text wall - Sample for the 46elks cloud telephony platform
//
// Prerequisites:
// 1) Ensure your web server has write permissions for the directory where this file is stored.
// 2) Obtain your unique API key for added security and set it below in $key.
// 3) Set up your SMS URL to: http://yourserver.com/textwall.php?key=your_unique_key
// 4) This example assumes incoming POST requests with parameters 'from' and 'message' from 46elks.

// Configuration
$key = 'uzh789j123';  // Change this to a secure, unique key for URL-based authentication
$filename = __DIR__ . '/' . date('Ym') . '.txt';  // Saves logs in a file named by the current month (e.g., 202310.txt)

// Handle incoming SMS and save to file
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['from'], $_POST['message'])) {
    // Authentication to secure the endpoint
    if (!isset($_GET['key']) || $_GET['key'] !== $key) {
        die("Unauthorized access.");  // Stop if the key is incorrect
    }

    // Sanitize inputs to prevent injection or XSS attacks
    $from = filter_var($_POST['from'], FILTER_SANITIZE_STRING);
    $message = base64_encode(filter_var($_POST['message'], FILTER_SANITIZE_STRING));  // Encode the message for safe storage

    // Write SMS to log file
    $line = $from . ' ' . $message . "\n";
    if (file_put_contents($filename, $line, FILE_APPEND) === false) {
        die("Error writing to file.");  // Display error if the file cannot be written
    }

    die("Message logged successfully.");  // Exit after successfully logging the message
}

// Load and display stored messages for the current month
$lines = file_exists($filename) ? file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) : [];  // Get lines if the file exists
$lines = array_reverse($lines);  // Reverse the lines to display the most recent messages first

// Map specific phone numbers to person names for readability
$peoplenames = [
    '+46704508449' => 'Johannes L',
    '+46701234124' => 'Mike Douglas',
];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Text Wall - 46elks Example</title>
    <style type="text/css">
        /* Basic styling for text wall display */
        p, h1 {
            font-family: Verdana, sans-serif;
            text-align: center;
            width: 95%;
        }
        p {
            padding: 10px 20px;
            font-size: 20px;
        }
        .footer {
            font-size: 14px;
            text-align: center;
            margin-top: 20px;
            color: #888;
        }
    </style>
    <meta http-equiv="refresh" content="5">  <!-- Refresh every 5 seconds to show new messages -->
</head>
<body>

<h1>SMS Wall for +467XXXXXXXXXX</h1>

<!-- Display the last 20 messages stored in the file -->
<?php foreach (array_slice($lines, 0, 20) as $line): ?>
    <?php
        // Parse each line to separate phone number and message
        list($from, $text) = explode(' ', $line, 2);
        $message = htmlspecialchars(base64_decode($text));  // Decode and escape message for safe display

        // Display sender name if available, otherwise show masked phone number with only the last 4 digits
        $from_display = $peoplenames[$from] ?? "+XXXXXXX" . substr($from, -4);
    ?>
    <p>"<?= $message ?>" <em>sent from <?= $from_display ?></em></p>
<?php endforeach; ?>

<!-- Footer link to GitHub for the code example --> 
<div class="footer">
    <p>Curious on how we built this? <a href="https://github.com/46elks/46elks-getting-started/blob/master/code-examples/PHP/text-wall.php" target="_blank">Take a look here!</a></p>
</div>

</body>
</html>
