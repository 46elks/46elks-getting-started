# Call for contributions

Are you missing an example?! Perhaps you'll build it before we do?! How about sharing it?
Help others who'll be in your current state in the future! :)

Se an error or something that can be improved? Pull requests are very welcome. Feel free to e-mail us at hello@46elks.com to make a suggestion, send us a request, or just get in touch because you feel like it!

## IVR
IVR stands for interactive voice response. You can play a message to the caller, and then do different actions depending on what the users does next. React to user pressing one, let the user press a pincode to access information, Let them know about your opening hours.

## Record incoming phonecalls
```
// Record all incoming calls
// Configure your 46elks number with voice_start as below:
{
  "connect": "+46704508449",
  "recordcall": "http://myserver.se/newrecording.php"
}

// The POST to newrecording.php will contain one variable called "wav", which
// will be a link to the WAV audio file of the recorded call
```

## Interactive voice menu (IVR)
```
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
```
