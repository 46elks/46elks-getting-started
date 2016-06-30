import com.mashape.unirest.http.HttpResponse;
import com.mashape.unirest.http.Unirest;

public class ElksTest {
    public static void main(String[] args) {
        try {
            System.out.println("Sending SMS");

            HttpResponse<String> response = Unirest.post("https://api.46elks.com/a1/SMS")
                .basicAuth("<API Username>","<API Password>")
                .field("to", "+46723175800")
                .field("from", "+46766862078")
                .field("message", "Hi! This is åäö a message from me! Have a nice day!")
                .asString();
        
            System.out.println(response.getBody());
            }
                
        catch (Exception e){
            system.out.println(e);
        }
    }
}