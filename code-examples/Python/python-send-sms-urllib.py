import urllib.parse
import urllib.request
from base64 import b64encode
from urllib.error import HTTPError, URLError

elks_username = "<API username>"
elks_password = "<API password>"

auth_bytes = f"{elks_username}:{elks_password}".encode()
auth_token = b64encode(auth_bytes).decode()
headers = {
    "Authorization": f"Basic {auth_token}"
}

data = urllib.parse.urlencode([
    ("from", "UrllibSMS"),
    ("to", "+46700000000"),  # XXX: change this!
    ("message", "Hello from urllib! 📡"),
])

req = urllib.request.Request(
    "https://api.46elks.com/a1/sms",
    data=data.encode(),
    headers=headers,
    method="POST"
)

try:
    response = urllib.request.urlopen(req)
except HTTPError as e:
    print(e.code, e.reason)
    print(e.read().decode())
except URLError as e:
    print("Could not reach server.")
    print(e.reason)
else:
    print(response.read().decode())
