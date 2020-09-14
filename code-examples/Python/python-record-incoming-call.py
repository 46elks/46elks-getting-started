from bottle import request, post, run, get, static_file
import requests
import json 

baseURL = "https://YOUR-SERVER"

auth = ("api_user", "api_pass")

@post('/incomingCall')
def calls():
    j = {"play": baseURL + "/static/sound.mp3", 
        "next":
        {"record": baseURL + "/recordings"}}
    return json.dumps(j)

@post('/recordings')
def recordings():
    file_url = request.forms.get("wav")
    download_file(file_url)

def download_file(url):
    filename = "static/new-" + url.split('/')[-1]
    with requests.get(url, auth=auth, stream=True) as r:
        r.raise_for_status()
        with open(filename, 'wb') as f:
            for chunk in r.iter_content(chunk_size=8192):
                if chunk:
                    f.write(chunk)
    return filename

@get('/static/<path>')
def static(path):
    return static_file(path, "static")


run(reloader=True, host='0.0.0.0', port=5501)