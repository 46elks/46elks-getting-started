using System;
using RestSharp;

public class ElkSendSMS
{
    static public void Main ()
    {
        Console.WriteLine ("Trying to send");

        var client = new RestClient("https://api.46elks.com/a1/SMS");
        var request = new RestRequest(Method.POST);
        
        client.Authenticator = new RestSharp.Authenticators.
            HttpBasicAuthenticator(
                "<API Username>",
                "<API Password>");
                    
        request.AddParameter(
            "to", "+46723175800",
            ParameterType.GetOrPost); 
                        
        request.AddParameter(
            "from", "+46766862078",
             ParameterType.GetOrPost);
                        
        request.AddParameter(
            "message", 
            "Hi! This is a message from me! Have åäö a nice day!", 
            ParameterType.GetOrPost);
                        
        IRestResponse response = client.Execute(request);
        Console.WriteLine(response.Content);
    }
}
