import requests

auth = (
    '<API Username>',
    '<API Password>'
    )

recipients = ["+46700000000","+46700000000"]

for recipient in recipients:
  fields = {
      'from': 'PythonElk',
      'to': recipient,
      'message': 'Test Message To your phone.'
      }

  response = requests.post(
      "https://api.46elks.com/a1/SMS",
      data=fields,
      auth=auth
      )

  print(response.text)
