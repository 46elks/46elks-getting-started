import org.apache.http.auth.AuthScope;
import org.apache.http.auth.UsernamePasswordCredentials;
import org.apache.http.client.CredentialsProvider;
import org.apache.http.client.methods.CloseableHttpResponse;
import org.apache.http.client.methods.RequestBuilder;
import org.apache.http.impl.client.BasicCredentialsProvider;
import org.apache.http.impl.client.CloseableHttpClient;
import org.apache.http.impl.client.HttpClients;
import org.apache.http.client.methods.HttpUriRequest;
import java.net.URI;
import java.nio.charset.Charset;

public class ElkTest {
    public static void main(String[] args) {
        try {
        			
            CredentialsProvider credsProvider = new BasicCredentialsProvider();
            credsProvider.setCredentials(
                new AuthScope("api.46elks.com", 443),
                new UsernamePasswordCredentials(
                    "&#60;API Username&#62",
                    "&#60;API Password&#62"));
                        
            CloseableHttpClient httpclient = HttpClients.custom()
                .setDefaultCredentialsProvider(credsProvider)
                .build();

            HttpUriRequest postrequest = RequestBuilder.post()
                .setUri(new URI("https://api.46elks.com/a1/Calls"))
                .addParameter("from", "elkme")
                .addParameter("to", "+46723175800")
                .addParameter("voice_start", '{"connect":"+461890510"}')
                .build();

             CloseableHttpResponse response = httpclient.execute(postrequest);
                
             System.out.println(response.getStatusLine());
             
             response.close();
             httpclient.close();
        } catch (Exception e){
             System.out.println(e);
        }
    }
}