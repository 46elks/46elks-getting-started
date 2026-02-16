/*
 * SMS Health Data Tracking System with AI-Powered Responses
 * 
 * This PHP script provides a comprehensive 2-way SMS communication system for tracking health data
 * using the 46elks API and Claude Sonnet 4.5 AI. It combines simple keyword-based tracking with
 * intelligent conversational AI capabilities for health information and support.
 * 
 * CORE FEATURES:
 * ================
 * 1. KEYWORD-BASED HEALTH TRACKING:
 *    - Patients send keywords ("blood pressure", "glucose", or "weight") to trigger data collection
 *    - System sends follow-up SMS requesting the appropriate reading
 *    - Patient replies with their reading (e.g., "120/80" for blood pressure)
 *    - Reading is logged to file with timestamp and confirmation SMS sent
 * 
 * 2. AI-POWERED HEALTH ASSISTANT:
 *    - Natural conversation about health topics using Claude Sonnet 4.5
 *    - Context-aware responses that maintain conversation history per user
 *    - Emergency detection and crisis resource provision
 *    - General health information and wellness guidance
 * 
 * WORKFLOW:
 * =========
 * TRACKING MODE:
 * Step 1: Patient texts keyword → "blood pressure" to +46701234567
 * Step 2: System replies → "Please send your blood pressure reading (e.g., 120/80)"
 * Step 3: Patient replies → "125/82"
 * Step 4: System logs data and confirms → "Blood pressure 125/82 recorded at 2025-11-07 14:30"
 * 
 * AI ASSISTANT MODE:
 * Step 1: Patient asks → "What should I do about my headache?"
 * Step 2: Claude analyzes and responds with helpful information
 * Step 3: Conversation continues with maintained context
 * 
 * DATA STORAGE:
 * =============
 * - Blood Pressure: stored in "data/blood_pressure_log.txt"
 * - Glucose Level: stored in "data/glucose_log.txt"
 * - Weight (kg): stored in "data/weight_log.txt"
 * - Conversation History: stored in "data/conversations.json"
 * - Session State: stored in "data/sessions.json"
 * 
 * Each log entry includes: Phone Number | Reading | Timestamp
 * Format: +46701234567 | 120/80 | 2025-11-07 14:30:25
 * 
 * CONFIGURATION:
 * ==============
 * Set these constants before deployment:
 * - ELKS_USERNAME: Your 46elks API username
 * - ELKS_PASSWORD: Your 46elks API password
 * - FROM_NUMBER: Your 46elks phone number (international format)
 * - ANTHROPIC_API_KEY: Your Claude API key
 * 
 * WEBHOOK SETUP:
 * ==============
 * 1. Upload this file to your web server (HTTPS required)
 * 2. Configure 46elks webhook to: https://yourdomain.com/sms_health_tracker_complete.php
 * 3. Ensure data/ directory exists and is writable (chmod 755)
 * 
 * SECURITY NOTES:
 * ===============
 * - Store credentials in environment variables for production
 * - Implement proper authentication/authorization
 * - Use HTTPS for all communications
 * - Comply with HIPAA/GDPR as applicable
 * - This is demonstration software - add appropriate safeguards for production
 * 
 * DISCLAIMER:
 * ===========
 * This is demonstration software for educational purposes only.
 * NOT a medical device. NOT a substitute for professional medical care.
 * Always encourage users to consult healthcare providers.
 * Use at your own risk. Authors assume no liability.
 */

<?php

// ============================================================================
// CONFIGURATION
// ============================================================================

// 46elks API Configuration
define('ELKS_USERNAME', 'your_46elks_username');  // Replace with your 46elks username
define('ELKS_PASSWORD', 'your_46elks_password');  // Replace with your 46elks password
define('FROM_NUMBER', '+46701234567');             // Replace with your 46elks number

// Anthropic Claude API Configuration
define('ANTHROPIC_API_KEY', 'your_anthropic_api_key');  // Replace with your Claude API key
define('CLAUDE_MODEL', 'claude-sonnet-4-5-20250929');
define('CLAUDE_API_URL', 'https://api.anthropic.com/v1/messages');

// Data Storage Configuration
define('DATA_DIR', __DIR__ . '/data/');
define('BP_LOG_FILE', DATA_DIR . 'blood_pressure_log.txt');
define('GLUCOSE_LOG_FILE', DATA_DIR . 'glucose_log.txt');
define('WEIGHT_LOG_FILE', DATA_DIR . 'weight_log.txt');
define('SESSION_FILE', DATA_DIR . 'sessions.json');
define('CONVERSATION_FILE', DATA_DIR . 'conversations.json');

// Session timeout (30 minutes)
define('SESSION_TIMEOUT', 1800);

