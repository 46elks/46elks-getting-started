import HTTPotion.base

authdata = [basic_auth: {'<API-Username>',
                         '<API-Password>'}]

request = %{
            "from"    => "+46766861234", 
            "to"      => "+46723175800", 
            "message" => "{\"connect\":\"+4634090510\"}"
           }

request_data = URI.encode_query(request)

HTTPotion.start
HTTPotion.post("https://api.46elks.com/a1/Call",[body: request_data , ibrowse: authdata])
