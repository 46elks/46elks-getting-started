# This is a test script that receives incoming SMS and creates an entry in Airtable
# Installation instructions in the link below
# https://airtable-python-wrapper.readthedocs.io/en/master/

from bottle import request, post, run
import requests
from airtable import Airtable

airtable = airtable = Airtable("base_key", "table_name", "api_key")

def post_to_airtable(message, recipient, sender, created):
  airtable.insert({
    'Date': str(created),
    'To':str(recipient),
    'From': str(sender),
    'Message': str(message)
  })
  return "Success"

@post('/smsDemo')
def sms():
  message = request.forms.get("message")
  recipient = request.forms.get("to")
  sender = request.forms.get("from")
  created = request.forms.get("created")
  post_to_airtable(message, recipient, sender, created)
   
run(host='0.0.0.0', port=5501, reloader = True, quiet=True)