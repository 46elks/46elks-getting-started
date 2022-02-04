const https = require('https')

const username = '<API Username>'
const password = '<API Password>'
const postFields = {
    from: "+46723175800",
    to: "+46723175800",
    voice_start: '{"connect":"+461890510"}'
}

const key = Buffer.from(username + ':' + password).toString("base64");
const querystring = new URLSearchParams(postFields)
const postData = querystring.toString()

const options = {
    hostname: 'api.46elks.com',
    path: '/a1/Calls',
    method: 'POST',
    headers: {
        'Authorization': 'Basic ' + key
    }
}

const callback = (response) => {
    var str = ''
    response.on('data', (chunk) => {
        str += chunk
    })

    response.on('end', () => {
        console.log(str)
    })
}

// Start the web request.
const request = https.request(options, callback)

// Send the real data away to the server.
request.write(postData)

// Finish sending the request.
request.end()