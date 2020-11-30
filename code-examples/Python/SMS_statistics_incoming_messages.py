import requests, json

# Retrieves incoming SMS messages and groups them by the phone number they were sent to.
#
# Works in both python 2.7 and 3.X, requires the requests library.
#
# Add your username and password from the 46elks dashboard.

username = ""
password = ""
sms_list = []

while True:
    r = requests.get("https://api.46elks.com/a1/SMS", auth=(username, password))
    j = json.loads(r.text)
    sms_list.extend(j["data"])
    #Check if there are more pages and update the start value if needed
    if not "next" in j:
        break
    else:
        start = j["next"]

output = {}
for sms in sms_list:
    if sms["direction"] == "incoming":

        if sms["to"] not in output:
            output[sms["to"]] = {"amount":1 }

        else:
            output[sms["to"]]["amount"] += 1

print ( "Incoming messages: ")
for key in output:
    print("To: {}, messages: {}".format( key, output[key]["amount"] ))

print ("Done!")
