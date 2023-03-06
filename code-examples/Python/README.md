![46elks-logo](../../code-examples/logo-240x150.png)

## Here is a brief explanation of the Python demos in this repo

### python-send-sms.py and python-send-sms-extended.py

* A basic example of sending SMS with [Requests](https://requests.readthedocs.io/en/latest/)
* The extended version gives examples of optional parameters available through the 46elks API

### python-send-sms-urllib.py

* An example of sending SMS without external dependencies by using [urllib](https://docs.python.org/3/library/urllib.html)

### python-send-sms-multiple-recipients.py

* Send a basic SMS in a for loop

### python-call-and-connect.py

* Make a phone call to a number
* If connected, attempt to connect to another number

### python-flask-ivr.py

* A basic Flask IVR solution
* Receive a phone call and offer two options
* Depending on response, play an audio file

### python-call-multiple.py

* Receive a phone call to a virtual 46elks number
* Attempt to call multiple numbers at the same time
* The first number to answer will be connected to the inbound caller
* This script also includes examples of the "whenhangup" parameter which sends data about the call at the end of the call

### python-forward-incoming-sms-to-airtable.py

* Receive an SMS to your server
* Add information about this SMS to an Airtable project

### python-record-incoming-call.py

* Receive an incoming call to your server
* Record the call
* Download and store the recording from 46elks

### python-send-sms-from-json.py

* An example of how you could send SMS from a database
* In this case sending customers different messages based on the customers information
