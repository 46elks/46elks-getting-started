var express = require('express');
var router = express.Router();

/* GET users listing. */
router.get('/send', function(req, res, next) {

var request = require('request');
var result;

var hej = request.post("https://api.46elks.com/a1/SMS", 
    { form: {
        from: "Victoria",
        to: "+46722535106",
        message: "Test message 1",
        flashsms: 'yes'
         },

    'auth': {
    'user': '<APIUSERNAME>',
    'pass': '<APIPASSWORD>'}
    },
    function (error, response, body) {
      result = body;
    });
  res.send('send a textmessage');
});

module.exports = router;
