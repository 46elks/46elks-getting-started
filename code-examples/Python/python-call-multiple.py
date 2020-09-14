# This is a test script calls multiple people at the same time with one phone call

from bottle import request, post, run, get
import requests
import json

baseURL = "https://YOUR-SERVER"

@post('/incomingCall')
def calls():
    print(" ----------------- Call coming in -----------------")
    j = {"connect":'+46709695839,+46762953010',
    "whenhangup":baseURL + "/whenhangup"}
    return json.dumps(j)
    
@post('/whenhangup')
def whenhangup():
    print(request.body.readlines())
    return ""

run(host='0.0.0.0', port=5000)