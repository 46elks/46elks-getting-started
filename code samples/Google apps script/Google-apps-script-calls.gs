function elkTest(){
  var user = "<API-username>";
  var pass = "<API-password>";

  var parameters = 
    {
    "method": "post",
    "headers": 
        {
          "Authorization": "Basic " + Utilities.base64Encode(user+":"+pass)
        },
      "payload": 
      {
        "from" : "+46723175800", 
        "to" : "+46766861004",
        "voice_start": '{"connect":"+461890510"}'
      }
    };
  var response = UrlFetchApp.fetch("https://api.46elks.com/a1/Call", parameters);
}