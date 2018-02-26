'URL to open....
sUrl = "https://api.46elks.com/a1/sms"
'POST Request to send.
sRequest = "from=ElkCo&to=+46700000000&message=test"

HTTPPost sUrl, sRequest

Function HTTPPost(sUrl, sRequest)
  set oHTTP = CreateObject("Microsoft.XMLHTTP")
  oHTTP.open "POST", sUrl,false
  oHTTP.setRequestHeader "Content-Type", "application/x-www-form-urlencoded"

  'Visual basic script doesn't have a function to base46encode strings in the
  'standard library, use the encoded string of "username:password"
  oHTTP.setRequestHeader "Authorization", "Basic *base46 encoded string containing 'username:password'*"
  oHTTP.setRequestHeader "Content-Length", Len(sRequest)
  oHTTP.send sRequest
  HTTPPost = oHTTP.responseText
  WScript.Echo HTTPPost
 End Function
