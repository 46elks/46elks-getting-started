using System;
using System.Net.Http;
using System.Net.Http.Headers;
using System.Text;

public class ElkSendSMS
{
    static public void Main ()
    {
        Console.WriteLine ("Trying to send");
        var username = "<API Username>";
        var password = "<API Password>";
        var postData = new List<KeyValuePair<string, string>>()
            {
            new KeyValuePair<string, string>("from", "+4676865201"),
            new KeyValuePair<string, string>("to", "+46723175800"),
            new KeyValuePair<string, string>("voice_start", '{"connect":"+461890510"}')
            };
                                
        string creds = string.Format("{0}:{1}", username, password);
        byte[] bytes = Encoding.ASCII.GetBytes(creds);
        var header = new AuthenticationHeaderValue("Basic", Convert.ToBase64String(bytes));
        var content = new FormUrlEncodedContent(postData);
        
        HttpClient Client = new HttpClient();
        Client.DefaultRequestHeaders.Authorization = header;
            
        var responseMessage =  Client.Post("https://api.46elks.com/a1/Calls", content);
        var response = esponseMessage.Content.ReadAsString();
        Console.WriteLine (response);
    }
}