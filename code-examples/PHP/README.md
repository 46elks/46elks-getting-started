![46elks-logo](../../code-examples/logo-240x150.png)

## Here is a brief explanation of the PHP demos in this repo

### send-sms.php

* A basic example of sending SMS
* Also included are a list of optional parameters available through the 46elks API

### send_custom_messages_to_multiple_recipients_from_csv

* An example of how to send customised SMS to list of recipients in a CSV file
* Includes an example of how the CSV should be formatted

### php-call-and-connect.php

* Make a phone call to a number
* If connected, attempt to connect to another number

### php-call-and-play.php

* Make a phone call to a number
* If connected, play a sound file from a url

### php-curl-sendsms.php 

* A basic example of sending SMS by making the post request using cURL

### php-curl-calls.php

* A basic example of a outgoing phone call by making the post request using cURL

### receive-sms-send-email.php

* Receive an incoming SMS to your server
* Send an email to yourself with content for the message and the sender

### receive-phonecall-send-email.php

* Be notified that someone tried to call your 46elks phone number
* Send an email to yourself with the callid and the number of the caller

### receive-phonecall-after-hours.php

* Receive an incoming phone call to your server
* If the time is between opening hours, transfer the call to the business owner
* If not, play an audio file

### example1_hours.php

* Receive an incoming SMS to your server
* Respond with opening hours for your business/service/home etc.

### example2_groupsms.php

* Send a dynamic SMS that is modified depending on group membership

### example3_textwall.php

* Record incoming SMS to a webpage and display sender name and the message content

### example3_textwall.php

* Record incoming SMS to a webpage and display sender name and the message content

### example4_balancealert.php

* Get your account information and process the balance information
* If you balance is below a certain amount, email yourself

### example5_clicktocall.php

* A website that allows you to insert your phone number to receive a phone call

### example12_showcosts.php

* A script that parses your accounts information to print out the cost of your virtual phone numbers

### example13_smshistory.php

* A script that fetches and prints your SMS history