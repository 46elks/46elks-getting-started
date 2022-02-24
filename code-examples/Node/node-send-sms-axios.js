/**
 * Sending SMS with axios
 * 
 */

const axios = require("axios");

const sendSMS = async () => {
  
  try {

    // API credentials
    const username = "<API Username>";
    const password = "<API Password>";
    const authKey     = Buffer.from(username + ":" + password).toString("base64");

    // Set the SMS endpoint
    const url = "https://api.46elks.com/a1/sms";

    // Request data object
    var data = {
      from: "NodeElk",
      to: "+46766860001",
      message: "Hej Vad trevligt att se dig!"
    }

    data = new URLSearchParams(data);
    data = data.toString();

    // Set the headers
    const config = {
      headers: {
       "Authorization": "Basic " + authKey
      }
    };

    // Send request
    const res = await axios.post(url, data, config);
    console.log(res.data);

  } catch (err) {
    console.error(err);
  }
};

sendSMS();