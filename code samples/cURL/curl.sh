curl -X POST \
  -u <API Username>:<API Password> \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "from=VILLE" \
  -d "message=Test Message To your phone." \
  -d "to=+358503672181" \
  'https://api.46elks.com/a1/SMS'