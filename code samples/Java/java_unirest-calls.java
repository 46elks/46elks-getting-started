import com.mashape.unirest.http.HttpResponse;
import com.mashape.unirest.http.Unirest;

public class ElksTest {
    public static void main(String[] args) {
        try {
            System.out.println("Sending SMS");

            HttpResponse<String> response = Unirest.post("https://api.46elks.com/a1/Calls")
                .basicAuth("<API Username>","<API Password>")
                .field("to", "+46723175800")
                .field("from", "+46766862078")
                .field("voice_start", '{"connect":"+461890510"}')
                .asString();
        
            System.out.println(response.getBody());
            }
                
        catch (Exception e){
            system.out.println(e);
        }
    }
}