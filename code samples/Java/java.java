import java.net.URLEncoder;
import java.net.URL;
import java.net.URLConnection;
import java.io.OutputStreamWriter;
import java.io.BufferedReader;
import java.io.InputStreamReader;
import javax.xml.bind.DatatypeConverter;


class ElksDemo
{  
  static void sendSMS () {
    try {
        // Construct POST data
        String data = URLEncoder.encode("from", "UTF-8") + "=" + URLEncoder.encode("Mattias", "UTF-8");
        data += "&" + URLEncoder.encode("to", "UTF-8") + "=" + URLEncoder.encode("+46705569900", "UTF-8");
        data += "&" + URLEncoder.encode("message", "UTF-8") + "=" + URLEncoder.encode("Freshly brewed coffee is tasteful!", "UTF-8");
    
        // Make HTTP POST request
        URL url = new URL("https://api.46elks.com/a1/SMS");
        URLConnection conn = url.openConnection();

        String username = "u1234123412341234123412341234";
        String password = "ABCD1234ABCD1234ABCD1234ABCD1234";
        String base64string = DatatypeConverter.printBase64Binary((username + ":" + password).getBytes("UTF-8"));
        String basicAuth = "Basic " + base64string;
        conn.setRequestProperty("Authorization", basicAuth);
        conn.setRequestProperty("Content-Length", Integer.toString(data.getBytes("UTF-8").length));
        conn.setRequestProperty("Content-Type", "application/x-www-form-urlencoded");

        conn.setDoOutput(true);
        OutputStreamWriter wr = new OutputStreamWriter(conn.getOutputStream());
        wr.write(data);
        wr.flush();

        // Handle response data here (currently, just print out response)
        BufferedReader rd = new BufferedReader(new InputStreamReader(conn.getInputStream()));
        String line;
        while ((line = rd.readLine()) != null) {
          System.out.println(line);
        }
        wr.close();
        rd.close();
    } catch (Exception e) {
    }
  }

  public static void main(String args[])
  {
    System.out.println("Hello World!");
    sendSMS();
  }
}