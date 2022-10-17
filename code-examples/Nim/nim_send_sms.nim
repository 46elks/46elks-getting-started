import std/[base64, httpclient, uri]

let
  username = "<api username>"
  secret = "<api password>"
  b64credentials = encode(username & ":" & secret)
  headers = newHttpHeaders({
    "Authorization": "Basic " & b64credentials,
    "Content-Type": "application/x-www-form-urlencoded"
  })
  data = encodeQuery({
    "from": "NimElk",
    "to": "+46700000000",
    "message": "We are the Elks who say \"Nim!\""
  })

let client = newHttpClient()
client.headers = headers
let response = client.post("https://api.46elks.com/a1/sms", body = data)
echo response.status
echo response.body
