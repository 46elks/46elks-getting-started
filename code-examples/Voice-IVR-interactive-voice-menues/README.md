## IVR
IVR stands for interactive voice response. You can play a message to the caller, and then do different actions depending on what the users does next. React to user pressing one, let the user press a pincode to access information, Let them know about your opening hours.

## Record incoming phonecalls
```
// Record all incoming calls
// Configure your 46elks number with voice_start as below:
{
  "connect": "+46723414646",
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


### Support calls
```
//  Calls up one person at the time, with a delay, until someone responds, or there is no longer anyone to call.
{
  "connect":"+46766861647",
  "timeout":"15",
  "next":{
    "connect":"+46723414646",
    "timeout":"15",
    "next":{
      "connect":"+46766861647",
      "timeout":"15",
      "next":{
        "connect":"+46723414646"
      }
    }
  }
}
```


### !Call everyone!

When someone calls your number, call everyone, and the first person to respond, is connected to the caller.
```
{
  "connect":"+46723414646,+46766861647,+46723414646"
}
```

<br>
### IVR 
#### Voice mail if busy
1. Let user make a choice
2. Try calling people on support for that choice
3. When choise was made, try calling everyone... if busy, ask caller if they would like to leave a message, and let them know that you will call them back as soon as possible.
4. In case "2", when buys - what we do depends on what json you serve us back.
5. Case 3 - code for verification

```
{
    "ivr": "http://myserver.com/welcome-if-you-want-a-press-1-if-other-press-2",
    "1": {
        "connect": "+46766861647,+46766861647",
        "busy": {
            "play": "http://myserver.com/we-are-busy-please-leave-a-message-and-well-call-you-back.mp3",
            "next": {
                "record": "http://myserver.com/store-call"
            }
        }
    },
    "2": {
        "connect": "+46766861647,+46766861647",
        "busy": "http://myserver.com/if-busy-do-this-return-json-response-with-instructions-to-46elks"
    },
    "3": {
      "ivr": "http://myserver.se/enterpin.wav",
      "digits": 5,
      "next": "http://myserver.se/login-and-then-return-json-response-with-instructions-to-46elks.php"
    }
}
```
Docs: 
[Voice calls](https://www.46elks.com/docs#voice-calls)
[IVR](https://www.46elks.com/docs#action---ivr)


# Contributions welcome!

An example missing?  
See an error or something that can be improved? Share and help others!  
Pull requests are very welcome. Feel free to e-mail us at hello@46elks.com to make a suggestion, send us a request, or just get in touch because you feel like it!



<br>

```
 ,;MMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMM;,.
/MMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMM.
MMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMM
MMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMM
MMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMM
MMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMM
MMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMM`   `^´  `Q/^^\MMMpcqMMMMMMMMMMMMMMM
MMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMM;,             `^´   `VP    YP´  `MM
MMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMDomm;,._                     /M
MMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMP`        _.,,=rRMMMMMMMMMMM
MMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMP^^^^MMMM´          MMMMMMMMMMMMMMMMMM
MMMMMMMMP`              '^^oMMMPP`       ``´            \MMMMMMMMMMMMMMMM
MMMMMMM´                                                  ``\MMMMMMMMMMMM
MMMMMM'                                                          'QMMMMMM
MMM/`                                                              \MMMMM
MM/_=o,                                     ,/     \_               `MMMM
MMMMMMM,                                   /MM     pMM\,._           MMMM
MMMMMMMM,                                 ,MMMM, pMMMMMMMX          /MMMM
MMMMMMMMP                                 AMMMMMMMMMMMMMMMM\m____.pMMMMMM
MMMMMMMP                                  MMMMMMMMMMMMMMMMMMMMMMMMMMMMMMM
MMMMMMM|         ,.mPDMMMMMMMMpo.,        \MMMMMMMMMMMMMMMMMMMMMMMMMMMMMM
MMMMMMM|      ,mPMMMMMMMMMMMMMMMMMDo       \MMMMMMMMMMMMMMMMMMMMMMMMMMMMM
MMMMMMM|    /MMMMMMMMMMMMMMMMMMMMMMMM\       \MMMMMMMMMMMMMMMMMMMMMMMMMMM
MMMMMMM|    MMMMMMMMMMMMMMMMMMMMMMMMMMDp,.    `MMMMMMMMMMMMMMMMMMMMMMMMMM
MMMMMMP     MMMMMMMMMMMMMMMMMMMMMMMMMMMMMMP    \MMMMMMMMMMMMMMMMMMMMMMMMM
MMMMMM|    `MMMMMMMMMMMMMMMMMMMMMMMMMMMMMM|     \MMMMMMMMMMMMMMMMMMMMMMMM
MMMMMMMPPDmmMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMM
MMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMM
MMMMMMMMMMMMMMMBYMMJOHANNESLMMOFMM46ELKSMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMM
\MMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMP
`\MMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMM`
```

