import Requests: readstring, post

message = "π = 3.1415… 🎂"

function api(endpoint)
  # Get your API keys on https://dashboard.46elks.com/
  username = "API USERNAME"
  password = "API PASSWORD"
  "https://$(username):$(password)@api.46elks.com/a1/$(endpoint)"
end

payload(message; from = "Julia", flash = false) =
  return Dict(["message" => message,
    "from" => from,
    "flashsms" => ifelse(flash, "yes", "no")])

function sms_to(message, recipient)
  message["to"] = recipient
  post(api("sms"), data=message)
end

⤇(message, recipient) = sms_to(message, recipient)
status = payload(message, flash=true) ⤇ "+46709784966"
println(readstring(status))
