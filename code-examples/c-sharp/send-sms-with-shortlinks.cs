using System;
using System.Net.Http;
using System.Text;
using System.Threading.Tasks;

class Program
{
    static async Task Main(string[] args)
    {
        string apiUrl = "https://api.46elks.com/a1/sms";
        string apiUsername = "<API_USERNAME>";
        string apiPassword = "<API_PASSWORD>";

        // auth
        var client = new HttpClient();
        var authHeader = Convert.ToBase64String(Encoding.ASCII.GetBytes($"{apiUsername}:{apiPassword}"));
        client.DefaultRequestHeaders.Authorization = new System.Net.Http.Headers.AuthenticationHeaderValue("Basic", authHeader);

        // data
        var content = new FormUrlEncodedContent(new[]
        {
            new KeyValuePair<string, string>("from", "CsElk"),
            new KeyValuePair<string, string>("to", "+46720000000"),
            new KeyValuePair<string, string>("message", "This will be a shortlink: https://yourdomain.com/events/cool_workshop/signup"),
            new KeyValuePair<string, string>("shortlinks", "default")
        });

        var response = await client.PostAsync(apiUrl, content);
        string responseString = await response.Content.ReadAsStringAsync();
        Console.WriteLine(responseString);
    }
}
