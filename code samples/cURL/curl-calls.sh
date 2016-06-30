curl -X POST \
  -u <API Username>:<API Password> \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "from=+46766861234" \
  -d "to=+358503672181" \
  -d 'voice_start={"connect":"+461890510"}' \
  'https://api.46elks.com/a1/Calls'