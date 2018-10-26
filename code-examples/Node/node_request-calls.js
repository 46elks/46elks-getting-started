const request = require("request")

request.post("https://api.46elks.com/a1/Calls", {
	"auth": {
		"user": "<API-Username>",
		"pass": "<API-Password>"
	},

	"form": {
		"from":		    "<TEL. NO>", // See https://www.46elks.com/docs#get-started how phone numbers should be formatted
		"to":		    "<TEL. NO>",
		"voice_start":  '{ "connect": "<TEL. NO>" }'
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
