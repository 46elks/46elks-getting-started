require 'net/http'

uri = URI('https://api.46elks.com/a1/SMS')
req = Net::HTTP::Post.new(uri)
req.basic_auth '<API Username>', '<API Password>'
req.set_form_data(
  :from => 'TestElk',
  :to => '+46704508449',
  :message => 'Login code 123456'
)

res = Net::HTTP.start(
    uri.host,
    uri.port,
    :use_ssl => uri.scheme == 'https') do |http|    
  http.request req 
end

puts res.body