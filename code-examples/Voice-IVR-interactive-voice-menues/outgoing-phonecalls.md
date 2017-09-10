
# Outgoing phone calls

## Trigger a phone call and play a sound when someone answers
Let's say you have a sound file at http://www.yourserver.com/magic-message-prerecorded.mp3

In this example we'll use: https://archive.org/download/MLKDream/MLKDream_64kb.mp3

1. Post a request to `https://api.46elks.com/a1/Calls`

Send in this data:
```
from: <YOUR PHONE NUMBER, ex +467........>
to: <The number you want to call, example: +46704508449>
voice_start: {"play":"http://www.yourserver.com/magic-prerecorded-message.mp3"}
```

## voice_start
`voice_start: <URL or json>`
You can also enter a url to your server, where you respond with a json.
[Documentation](https://www.46elks.com/docs#outgoing-calls)
