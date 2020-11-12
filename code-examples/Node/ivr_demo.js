const express = require('express');
const bodyParser = require('body-parser');
const app = express();
const server = require('http').Server(app);
const port = 5501;

app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: true }));

server.listen(port, (err) => {
    if (err) {
        throw err;
    }
    console.log('Server running on port ' + port);
});

var baseURL = "YOUR-SERVER"

app.post('/incomingCall', (req, res) => {
    res.status(200);
    console.log(req.body);
    res.json({
        "ivr": "https://46elks.com/static/sound/ivr-menu.mp3",
        "digits": 1,
        "next": baseURL + "ivr"
    })
    res.end();
});

app.post('/ivr', (req, res) => {
    res.status(200);
    let result = req.body.result;
    if (result == "1") {
        res.json({ 'play': "https://www.46elks.com/static/sound/voiceinfo.mp3" })
    }
    if (result == "2") {
        res.json({ 'play': "https://www.46elks.com/static/sound/smsinfo.mp3" })
    }
    res.end();
});