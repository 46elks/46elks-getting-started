"""
SMS Health Data Tracking System with AI-Powered Responses

This Python script provides a comprehensive 2-way SMS communication system for tracking health data
using the 46elks API and Claude Sonnet 4.5 AI. It combines simple keyword-based tracking with
intelligent conversational AI capabilities for health information and support.

CORE FEATURES:
================
1. KEYWORD-BASED HEALTH TRACKING:
   - Patients send keywords ("blood pressure", "glucose", or "weight") to trigger data collection
   - System sends follow-up SMS requesting the appropriate reading
   - Patient replies with their reading (e.g., "120/80" for blood pressure)
   - Reading is logged to file with timestamp and confirmation SMS sent

2. AI-POWERED HEALTH ASSISTANT:
   - Natural conversation about health topics using Claude Sonnet 4.5
   - Context-aware responses that maintain conversation history per user
   - Emergency detection and crisis resource provision
   - General health information and wellness guidance

WORKFLOW:
=========
TRACKING MODE:
Step 1: Patient texts keyword → "blood pressure" to +46701234567
Step 2: System replies → "Please send your blood pressure reading (e.g., 120/80)"
Step 3: Patient replies → "125/82"
Step 4: System logs data and confirms → "Blood pressure 125/82 recorded at 2025-11-07 14:30"

AI ASSISTANT MODE:
Step 1: Patient asks → "What should I do about my headache?"
Step 2: Claude analyzes and responds with helpful information
Step 3: Conversation continues with maintained context

DATA STORAGE:
=============
- Blood Pressure: stored in "data/blood_pressure_log.txt"
- Glucose Level: stored in "data/glucose_log.txt"
- Weight (kg): stored in "data/weight_log.txt"
- Conversation History: stored in "data/conversations.json"
- Session State: stored in "data/sessions.json"

Each log entry includes: Phone Number | Reading | Timestamp
Format: +46701234567 | 120/80 | 2025-11-07 14:30:25

CONFIGURATION:
==============
Set these environment variables before deployment:
- ELKS_API_USERNAME: Your 46elks API username
- ELKS_API_PASSWORD: Your 46elks API password
- FROM_PHONE: Your 46elks phone number (international format)
- ANTHROPIC_API_KEY: Your Claude API key

Or use a .env file with python-dotenv

WEBHOOK SETUP:
==============
1. Install dependencies: pip install flask anthropic requests python-dotenv
2. Run the script: python sms_health_tracker_complete.py
3. For production, use gunicorn: gunicorn -w 4 -b 0.0.0.0:5000 sms_health_tracker_complete:app
4. Configure 46elks webhook to: https://yourdomain.com/sms
5. Ensure data/ directory exists and is writable

For local testing, use ngrok: ngrok http 5000

SECURITY NOTES:
===============
- Store credentials in environment variables for production
- Implement proper authentication/authorization
- Use HTTPS for all communications
- Comply with HIPAA/GDPR as applicable
- This is demonstration software - add appropriate safeguards for production

DISCLAIMER:
===========
This is demonstration software for educational purposes only.
NOT a medical device. NOT a substitute for professional medical care.
Always encourage users to consult healthcare providers.
Use at your own risk. Authors assume no liability.
"""

import os
import re
import json
import logging
from datetime import datetime, timedelta
from typing import Dict, List, Optional, Tuple
from pathlib import Path

from flask import Flask, request, jsonify
from anthropic import Anthropic
import requests
from requests.auth import HTTPBasicAuth
from dotenv import load_dotenv

# ============================================================================
# CONFIGURATION
# ============================================================================

# Load environment variables from .env file
load_dotenv()

# 46elks API Configuration
ELKS_API_USERNAME = os.getenv('ELKS_API_USERNAME', 'your_46elks_username')
ELKS_API_PASSWORD = os.getenv('ELKS_API_PASSWORD', 'your_46elks_password')
FROM_PHONE = os.getenv('FROM_PHONE', '+46701234567')
ELKS_API_URL = 'https://api.46elks.com/a1/sms'

# Anthropic Claude API Configuration
ANTHROPIC_API_KEY = os.getenv('ANTHROPIC_API_KEY', 'your_anthropic_api_key')
CLAUDE_MODEL = 'claude-sonnet-4-5-20250929'

# Data Storage Configuration
DATA_DIR = Path(__file__).parent / 'data'
BP_LOG_FILE = DATA_DIR / 'blood_pressure_log.txt'
GLUCOSE_LOG_FILE = DATA_DIR / 'glucose_log.txt'
WEIGHT_LOG_FILE = DATA_DIR / 'weight_log.txt'
SESSION_FILE = DATA_DIR / 'sessions.json'
CONVERSATION_FILE = DATA_DIR / 'conversations.json'