// ============================================================================
// SYSTEM INITIALIZATION
// ============================================================================

// Create data directory if it doesn't exist
if (!file_exists(DATA_DIR)) {
    mkdir(DATA_DIR, 0755, true);
}

// Initialize log files if they don't exist
foreach ([BP_LOG_FILE, GLUCOSE_LOG_FILE, WEIGHT_LOG_FILE] as $file) {
    if (!file_exists($file)) {
        file_put_contents($file, "Phone Number | Reading | Timestamp\n" . str_repeat("-", 70) . "\n");
    }
}

// Error logging
ini_set('log_errors', 1);
ini_set('error_log', DATA_DIR . 'error.log');

// ============================================================================
// CLAUDE AI SYSTEM PROMPT
// ============================================================================

$SYSTEM_PROMPT = <<<EOT
You are a helpful healthcare information assistant that communicates via SMS. Your role is to:

1. Provide general health information and wellness tips
2. Help users understand when to seek professional medical care
3. Answer questions about common medications and their proper use
4. Provide appointment reminders and medication schedules
5. Offer mental health support and crisis resources when needed

CRITICAL GUIDELINES:
- Always include a disclaimer that you're not a substitute for professional medical advice
- For serious symptoms (chest pain, difficulty breathing, severe bleeding, etc.), immediately advise calling emergency services
- Never diagnose conditions or prescribe medications
- Be empathetic, clear, and concise (SMS format)
- Keep responses under 160 characters when possible, or break into clear segments
- If asked about mental health crisis, provide crisis hotline numbers
- Always encourage users to consult their healthcare provider for personalized advice

CRISIS RESOURCES:
- Emergency: 112 (EU) / 911 (US)
- Mental Health Crisis (Sweden): 90101 (Mind)
- Suicide Prevention (Sweden): 020-22 00 60 (BRIS)

Be warm, supportive, and always prioritize user safety. Remember you're communicating via SMS.
EOT;

// ============================================================================
// HELPER FUNCTIONS - DATA MANAGEMENT
// ============================================================================

/**
 * Load sessions from file
 * 
 * @return array Sessions array
 */
function loadSessions() {
    if (!file_exists(SESSION_FILE)) {
        return [];
    }
    $content = file_get_contents(SESSION_FILE);
    return json_decode($content, true) ?: [];
}

/**
 * Save sessions to file
 * 
 * @param array $sessions Sessions to save
 */
function saveSessions($sessions) {
    file_put_contents(SESSION_FILE, json_encode($sessions, JSON_PRETTY_PRINT));
}

/**
 * Load conversation history from file
 * 
 * @return array Conversations array
 */
function loadConversations() {
    if (!file_exists(CONVERSATION_FILE)) {
        return [];
    }
    $content = file_get_contents(CONVERSATION_FILE);
    return json_decode($content, true) ?: [];
}

/**
 * Save conversation history to file
 * 
 * @param array $conversations Conversations to save
 */
function saveConversations($conversations) {
    file_put_contents(CONVERSATION_FILE, json_encode($conversations, JSON_PRETTY_PRINT));
}

/**
 * Clean up expired sessions
 * 
 * @param array $sessions Sessions array
 * @return array Cleaned sessions
 */
function cleanExpiredSessions($sessions) {
    $now = time();
    foreach ($sessions as $phone => $session) {
        if (($now - $session['timestamp']) > SESSION_TIMEOUT) {
            unset($sessions[$phone]);
        }
    }
    return $sessions;
}

/**
 * Get or create session for a phone number
 * 
 * @param string $phone Phone number
 * @param array $sessions Sessions array
 * @return array|null Session data
 */
function getSession($phone, &$sessions) {
    if (isset($sessions[$phone])) {
        $session = $sessions[$phone];
        // Check if session is expired
        if ((time() - $session['timestamp']) > SESSION_TIMEOUT) {
            unset($sessions[$phone]);
            return null;
        }
        return $session;
    }
    return null;
}

/**
 * Create new session
 * 
 * @param string $phone Phone number
 * @param string $type Session type (bp, glucose, weight)
 * @param array $sessions Sessions array
 */
function createSession($phone, $type, &$sessions) {
    $sessions[$phone] = [
        'type' => $type,
        'timestamp' => time()
    ];
}

/**
 * Delete session
 * 
 * @param string $phone Phone number
 * @param array $sessions Sessions array
 */
function deleteSession($phone, &$sessions) {
    unset($sessions[$phone]);
}

// ============================================================================
// HELPER FUNCTIONS - SMS COMMUNICATION
// ============================================================================

