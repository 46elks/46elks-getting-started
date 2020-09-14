# This script that uses information from a pretend database to send an SMS

import requests

auth = ("api_user", "api_pass")

database = {"customer1":{"quoteid": 124353, "url":"bestquote.com/quote?id=254654645693245", "number":"+46..."},
"customer2":{"quoteid": 1243452323, "url":"bestquote.com/quote?id=293725464354354245","number":"+46..."},
"customer3":{"quoteid": 124344, "url":"bestquote.com/quote?id=2937324346546", "number":"+46..."}}


for customer in database:
    response = requests.post(
    'https://api.46elks.com/a1/sms',
    auth = auth,
    data = {
        'from': 'quoteCOM',
        'to': database[customer]["number"],
        'message': "Quote now availabe at :" + database[customer]["url"]
    }
    )

    print(response)
    print(response.text)
  