# Session timeout (30 minutes)
SESSION_TIMEOUT = timedelta(minutes=30)

# ============================================================================
# SYSTEM INITIALIZATION
# ============================================================================

# Configure logging
logging.basicConfig(
    level=logging.INFO,
    format='%(asctime)s - %(name)s - %(levelname)s - %(message)s',
    handlers=[
        logging.FileHandler(DATA_DIR / 'app.log'),
        logging.StreamHandler()
    ]
)
logger = logging.getLogger(__name__)

# Create Flask app
app = Flask(__name__)

# Create data directory if it doesn't exist
DATA_DIR.mkdir(exist_ok=True)

# Initialize log files if they don't exist
for log_file in [BP_LOG_FILE, GLUCOSE_LOG_FILE, WEIGHT_LOG_FILE]:
    if not log_file.exists():
        with open(log_file, 'w') as f:
            f.write("Phone Number | Reading | Timestamp\n")
            f.write("-" * 70 + "\n")

# Initialize Anthropic client
anthropic_client = Anthropic(api_key=ANTHROPIC_API_KEY)

# ============================================================================
# CLAUDE AI SYSTEM PROMPT
# ============================================================================

SYSTEM_PROMPT = """You are a helpful healthcare information assistant that communicates via SMS. Your role is to:

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

Be warm, supportive, and always prioritize user safety. Remember you're communicating via SMS."""

# ============================================================================
# DATA MANAGEMENT FUNCTIONS
# ============================================================================

def load_json_file(file_path: Path) -> Dict:
    """Load JSON data from file."""
    if not file_path.exists():
        return {}
    try:
        with open(file_path, 'r') as f:
            return json.load(f)
    except json.JSONDecodeError:
        logger.error(f"Error decoding JSON from {file_path}")
        return {}

def save_json_file(file_path: Path, data: Dict) -> None:
    """Save JSON data to file."""
    try:
        with open(file_path, 'w') as f:
            json.dump(data, f, indent=2)
    except Exception as e:
        logger.error(f"Error saving JSON to {file_path}: {e}")

def load_sessions() -> Dict:
    """Load sessions from file."""
    return load_json_file(SESSION_FILE)

def save_sessions(sessions: Dict) -> None:
    """Save sessions to file."""
    save_json_file(SESSION_FILE, sessions)

def load_conversations() -> Dict:
    """Load conversation history from file."""
    return load_json_file(CONVERSATION_FILE)

def save_conversations(conversations: Dict) -> None:
    """Save conversation history to file."""
    save_json_file(CONVERSATION_FILE, conversations)

def clean_expired_sessions(sessions: Dict) -> Dict:
    """Remove expired sessions."""
    now = datetime.now()
    cleaned = {}
    for phone, session in sessions.items():
        timestamp = datetime.fromisoformat(session['timestamp'])
        if now - timestamp < SESSION_TIMEOUT:
            cleaned[phone] = session
        else:
            logger.info(f"Expired session removed for {phone}")
    return cleaned

def get_session(phone: str, sessions: Dict) -> Optional[Dict]:
    """Get active session for a phone number."""
    if phone not in sessions:
        return None
    
    session = sessions[phone]
    timestamp = datetime.fromisoformat(session['timestamp'])
    
    if datetime.now() - timestamp > SESSION_TIMEOUT:
        logger.info(f"Session expired for {phone}")
        return None
    
    return session

def create_session(phone: str, session_type: str, sessions: Dict) -> None:
    """Create new session for a phone number."""
    sessions[phone] = {
        'type': session_type,
        'timestamp': datetime.now().isoformat()
    }
    logger.info(f"Created {session_type} session for {phone}")

def delete_session(phone: str, sessions: Dict) -> None:
    """Delete session for a phone number."""
    if phone in sessions:
        del sessions[phone]
        logger.info(f"Deleted session for {phone}")

# ============================================================================
# SMS COMMUNICATION FUNCTIONS
# ============================================================================

def send_sms(to: str, message: str) -> bool:
    """
    Send SMS via 46elks API.
    
    Args:
        to: Recipient phone number
        message: Message content
        
    Returns:
        True if sent successfully, False otherwise
    """
    try:
        data = {
            'from': FROM_PHONE,
            'to': to,
            'message': message
        }
        
        response = requests.post(
            ELKS_API_URL,
            auth=HTTPBasicAuth(ELKS_API_USERNAME, ELKS_API_PASSWORD),
            data=data,
            timeout=10
        )
        
        if response.status_code == 200:
            logger.info(f"SMS sent successfully to {to}")
            return True
        else:
            logger.error(f"Failed to send SMS: {response.status_code} - {response.text}")
            return False
            
    except Exception as e:
        logger.error(f"Error sending SMS: {e}")
        return False

