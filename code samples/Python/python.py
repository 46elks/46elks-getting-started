import requests

auth = (
    '<API Username>',
    '<API Password>'
    )

fields = {
    'from': 'Hello',
    'to': '+46723175800',
    'message': 'Test Message To your phone.'
    }

response = requests.post(
    "https://api.46elks.com/a1/SMS",
    data=fields,
    auth=auth
    )

print(response.text)