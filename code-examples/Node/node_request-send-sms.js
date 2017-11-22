const request = require("request")

request.post("https://api.46elks.com/a1/SMS", {
	"auth": {
		"user": "<API-Username>",
		"pass": "<API-Password>"
	},

	"form": {
		"from":		"TestUser",
		"to":		"<TEL. NO>", // See https://www.46elks.com/docs#get-started how phone numbers should be formatted
		"message":	"It works!",
		"flashsms":	"yes" // Read more about it here: https://www.46elks.com/docs#flashsms
	}
}, function(err, response, body) {
	if (err) {
		console.error(err)
	} else if (response.statusCode != 200) {
		console.error("Error", response.statusCode, body)
	} else {
		console.log("Success!")
	}
})
