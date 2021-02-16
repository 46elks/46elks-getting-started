import base64
import httpclient
import uri

let
  username = "<api username>"
  secret = "<api password>"
  data = {
    "from": "NimElk",
    "to": "+46700000000",
    "message": "We are the Elks who say \"Nim!\""
  }

let
  b64credentials = encode(username & ":" & secret)
  authHeader = "Basic " & b64credentials
  headers = {
    "Authorization": authHeader,
    "Content-Type": "application/x-www-form-urlencoded"
  }

let client = newHttpClient()
client.headers = newHttpHeaders(headers)
let encodedData = encodeQuery(data)
let response = client.request("https://api.46elks.com/a1/sms",
                              httpMethod = HttpPost, body = encodedData)
echo response.status
echo response.body
