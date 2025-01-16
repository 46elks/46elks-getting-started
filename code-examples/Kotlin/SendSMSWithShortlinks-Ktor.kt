import io.ktor.client.*
import io.ktor.client.engine.cio.*
import io.ktor.client.request.*
import io.ktor.client.request.forms.*
import io.ktor.client.statement.*
import io.ktor.http.*
import io.ktor.util.*

suspend fun main() {
    val client = HttpClient(CIO)

    try {
        println("Sending SMS")

        val response: HttpResponse = client.submitForm(
            url = "https://api.46elks.com/a1/sms",
            formParameters = Parameters.build {
                append("to", "+46720000000")
                append("from", "KotlinElk")
                append("message", "This will be a shortlink: https://yourdomain.com/events/cool_workshop/signup")
                append("shortlinks", "default")
            }
        ) {
            headers {
                append(HttpHeaders.Authorization,
                    "Basic " + "<API_USERNAME>:<API_PASSWORD>"
                        .toByteArray().encodeBase64()
                )
            }
        }

        println("Response: ${response.status}")
        println("Body: ${response.bodyAsText()}")
    } catch (e: Exception) {
        println("Error: ${e.message}")
    } finally {
        client.close()
    }
}