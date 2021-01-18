const express = require('express');
const Fs = require('fs');
const bodyParser = require('body-parser');
const Path = require('path');
const Axios = require('axios');
const app = express();
const server = require('http').Server(app);
const port = 5000;

app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: true }));

server.listen(port, (err) => {
    if (err) {
        throw err;
    }
    console.log('Server running on port ' + port);
});

const username = "USER";
const password = "PASS"
const key = Buffer.from(username + ':' + password).toString("base64");

app.post('/recording', (req, res) => {
    res.status(200);
    let url = req.body.wav;
    download(url)
    res.end();
});

async function download(url) {
    let name = url.split("/");
    let filename = name[name.length - 1];
    console.log("Fetching: " + filename)
    const path = Path.resolve(__dirname, "recordings", filename)
    const response = await Axios({
        method: 'GET',
        url: url,
        responseType: 'stream',
        headers: { 'Authorization': 'Basic ' + key }
    })
    response.data.pipe(Fs.createWriteStream(path))
    return new Promise((resolve, reject) => {
        response.data.on('end', () => {
            console.log("Finished downloading: " + filename)
            resolve();
        })
        response.data.on('error', () => {
            reject(err);
        })
    })
}