import org.apache.http.client.fluent.Content
import org.apache.http.client.fluent.Form
import org.apache.http.client.fluent.Request

@Grab(group = 'org.apache.httpcomponents', module = 'fluent-hc', version = '4.5.2')

def sendSms(String from, String to, String message) {
	def user = "API_USERNAME" //TODO Change it!
	def password = "API_PASSWORD" //TODO Change it!
	def credentials = "${user}:${password}"

	Content content = Request.Post("https://api.46elks.com/a1/SMS")
			.addHeader("Authorization", "Basic " + credentials.bytes.encodeBase64().toString())
			.addHeader("Content-Type", "application/x-www-form-urlencoded; charset=utf-8")
			.bodyForm(Form.form()
			.add("message", message)
			.add("to", to)
			.add("from", from)
			.build())

			.execute().returnContent()
}

Content content = sendSms("GroovyElk", "+34617112338", "Freshly brewed coffee is tasteful!")
System.out.println(content)
