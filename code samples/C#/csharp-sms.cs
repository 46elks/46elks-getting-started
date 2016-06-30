using System;
using System.Net.Http;
using System.Net.Http.Headers;
using System.Collections.Generic;
using System.Threading.Tasks;

namespace elktest {
    class Program {

        public static string user = "<API Username>";
        public static string pwd = "<API Password>";
        private static HttpClient client;
        static void Main(string[] args)

        {
            Task.Run(async() = > {
                using(client = new HttpClient()) {
                    client.BaseAddress = new Uri("https://api.46elks.com");

                    client.DefaultRequestHeaders.Authorization = new AuthenticationHeaderValue(
                        "Basic",
                        Convert.ToBase64String(
                            System.Text.ASCIIEncoding.ASCII.GetBytes(
                                string.Format("{0}:{1}", user, pwd))));

                    var content = new FormUrlEncodedContent(new[] {
                        new KeyValuePair < string, string > ("from", "+46723175800"),
                        new KeyValuePair < string, string > ("to", "+46735417172"),
                        new KeyValuePair < string, string > ("message", "Test 😊"),
                    });

                    HttpResponseMessage response = await client.PostAsync("/a1/SMS", content);
                    response.EnsureSuccessStatusCode();
                    var result = await response.Content.ReadAsStringAsync();
                }
            }).Wait();
        }
    }
}