def send_long_sms(to: str, message: str) -> None:
    """
    Send SMS, splitting into multiple messages if needed.
    
    Args:
        to: Recipient phone number
        message: Message content
    """
    if len(message) <= 160:
        send_sms(to, message)
    else:
        # Split into chunks of 155 characters (leaving room for part numbers)
        max_length = 155
        parts = [message[i:i+max_length] for i in range(0, len(message), max_length)]
        total_parts = len(parts)
        
        for index, part in enumerate(parts, 1):
            formatted_message = f"({index}/{total_parts}) {part}"
            send_sms(to, formatted_message)
            # Small delay between messages
            if index < total_parts:
                import time
                time.sleep(0.5)

# ============================================================================
# DATA LOGGING FUNCTIONS
# ============================================================================

def log_reading(phone: str, reading: str, reading_type: str) -> bool:
    """
    Log health reading to file.
    
    Args:
        phone: Phone number
        reading: Reading value
        reading_type: Type of reading (bp, glucose, weight)
        
    Returns:
        True if logged successfully, False otherwise
    """
    timestamp = datetime.now().strftime('%Y-%m-%d %H:%M:%S')
    log_line = f"{phone} | {reading} | {timestamp}\n"
    
    log_file_map = {
        'bp': BP_LOG_FILE,
        'glucose': GLUCOSE_LOG_FILE,
        'weight': WEIGHT_LOG_FILE
    }
    
    log_file = log_file_map.get(reading_type)
    if not log_file:
        logger.error(f"Unknown reading type: {reading_type}")
        return False
    
    try:
        with open(log_file, 'a') as f:
            f.write(log_line)
        logger.info(f"Logged {reading_type} reading for {phone}: {reading}")
        return True
    except Exception as e:
        logger.error(f"Failed to log {reading_type} reading: {e}")
        return False

# ============================================================================
# DATA VALIDATION FUNCTIONS
# ============================================================================

def is_valid_blood_pressure(reading: str) -> bool:
    """Validate blood pressure reading format (e.g., 120/80)."""
    pattern = r'^\d{2,3}/\d{2,3}$'
    return bool(re.match(pattern, reading.strip()))

def is_valid_glucose(reading: str) -> bool:
    """Validate glucose reading format (mmol/L)."""
    try:
        value = float(reading.strip())
        return 0 < value < 50  # Reasonable mmol/L range
    except ValueError:
        return False

def is_valid_weight(reading: str) -> bool:
    """Validate weight reading format (kg)."""
    try:
        value = float(reading.strip())
        return 20 < value < 300  # Reasonable kg range
    except ValueError:
        return False

# ============================================================================
# AI FUNCTIONS - CLAUDE INTEGRATION
# ============================================================================

def get_claude_response(phone: str, user_message: str, conversations: Dict) -> str:
    """
    Get AI response from Claude Sonnet 4.5.
    
    Args:
        phone: User's phone number for conversation tracking
        user_message: User's message
        conversations: Conversation history dictionary
        
    Returns:
        Claude's response message
    """
    try:
        # Initialize conversation if new user
        if phone not in conversations:
            conversations[phone] = []
            logger.info(f"New conversation started for {phone}")
        
        # Add user message to history
        conversations[phone].append({
            'role': 'user',
            'content': user_message
        })
        
        # Keep conversation history manageable (last 20 messages)
        if len(conversations[phone]) > 20:
            conversations[phone] = conversations[phone][-20:]
        
        # Call Claude API
        response = anthropic_client.messages.create(
            model=CLAUDE_MODEL,
            max_tokens=500,
            system=SYSTEM_PROMPT,
            messages=conversations[phone]
        )
        
        # Extract assistant's response
        assistant_message = response.content[0].text
        
        # Add assistant response to history
        conversations[phone].append({
            'role': 'assistant',
            'content': assistant_message
        })
        
        logger.info(f"Claude response generated for {phone}")
        return assistant_message
        
    except Exception as e:
        logger.error(f"Error getting Claude response: {e}")
        return "I'm experiencing technical difficulties. Please try again later or contact support."

# ============================================================================
# REQUEST HANDLERS
# ============================================================================

