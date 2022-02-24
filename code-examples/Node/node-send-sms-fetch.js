/**
 * Sending SMS with fetch
 *  
 * 2022-02-24: At the time, fetch() is still an experimental feature in node (since v.17.5).
 * To run this code without the node-fetch module, use the following command:
 * node --experimental-fetch node-send-sms-fetch.js
 * 
 * Since v3 node-fetch does no longer support require().
 * To use 'import', remember to add "type":"module" in your package.json.
 * 
 * For node-fetch v.2, use:
 * const fetch = require("node-fetch")
 * 
 */

import fetch from "node-fetch";

// API credentials
const username = "<API Username>";
const password = "<API Password>";
const authKey  = Buffer.from(username + ":" + password).toString("base64");

// Request data object
var data = {
  from: "NodeElk",
  to: "+46766860001",
  message: "Hej Vad trevligt att se dig!"
}

data = new URLSearchParams(data);
data = data.toString();

fetch("https://api.46elks.com/a1/sms", {
  method: "post",
  body: data,
  headers: {"Authorization": "Basic "  + authKey}
})
.then(res => res.json())
.then(json => console.log(json))
.catch(err => console.log(err))