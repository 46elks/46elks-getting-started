var request = require('request');

var hej = request.post("https://api.46elks.com/a1/SMS", 
    { form: { 
        from: "royden",
        to: "+46760261899",
        message: "Test message 1", 
        flashsms: 'yes'
         },

    'auth': {
    'user': '<API Username>',
    'pass': '<API Password>'}},
    function (error, response, body) {console.log(body) });