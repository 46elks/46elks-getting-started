# WebRTC

WebRTC stands for ”Web Real-Time Communication” and enables communication between browsers over audio, video and text.

In this example we have build a communication stream between a browser and a regular phone. With this code you can:

- Call from your browser to a regular phone number
- Receive calls in you browser made form a regular phone number

## Getting started
1. Create an [account](https://46elks.com/register) at 46elks.
2. Get a [virtual number](https://46elks.com/guides/get-virtual-number).
3. Get a WebRTC number. Ask our [support](https://46elks.com/support) to create one for you.
4. [Configure voice_start](https://46elks.com/guides/configure-voice-start) on your virtual number. Set `{"connect" : "<your_webrtc_number>"}`.

## Add your credentials

1. Add your [API credentials](https://46elks.com/account) in `forward.php`
```
$username = "API_USERNAME";
$password = "API_PASSWORD";
```

2. Add your [WebRTC credentials](https://46elks.com/guides/find-webrtc-credentials) in `script.js`
```
var inoutnumber  	= 'YOUR_VIRTUAL_NUMBER';
var webrtcUser 		= 'WEBRTC_USER';
var webrtcPass 		= 'WEBRTC_PASS'; 
```


## Try it out

1. Make a call to your virtual number from your phone. The call will soon appear in your browser.
2. Make a call to your regular phone number from your browser. Your phone will soon start ringing.

## Documentation
- [JsSip](https://jssip.net/documentation/3.7.x/getting_started/) (v. 3.7.x)
- [46elks WebRTC client](https://46elks.se/docs/webrtc-client-connect) 