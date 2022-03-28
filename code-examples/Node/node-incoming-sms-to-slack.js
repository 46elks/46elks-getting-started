const express = require('express');
const bodyParser = require('body-parser');
const app = express();
const server = require('http').Server(app);
const https = require('https')
const port = 5501;

app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: true }));

server.listen(port, (err) => {
    if (err) {
        throw err;
    }
    console.log('Server running on port ' + port);
});

const webhookURL = "<YOUR SLACK WEBHOOK>";

const callback = (response) => {
  var str = ''
  response.on('data', (chunk) => {
      str += chunk
  })

  response.on('end', () => {
      console.log(str)
  })
}

const options = {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json'
  }
}

function createText(message){
  const userAccountNotification = {
    'username': 'SMS Notifier', // This will appear as user name who posts the message
    'text': message, // text
  };
  return userAccountNotification
}

app.post('/slack', (req, res) => {
    messageBody = JSON.stringify(createText(req.body.message));
    var request = https.request(webhookURL,options, callback)
    request.write(messageBody)
    request.end()
    res.end();
});