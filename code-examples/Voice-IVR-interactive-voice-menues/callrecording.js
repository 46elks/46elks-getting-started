// Record all incoming calls
// Configure your 46elks number with voice_start as below:
{
  "connect": "+46704508449",
  "recordcall": "http://myserver.se/newrecording.php"
}

// The POST to newrecording.php will contain one variable called "wav", which
// will be a link to the WAV audio file of the recorded call
