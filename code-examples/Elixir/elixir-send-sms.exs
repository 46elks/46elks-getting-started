import HTTPotion.base

authdata = [basic_auth: {'<API-Username>',
                         '<API-Password>'}]

request = %{
            "from"    => "Me", 
            "to"      => "+46723175800", 
            "message" => "Hej nu testar jag igen! Med alla bokstäver!"
           }

request_data = URI.encode_query(request)

HTTPotion.start
HTTPotion.post("https://api.46elks.com/a1/SMS",[body: request_data , ibrowse: authdata])