/**
 * Send SMS via 46elks API
 * 
 * @param string $to Recipient phone number
 * @param string $message Message content
 * @return bool Success status
 */
function sendSMS($to, $message) {
    $data = [
        'from' => FROM_NUMBER,
        'to' => $to,
        'message' => $message
    ];
    
    $ch = curl_init('https://api.46elks.com/a1/sms');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_USERPWD, ELKS_USERNAME . ':' . ELKS_PASSWORD);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode === 200) {
        error_log("SMS sent successfully to $to");
        return true;
    } else {
        error_log("Failed to send SMS to $to: HTTP $httpCode - $response");
        return false;
    }
}

// ============================================================================
// HELPER FUNCTIONS - DATA LOGGING
// ============================================================================

/**
 * Log health reading to file
 * 
 * @param string $phone Phone number
 * @param string $reading Reading value
 * @param string $type Reading type (bp, glucose, weight)
 * @return bool Success status
 */
function logReading($phone, $reading, $type) {
    $timestamp = date('Y-m-d H:i:s');
    $logLine = "$phone | $reading | $timestamp\n";
    
    $logFile = '';
    switch ($type) {
        case 'bp':
            $logFile = BP_LOG_FILE;
            break;
        case 'glucose':
            $logFile = GLUCOSE_LOG_FILE;
            break;
        case 'weight':
            $logFile = WEIGHT_LOG_FILE;
            break;
        default:
            return false;
    }
    
    $result = file_put_contents($logFile, $logLine, FILE_APPEND);
    
    if ($result !== false) {
        error_log("Logged $type reading for $phone: $reading");
        return true;
    } else {
        error_log("Failed to log $type reading for $phone");
        return false;
    }
}

// ============================================================================
// HELPER FUNCTIONS - DATA VALIDATION
// ============================================================================

/**
 * Validate blood pressure reading format
 * 
 * @param string $reading Blood pressure reading
 * @return bool Valid or not
 */
function isValidBloodPressure($reading) {
    return preg_match('/^\d{2,3}\/\d{2,3}$/', trim($reading));
}

/**
 * Validate glucose reading format
 * 
 * @param string $reading Glucose reading
 * @return bool Valid or not
 */
function isValidGlucose($reading) {
    $value = floatval($reading);
    return $value > 0 && $value < 50; // mmol/L range
}

/**
 * Validate weight reading format
 * 
 * @param string $reading Weight reading
 * @return bool Valid or not
 */
function isValidWeight($reading) {
    $value = floatval($reading);
    return $value > 20 && $value < 300; // kg range
}

// ============================================================================
// AI FUNCTIONS - CLAUDE INTEGRATION
// ============================================================================

/**
 * Get response from Claude AI
 * 
 * @param string $phone Phone number
 * @param string $userMessage User's message
 * @param array $conversations Conversations array
 * @return string Claude's response
 */
function getClaudeResponse($phone, $userMessage, &$conversations) {
    global $SYSTEM_PROMPT;
    
    // Initialize conversation if new
    if (!isset($conversations[$phone])) {
        $conversations[$phone] = [];
        error_log("New conversation started for $phone");
    }
    
    // Add user message to history
    $conversations[$phone][] = [
        'role' => 'user',
        'content' => $userMessage
    ];
    
    // Keep conversation history manageable (last 20 messages)
    if (count($conversations[$phone]) > 20) {
        $conversations[$phone] = array_slice($conversations[$phone], -20);
    }
    
    // Prepare API request
    $payload = [
        'model' => CLAUDE_MODEL,
        'max_tokens' => 500,
        'system' => $SYSTEM_PROMPT,
        'messages' => $conversations[$phone]
    ];
    
    // Make API call
    $ch = curl_init(CLAUDE_API_URL);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'x-api-key: ' . ANTHROPIC_API_KEY,
            'anthropic-version: 2023-06-01'
        ]
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($httpCode !== 200 || $error) {
        error_log("Claude API error: HTTP $httpCode - $error - $response");
        return "I'm experiencing technical difficulties. Please try again later or contact support.";
    }
    
    $responseData = json_decode($response, true);
    
    if (!isset($responseData['content'][0]['text'])) {
        error_log("Unexpected Claude API response format: $response");
        return "I'm having trouble processing your request. Please try again.";
    }
    
    $assistantMessage = $responseData['content'][0]['text'];
    
    // Add assistant response to history
    $conversations[$phone][] = [
        'role' => 'assistant',
        'content' => $assistantMessage
    ];
    
    error_log("Claude response generated for $phone");
    
    return $assistantMessage;
}

// ============================================================================
// MAIN REQUEST HANDLER
// ============================================================================

/**
 * Handle incoming SMS webhook from 46elks
 */
