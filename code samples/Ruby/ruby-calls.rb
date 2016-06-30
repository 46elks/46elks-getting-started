require 'net/http'

uri = URI('https://api.46elks.com/a1/Calls')
req = Net::HTTP::Post.new(uri)
req.basic_auth '<API Username>', '<API Password>'
req.set_form_data(
  :from => '+46766861234',
  :to => '+46704508449',
  :voice_start => '{"connect":"+461890510"}'
)

res = Net::HTTP.start(
    uri.host,
    uri.port,
    :use_ssl => uri.scheme == 'https') do |http|    
  http.request req 
end

puts res.body