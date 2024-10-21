/**
 * Making phone calls with fetch
 *  
 * 2022-02-24: At the time, fetch() is still an experimental feature in node (since v.17.5).
 * To run this code without the node-fetch module, use the folloing command:
 * node --experimental-fetch node-send-sms-fetch.js
 * 
 * node-fetch module:
 * Since v3 node-fetch does no longer support require().
 * To use 'import', remember to add "type":"module" in your package.json.
 * 
 */

import fetch from "node-fetch"; // v.3
// const fetch = require("node-fetch"); // v.2

// API credentials
const username = '<API Username>';
const password = '<API Password>';
const authKey     = Buffer.from(username + ":" + password).toString("base64");

// Request data object
var data = {
  from: "+46700000001",
  to: "+46700000002",
  voice_start: '{"connect":"+46700000003"}'
}

data = new URLSearchParams(data);
data = data.toString();

fetch("https://api.46elks.com/a1/calls", {
  method: "post",
  body: data,
  headers: {"Authorization": "Basic "  + authKey}
})
.then(res => res.json())
.then(json => console.log(json))
.catch(err => console.log(err))