function handleIncomingSMS() {
    // Get incoming SMS data
    $from = $_POST['from'] ?? '';
    $message = trim($_POST['message'] ?? '');
    
    if (empty($from) || empty($message)) {
        error_log("Invalid incoming SMS: missing 'from' or 'message'");
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request']);
        return;
    }
    
    error_log("Incoming SMS from $from: $message");
    
    // Load sessions and conversations
    $sessions = loadSessions();
    $sessions = cleanExpiredSessions($sessions);
    $conversations = loadConversations();
    
    // Check if user has an active session
    $session = getSession($from, $sessions);
    
    if ($session) {
        // User is in a data collection session
        handleDataCollection($from, $message, $session, $sessions);
    } else {
        // Check if message is a trigger keyword
        $messageLower = strtolower($message);
        
        if (strpos($messageLower, 'blood pressure') !== false || strpos($messageLower, 'bp') !== false) {
            // Start blood pressure tracking session
            createSession($from, 'bp', $sessions);
            saveSessions($sessions);
            sendSMS($from, "Please send your blood pressure reading in the format: 120/80");
            
        } elseif (strpos($messageLower, 'glucose') !== false || strpos($messageLower, 'sugar') !== false) {
            // Start glucose tracking session
            createSession($from, 'glucose', $sessions);
            saveSessions($sessions);
            sendSMS($from, "Please send your blood glucose reading in mmol/L (e.g., 5.5)");
            
        } elseif (strpos($messageLower, 'weight') !== false) {
            // Start weight tracking session
            createSession($from, 'weight', $sessions);
            saveSessions($sessions);
            sendSMS($from, "Please send your weight in kilograms (e.g., 75.5)");
            
        } else {
            // Not a tracking keyword - use AI assistant
            handleAIConversation($from, $message, $conversations);
        }
    }
    
    // Respond with 200 OK to 46elks
    http_response_code(200);
    echo json_encode(['status' => 'ok']);
}

/**
 * Handle data collection session
 * 
 * @param string $phone Phone number
 * @param string $message User's message
 * @param array $session Session data
 * @param array $sessions All sessions
 */
function handleDataCollection($phone, $message, $session, &$sessions) {
    $type = $session['type'];
    $reading = trim($message);
    $isValid = false;
    $readingType = '';
    
    // Validate and log based on type
    switch ($type) {
        case 'bp':
            $isValid = isValidBloodPressure($reading);
            $readingType = 'Blood pressure';
            break;
        case 'glucose':
            $isValid = isValidGlucose($reading);
            $readingType = 'Glucose';
            break;
        case 'weight':
            $isValid = isValidWeight($reading);
            $readingType = 'Weight';
            break;
    }
    
    if ($isValid) {
        // Log the reading
        if (logReading($phone, $reading, $type)) {
            $timestamp = date('Y-m-d H:i');
            sendSMS($phone, "$readingType reading of $reading recorded at $timestamp. Thank you!");
        } else {
            sendSMS($phone, "Sorry, there was an error recording your reading. Please try again.");
        }
        
        // Delete session
        deleteSession($phone, $sessions);
        saveSessions($sessions);
        
    } else {
        // Invalid format
        $formatMsg = '';
        switch ($type) {
            case 'bp':
                $formatMsg = "Invalid format. Please send blood pressure as: 120/80";
                break;
            case 'glucose':
                $formatMsg = "Invalid format. Please send glucose level in mmol/L (e.g., 5.5)";
                break;
            case 'weight':
                $formatMsg = "Invalid format. Please send weight in kg (e.g., 75.5)";
                break;
        }
        sendSMS($phone, $formatMsg);
    }
}

/**
 * Handle AI conversation
 * 
 * @param string $phone Phone number
 * @param string $message User's message
 * @param array $conversations All conversations
 */
function handleAIConversation($phone, $message, &$conversations) {
    // Get Claude response
    $response = getClaudeResponse($phone, $message, $conversations);
    
    // Save conversation history
    saveConversations($conversations);
    
    // Send response (split if too long)
    if (strlen($response) <= 160) {
        sendSMS($phone, $response);
    } else {
        // Split into multiple SMS messages
        $parts = str_split($response, 155);
        $totalParts = count($parts);
        
        foreach ($parts as $index => $part) {
            $partNumber = $index + 1;
            $message = "($partNumber/$totalParts) " . $part;
            sendSMS($phone, $message);
            usleep(500000); // 0.5 second delay between messages
        }
    }
}

// ============================================================================
// ENTRY POINT
// ============================================================================

// Handle incoming webhook request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    handleIncomingSMS();
} else {
    // Non-POST request
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed. This endpoint only accepts POST requests.']);
}

?>