def handle_data_collection(phone: str, message: str, session: Dict, sessions: Dict) -> None:
    """
    Handle data collection session.
    
    Args:
        phone: Phone number
        message: User's message
        session: Session data
        sessions: All sessions dictionary
    """
    session_type = session['type']
    reading = message.strip()
    
    # Validation mapping
    validation_map = {
        'bp': (is_valid_blood_pressure, 'Blood pressure', 
               'Invalid format. Please send blood pressure as: 120/80'),
        'glucose': (is_valid_glucose, 'Glucose',
                   'Invalid format. Please send glucose level in mmol/L (e.g., 5.5)'),
        'weight': (is_valid_weight, 'Weight',
                  'Invalid format. Please send weight in kg (e.g., 75.5)')
    }
    
    if session_type not in validation_map:
        logger.error(f"Unknown session type: {session_type}")
        return
    
    validator, reading_name, error_msg = validation_map[session_type]
    
    if validator(reading):
        # Log the reading
        if log_reading(phone, reading, session_type):
            timestamp = datetime.now().strftime('%Y-%m-%d %H:%M')
            send_sms(phone, f"{reading_name} reading of {reading} recorded at {timestamp}. Thank you!")
        else:
            send_sms(phone, "Sorry, there was an error recording your reading. Please try again.")
        
        # Delete session
        delete_session(phone, sessions)
        save_sessions(sessions)
    else:
        # Invalid format
        send_sms(phone, error_msg)

def handle_ai_conversation(phone: str, message: str, conversations: Dict) -> None:
    """
    Handle AI conversation.
    
    Args:
        phone: Phone number
        message: User's message
        conversations: All conversations dictionary
    """
    # Get Claude response
    response = get_claude_response(phone, message, conversations)
    
    # Save conversation history
    save_conversations(conversations)
    
    # Send response (split if too long)
    send_long_sms(phone, response)

# ============================================================================
# FLASK ROUTES
# ============================================================================

@app.route('/sms', methods=['POST'])
def handle_incoming_sms():
    """Handle incoming SMS webhook from 46elks."""
    
    # Get incoming SMS data
    from_number = request.form.get('from', '')
    message = request.form.get('message', '').strip()
    
    if not from_number or not message:
        logger.error("Invalid incoming SMS: missing 'from' or 'message'")
        return jsonify({'error': 'Invalid request'}), 400
    
    logger.info(f"Incoming SMS from {from_number}: {message}")
    
    # Load sessions and conversations
    sessions = load_sessions()
    sessions = clean_expired_sessions(sessions)
    conversations = load_conversations()
    
    # Check if user has an active session
    session = get_session(from_number, sessions)
    
    if session:
        # User is in a data collection session
        handle_data_collection(from_number, message, session, sessions)
    else:
        # Check if message is a trigger keyword
        message_lower = message.lower()
        
        if 'blood pressure' in message_lower or message_lower == 'bp':
            # Start blood pressure tracking session
            create_session(from_number, 'bp', sessions)
            save_sessions(sessions)
            send_sms(from_number, "Please send your blood pressure reading in the format: 120/80")
            
        elif 'glucose' in message_lower or 'sugar' in message_lower:
            # Start glucose tracking session
            create_session(from_number, 'glucose', sessions)
            save_sessions(sessions)
            send_sms(from_number, "Please send your blood glucose reading in mmol/L (e.g., 5.5)")
            
        elif 'weight' in message_lower:
            # Start weight tracking session
            create_session(from_number, 'weight', sessions)
            save_sessions(sessions)
            send_sms(from_number, "Please send your weight in kilograms (e.g., 75.5)")
            
        else:
            # Not a tracking keyword - use AI assistant
            handle_ai_conversation(from_number, message, conversations)
    
    # Respond with 200 OK to 46elks
    return jsonify({'status': 'ok'}), 200

@app.route('/health', methods=['GET'])
def health_check():
    """Health check endpoint."""
    return jsonify({
        'status': 'healthy',
        'timestamp': datetime.now().isoformat()
    }), 200

@app.route('/', methods=['GET'])
def index():
    """Root endpoint with basic info."""
    return jsonify({
        'service': 'SMS Health Tracker',
        'version': '1.0.0',
        'endpoints': {
            '/sms': 'POST - Webhook for incoming SMS',
            '/health': 'GET - Health check'
        }
    }), 200

# ============================================================================
# MAIN ENTRY POINT
# ============================================================================

if __name__ == '__main__':
    # Development server
    logger.info("Starting SMS Health Tracker service...")
    logger.info(f"Data directory: {DATA_DIR}")
    
    # Check configuration
    if ELKS_API_USERNAME == 'your_46elks_username':
        logger.warning("46elks credentials not configured! Please set environment variables.")
    if ANTHROPIC_API_KEY == 'your_anthropic_api_key':
        logger.warning("Anthropic API key not configured! Please set ANTHROPIC_API_KEY environment variable.")
    
    # Run Flask app
    app.run(
        host='0.0.0.0',
        port=5000,
        debug=True
    )
