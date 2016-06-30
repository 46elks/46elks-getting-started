// Sample IVR for incoming calls to a 46elks number
//
// 1. Change +461890510 and +461890511 to your destination numbers **
// 2. Allocate a 46elks number if you do not have one yet
// 3. Configure voice_start on the 46elks number to the JSON below
//    or to a URL which returns the JSON below upon HTTP POST
// 4. Call your 46elks number!
//
// ** Mobile 46elks numbers can only "connect" to same-country mobile numbers
{
  "ivr": "http://www.46elks.com/download/DummySupportIVR.wav",
  "1": {
    "connect": "+461890510"
  },
  "2": {
    "connect": "+461890511"
  }
}