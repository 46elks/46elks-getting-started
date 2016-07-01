import requests

auth = (
    '<API Username>;',
    '<API Password>;'
    )

fields = {
    'from': '+46766861234',
    'to': '+46723175800',
    'voice_start': '{"connect":"+461890510"}'
    }

response = requests.post(
    "https://api.46elks.com/a1/Call",
    data=fields,
    auth=auth
    )

print(response.text)