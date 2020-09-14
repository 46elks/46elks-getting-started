# This script sends SMS and includes from of the optional parameters available when sending SMS

import requests

auth = (
    '<API Username>',
    '<API Password>'
    )
response = requests.post(
  'https://api.46elks.com/a1/sms',
  auth = auth,
  data = {
    'from': 'PythonElk',
    'to':"+46723175800",
    #'message': "It's cold outside, bring a sweater!",
    'whendelivered':'https://YOUR-SERVER/whendelivered', 
    'flashsms':"no",
    "dryrun":"no"
  })

print(response.text)