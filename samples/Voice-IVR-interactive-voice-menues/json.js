{
  "ivr": "http://myserver.se/welcome.wav",
  "1": {
    "play": "http://myserver.se/info.wav"
  },
  "2": {
    {
    "play": "http://myserver.se/entervoicemail.wav",
    "next": {
      "record": "http://myserver.se/newvoicemail"
    }
  }
  "3": {
    "ivr": "http://myserver.se/enterpin.wav",
    "digits": 5,
    "next": "http://myserver.se/usercode"
  }
  "4": {
    "connect": "+46723175800"
  }
}