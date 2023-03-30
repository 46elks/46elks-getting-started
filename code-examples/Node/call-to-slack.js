const axios = require('axios');
const express = require('express');
const bodyParser = require('body-parser');
const app = express();
const PORT = process.env.PORT || 3000;

const slack_url = 'https://hooks.slack.com/services/XXXXXXXXX/XXXXXXXXX/XXXXXXXXXXXXXXXXXXXXXXXX';

const sendToSlack = async (txt) => {
  try {
    await axios.post(slackUrl, {
      username: 'incoming_call',
      text: txt,
    });
  } catch (error) {
    console.error(error);
  }
};

app.use(bodyParser.json());

app.post('/incoming-call', async (req, res) => {
  const incomingCaller = req.body.from; // Replace this with the actual POST data

  await sendToSlack(`incoming_caller: ${incomingCaller}`);

  res.json({
    connect: 'FIRST NUMBER',
    timeout: 20,
    failed: {
      connect: 'SECOND NUMBER',
      timeout: 20,
      failed: {
        connect: 'THIRD NUMBER',
        timeout: 20,
      },
    },
  });
});

app.listen(PORT, () => {
  console.log(`Server is running on port ${ PORT }`);
});