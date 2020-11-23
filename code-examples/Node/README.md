# Telephony + NODE = true

![46elks-logo](../../code-examples/logo-240x150.png)

## Here is a brief explanation of the Node.js demos in this repo

### node-send-sms.js and node_request-send-sms.js

* A basic example of sending SMS in two versions

### node-calls.js and node_request-calls.js

* Make a phone call to a number
* If connected, attempt to connect to another number

### node_ivr.js

* A basic IVR solution
* Receive a phone call and offer two options
* Depending on response, play an audio file

### node_incoming_call.js

* Receive an incoming call to your server
* Connect the call to another number

### node_incoming_sms.js

* Receive an incoming SMS to your server
* Send an automatic reply

### incoming_call_and_sms.js

* Receive an incoming call to your server OR an incoming SMS
* If call, connect the call to another number
* If SMS, send an automatic reply