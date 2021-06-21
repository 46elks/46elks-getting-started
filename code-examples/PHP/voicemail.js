// Sample custom voicemail using 46elks
//
// 1. Change +46704508449 to your destination number **
// 2. Allocate a 46elks number if you do not have one yet
// 3. Configure voice_start on the 46elks number to the JSON below
//    or to a URL which returns the JSON below upon HTTP POST
// 4. Call your 46elks number!
//
// ** Mobile 46elks numbers can only "connect" to same-country mobile numbers
{
  "connect": "+46704508449",
  "timeout": 15,
  "busy": {
    "play": "http://www.46elks.com/download/PleaseLeaveAMessageAfterTheBeep.wav",
    "next": {
      "record": "http://myserver.se/newvoicemail.php"
    }
  },
  "failed": {
    "play": "http://www.46elks.com/download/PleaseLeaveAMessageAfterTheBeep.wav",
    "next": {
      "record": "http://myserver.se/newvoicemail.php"
    }
  }
}