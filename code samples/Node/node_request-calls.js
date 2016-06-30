var request = require('request');

var hej = request.post("https://api.46elks.com/a1/Calls", 
    { form: { 
        from: "+46723175800",
        to: "+46760261899",
        voice_start: '{"connect":"+461890510"}'
         },

    'auth': {
    'user': '<API Username>',
    'pass': '<API Password>'}},
    function (error, response, body) {console.log(body) 
    });