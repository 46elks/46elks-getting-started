var https = require('https');
var querystring = require('querystring');

var username = '<API Username>';
var password = '<API Password>';
var postFields = {
  from:    "Hello", 
  to:      "+46723175800", 
  message: "Hej Vad trevligt att se dig!"
  }

var key = new Buffer(uusername + ':' + password..toString('base64');
var postData = querystring.stringify(postFields);

var options = {
  hostname: 'api.46elks.com',
  path:     '/a1/SMS',
  method:   'POST',
  headers:  {
    'Authorization': 'Basic ' + key
    }
  };

// Start the web request.
var request = https.request(options, callback);

// Send the real data away to the server.
request.write(postData);

// Finish sending the request.
request.end();

callback = function(response) {
  var str = ''
  response.on('data', function (chunk) {
    str += chunk;
  });

  response.on('end', function () {
    console.log(str);
  });
}
