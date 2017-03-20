# Get user information
curl https://<user>:<password>@api-local.46elks.com/a1/Me

# Set level for when credit alert e-mail to be sent, when balance goes below 500 (SEK/EUR)
curl -X POST \
 -u '<user>:<password>' \
 -H 'Content-Type: application/x-www-form-urlencoded' \
 -d 'creditalert=5000000' \
 'https://api.46elks.com/a1/Me'
