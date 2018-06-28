import requests, json

# Retrieves outgoing SMS and sorts them by sender, counting the ammount of sms
# and the total number of parts.
#
# Works in both python 2.7 and 3.X but requires the requests library
#
# Add your username and password from the 46elks dashboard.

username = "u********************************"
password = "********************************"
start = "2019-01-02T15:04:05.00"
end = "2006-01-02T15:04:05.00"

sms_list = []

while True:
    r = requests.get("https://api.46elks.com/a1/SMS?start={}&end={}".format(start, end), auth=(username, password))
    j = json.loads(r.text)
    sms_list.extend(j["data"])
    #Check if there are more pages and update the start value if needed
    if not "next" in j:
        break
    else:
        start = j["next"]

out = {}
for sms in sms_list:
    if sms["direction"] != "outgoing":
        continue

    if sms["from"] not in out:
        out[sms["from"]] = {"ammount":1, "parts":sms["parts"], "last":sms["created"], "first":sms["created"]}

    else:
        out[sms["from"]]["ammount"] += 1
        out[sms["from"]]["parts"] += sms["parts"]
        out[sms["from"]]["first"] = sms["created"]

for key in out:
    print("sender: {}, messages: {}, parts: {}, first sms: {}, last sms: {}".format(key, out[key]["ammount"], out[key]["parts"], out[key]["first"], out[key]["last"]))
