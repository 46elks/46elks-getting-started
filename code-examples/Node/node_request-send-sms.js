const request = require("request")

request.post("https://api.46elks.com/a1/SMS", {
    "auth": {
        "user": "<API-Username>",
        "pass": "<API-Password>"
    },

    "form": {
        "from": "NodeElk",
        "to": "<TEL. NO>", // See https://www.46elks.com/docs#get-started how phone numbers should be formatted
        "message": "It works!"
            //"flashsms":	"yes", Optional parameter for immediately displaying the SMS --> Read more about it here: https://46elks.com/docs/send-sms
            //"whendelivered":	"yes", Optional parameter for receiving delivery reports --> Read more about it here: https://46elks.com/docs/sms-delivery-reports
            //"dontlog":	"message", Optional parameter to avoid storing the message text --> Read more about it here: https://46elks.com/docs/send-sms
    }
}, (err, response, body) => {
    if (err) {
        console.error(err)
    } else if (response.statusCode !== 200) {
        console.error("Error", response.statusCode, body)
    } else {
        console.log("Success!")
    }
})