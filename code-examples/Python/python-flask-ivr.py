import json
from flask import Flask
from flask import request

app = Flask(__name__)

@app.route("/incomingCalls", methods=['GET','POST'])
def home():
    print(request.form)
    response = {
        "ivr": "https://46elks.com/static/sound/ivr-menu.mp3",
        "digits": 1,
        "skippable":True,
        "next":"https://www.yourserver.com/ivr"
    return json.dumps(response)

@app.route("/ivr", methods=['POST'])
def calls_in():
    print(request.form)
    if request.form['result'] == "1":
        voice_start = {
            'play': "https://www.46elks.com/static/sound/voiceinfo.mp3"
        }
        return json.dumps(voice_start)
    elif request.form['result'] == "2":
        voice_start = {
            'play': "https://www.46elks.com/static/sound/smsinfo.mp3",
        }
        return json.dumps(voice_start)

if __name__ == "__main__":
    app.run(host='0.0.0.0', port=5000)