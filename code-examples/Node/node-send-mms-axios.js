const axios = require("axios");

const sendMMS = async(credentials) => {
    try {
        // Set the MMS endpoint
        const url = "https://api.46elks.com/a1/mms";

        // Request data object
        const data = new URLSearchParams({
            from: "noreply",
            to: "+46700000001",
            message: "Bring a sweater, it’s cold outside!",
            image: "https://46elks.com/press/46elks-blue-png"
        });

        // Set the headers
        const config = {
            headers: {
                "Authorization": "Basic " + credentials
            }
        };

        // Send request
        const res = await axios.post(url, data, config);
        console.log(res.data);

    } catch (err) {
        console.error(err);
    }
};

// API credentials
const username = "APIUSERNAME";
const password = "APIPASSWORD";
const authKey = Buffer.from(username + ":" + password).toString("base64");

sendMMS(authKey);