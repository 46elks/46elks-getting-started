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

app.post('/sms', (req, res) => {
    res.status(200);
    console.log(req.body);
    res.json({ 'reply': "Thanks" })
    res.end();
});