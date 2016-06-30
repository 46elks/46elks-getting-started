// Whisper is the common term for playing a message before transferring a call
// to the actual destination, and it's simple to create whisper with 46elks
// Just configure your 46elks number with voice_start as below:
{
  "play": "http://www.myserver.se/whisper.wav",
  "next": {
    "connect": "+461890510"
  }
}