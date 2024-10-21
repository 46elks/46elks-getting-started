/**
 * Sending MMS with fetch
 *  
 * 2022-02-24: At the time, fetch() is still an experimental feature in node (since v.17.5).
 * To run this code without the node-fetch module, use the following command:
 * node --experimental-fetch node-send-mms-fetch.js
 * 
 * Since node-fetch(v.3) does no longer support require().
 * To use 'import', remember to add "type":"module" in your package.json.
 * 
 * For node-fetch(v.2), use:
 * const fetch = require("node-fetch")
 * 
 */

import fetch from "node-fetch";

const sendMMS = async(credentials) => {
    try {
        const res = await fetch("https://api.46elks.com/a1/mms", {
            method: "post",
            body: new URLSearchParams({
                from: "noreply",
                to: "+46700000001",
                message: "Bring a sweater, it’s cold outside!",
                image: "https://46elks.com/press/46elks-blue-png"
            }),
            headers: { "Authorization": "Basic " + credentials }
        });

        const mmsRes = await res.json();
        console.log(mmsRes);

    } catch (err) {
        console.log(err);

    }
}

// API credentials
const username = "APIUSERNAME";
const password = "APIPASSWORD";
const authKey = Buffer.from(username + ":" + password).toString("base64");

sendMMS(authKey);