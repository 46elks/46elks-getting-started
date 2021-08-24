![46elks-logo](../../code-examples/logo-240x150.png)

# Demos in this repo

Here is a brief explanation of the demos in this repo and what you need to do to get started 🎉

## balance-alert.php

Get [your account information](https://46elks.com/docs/get-account) and process the balance information. If you balance is below a certain amount send an email yourself. You could set a cron job on this script to check your balance as frequently as you need.

1. Change `$username` and `$password` to your own [credentials](https://46elks.com/guides/find-api-credentials).
2. Add your email adress.

## call-and-connect.php

[Make a phone call](https://46elks.com/docs/make-call) to a number. If connected, attempt to [connect](https://46elks.com/docs/voice-connect) to another number.

1. Allocate a number using the [/Numbers REST resource](https://46elks.com/docs/allocate-number) or via the [dashboard](https://46elks.com/guides/get-virtual-number).
2. Change `$username` and `$password` to your own [credentials](https://46elks.com/guides/find-api-credentials).
3. Assign `from` to your allocated number. Assign `to` to your regular phone number. Assign `connect` to the number of the person you want to call.
4. Run the script and your phone will start ringing.
5. Answer the call.
6. When answered, the call will connect to the person you want to call.

## call-and-play.php

[Make a phone call](https://46elks.com/docs/make-call) to a number. If connected, [play a sound file](https://46elks.com/docs/voice-play) from a URL.

1. Allocate a number using the [/Numbers REST resource](https://46elks.com/docs/allocate-number) or via the [dashboard](https://46elks.com/guides/get-virtual-number).
2. Change `$username` and `$password` to your own [credentials](https://46elks.com/guides/find-api-credentials).
3. Assign `from` to your allocated number. Assign `to` to your regular phone number. Assign `play` to the URL of the sound file.
4. Run the script and your phone will start ringing.
5. Answer the call and you will hear the sound play.

## click-to-call.php

Same as [call-and-connect](#call-and-connectphp). In this demo the code is added to a website that allows you to enter your phone number in a field to receive a call and connect it.

## group-sms.php
Set up a group messaging function. Within the same script you can create multiple SMS groups and send dynamic messages to each member within the group.

1. Allocate one or more numbers using the [/Numbers REST resource](https://46elks.com/docs/allocate-number) or via the [dashboard](https://46elks.com/guides/get-virtual-number).
2. Assign a group for each allocated number. *(see example in the PHP-file)*. 
3. Assign each group a list of members by adding name and number. Be sure to add own name and number if you want to be a part of the group conversation. *(see example in the PHP-file)*.
4. Change `$username` and `$password` to your own [credentials](https://46elks.com/guides/find-api-credentials).
5. Configure [`sms_url`](https://46elks.com/docs/configure-number) on the number you allocated previously, with your script URL.
6. Send a text from your phone to one of your allocated number, e.g `+46761070124` and every member within that group will receive your text.

## opening-hours.php

Let your server receive incoming SMS from your customers and respond with opening hours for your business/service etc.

1. Allocate a number using the [/Numbers REST resource](https://46elks.com/docs/allocate-number) or via the [dashboard](https://46elks.com/guides/get-virtual-number).
2. Configure [`sms_url`](https://46elks.com/docs/configure-number) on the number you just allocated, with your script URL.
3. Send a text to your allocated number and you will get a text back with the opening hours.

You can write anything in your text. For instance; ”When are you open?”, ”Hello”, ”Open” etc. You could also configure the script to send different responses based on the content within the text. For instance ”today” could send todays opening hours, ”tomorrow” could send tomorrows opening hours and ”Sunday” could send the Sundays opening hours.

## php-curl-calls.php

A basic example of an [outgoing phone call](https://46elks.com/docs/make-call) with PHP where the post request is using cURL.

## php-curl-sendsms.php

A basic example of [sending SMS](https://46elks.com/docs/send-sms) with PHP where the post request is using cURL.

## receive-phonecall-after-hours.php

[Receive incoming phone calls](https://46elks.com/docs/receive-call) on your server. If the call is received between opening hours, transfer the call to the business owner. If not, [play an audio file](https://46elks.com/docs/voice-play) informing the caller you are closed. Also [send an SMS](https://46elks.com/docs/send-sms) to inform the business owner of the missed call.

1. Allocate a number using the [/Numbers REST resource](https://46elks.com/docs/allocate-number) or via the [dashboard](https://46elks.com/guides/get-virtual-number).
2. Configure [`voice_start`](https://46elks.com/guides/configure-voice-start) on the number you just allocated, with your script URL.
3. Add the URL of your audio file in the script.
4. Add the number of the business owner in the script.
5. Test the solution by calling your allocated number from your regular phone.

## receive-phonecall-send-email.php

Be notified by email when someone tried to call you.

1. Allocate a number using the [/Numbers REST resource](https://46elks.com/docs/allocate-number) or via the [dashboard](https://46elks.com/guides/get-virtual-number).
2. Add your email in the script.
3. Configure [`voice_start`](https://46elks.com/guides/configure-voice-start) on the number you allocated, with your script URL.
4. Make a call from your regular phone to your allocated number and you will get an email.

## receive-sms-send-email.php 

[Receive an incoming SMS](https://46elks.com/docs/receive-sms) on your sever and send an email to yourself with contents of the message.

1. Allocate a number using the [/Numbers REST resource](https://46elks.com/docs/allocate-number) or via the [dashboard](https://46elks.com/guides/get-virtual-number).
2. Add your email in the script.
3. Configure [`sms_url`](https://46elks.com/docs/configure-number) on the number you allocated, with your script URL.
4. Send a text to your allocated number and you will get an email with the SMS contents.

## send-sms-notion.php

This script will get a list of phone numbers from a Notion database and send an SMS to each number.

For more info, please see our tutorial [Send SMS from Notion](https://46elks.com/tutorials/send-sms-notion).

## send-sms.php

A basic example of [sending SMS](https://46elks.com/docs/send-sms).

1. Change `$username` and `$password` to your own [credentials](https://46elks.com/guides/find-api-credentials).
2. Add the number you want to send the SMS to and include a message. 
3. Run your script.

## show-costs.php

A script that parses [your accounts information](https://46elks.com/docs/get-account) to print out the cost of your virtual phone numbers.

1. Change `$username` and `$password` to your own [credentials](https://46elks.com/guides/find-api-credentials).

## sms-history.php

Fetch and print your entire [SMS history](https://46elks.com/docs/sms-history) or fetch SMS from specific dates with the parameters `start` and `end`.

1. Change `$username` and `$password` to your own [credentials](https://46elks.com/guides/find-api-credentials).
2. Define `start` and `end` if needed.

## text-wall.php

Save [incoming SMS](https://46elks.com/docs/receive-sms) to a textfil and display the message content along with the sender on a webpage.

1. Allocate a number using the [/Numbers REST resource](https://46elks.com/docs/allocate-number) or via the [dashboard](https://46elks.com/guides/get-virtual-number).
2. Configure [`sms_url`](https://46elks.com/docs/configure-number) on the number you just allocated, with your script URL.
3. Be sure your web server has write access to the directory in which you want to save the text file.
4. Open up your script in a browser and send an SMS to your allocated number. You will see the SMS appear on the screen.

## voicemail.js

Create a custom voicemail.

1. Allocate a number using the [/Numbers REST resource](https://46elks.com/docs/allocate-number) or via the [dashboard](https://46elks.com/guides/get-virtual-number).
2. Add the URL of your voicemail audio file in the script.
3. Configure [`voice_start`](https://46elks.com/guides/configure-voice-start) on the number you allocated, with your script URL.
4. Call your allocated number.

## weekly-cron-example.php

Send a reoccurring SMS every week (or every day, every third day, every month. Whatever suits you the best 😊).

1. Change `$username` and `$password` to your own [credentials](https://46elks.com/guides/find-api-credentials).
2. Add recipients to receive the SMS.
3. Add a cron job on your server to call this script as frequently as you wish.

## when-hangup.php

A simple example on what to do when the call is [hung up](https://46elks.com/docs/voice-hangup).

1. Change `$username` and `$password` to your own [credentials](https://46elks.com/guides/find-api-credentials).
2. Add your own instructions on what to do when the call is hung up.

## whisper.js

Whisper is the common term for playing a message before transferring a call to the actual destination.

1. Allocate a number using the [/Numbers REST resource](https://46elks.com/docs/allocate-number) or via the [dashboard](https://46elks.com/guides/get-virtual-number).
2. Configure [`voice_start`](https://46elks.com/guides/configure-voice-start) on the number you allocated, with your script URL.
3. Add the URL of your audio file in the script.
4. Call your allocated number.
