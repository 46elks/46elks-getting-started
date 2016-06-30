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
        "message" : "Hi! This is a message to you!"
      }
    };
  var response = UrlFetchApp.fetch("https://api.46elks.com/a1/SMS", parameters);
}