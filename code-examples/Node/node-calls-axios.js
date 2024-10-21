/**
 * Making phone calls with axios
 * 
 */

const axios = require("axios");

const makeCall = async () => {
  
  try {

    // API credentials
    const username = '<API Username>';
    const password = '<API Password>';
    const authKey     = Buffer.from(username + ":" + password).toString("base64");

    // Set the SMS endpoint
    const url = "https://api.46elks.com/a1/calls";

    // Request data object
    var data = {
      from: "+46700000001",
      to: "+46700000002",
      voice_start: '{"connect":"+46700000003"}'
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

makeCall();