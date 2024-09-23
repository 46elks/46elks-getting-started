import envoy
import gleam/bit_array
import gleam/hackney
import gleam/http
import gleam/http/request
import gleam/http/response.{type Response}
import gleam/int
import gleam/io
import gleam/result
import gleam/string
import gleam/uri

type Auth {
  Auth(username: String, password: String)
}

type Sms {
  Sms(from: String, to: String, message: String)
}

fn send_sms(auth: Auth, sms: Sms) -> Result(Response(String), hackney.Error) {
  let b64_auth =
    { auth.username <> ":" <> auth.password }
    |> bit_array.from_string
    |> bit_array.base64_encode(False)

  let auth_header = "Basic " <> b64_auth

  let body =
    uri.query_to_string([
      #("from", sms.from),
      #("to", sms.to),
      #("message", sms.message),
    ])

  let req =
    request.new()
    |> request.set_scheme(http.Https)
    |> request.set_host("api.46elks.com")
    |> request.set_path("/a1/sms")
    |> request.set_method(http.Post)
    |> request.set_header("authorization", auth_header)
    |> request.set_body(body)

  hackney.send(req)
}

pub fn main() {
  let api_username = envoy.get("ELKS_APIUSER") |> result.unwrap("")
  let api_secret = envoy.get("ELKS_APISECRET") |> result.unwrap("")
  let auth = Auth(api_username, api_secret)

  let sms =
    Sms(
      from: "GleamingElk",
      to: "+46700000000",
      message: "The quick, gleaming elk jumped over the cold river.",
    )

  case send_sms(auth, sms) {
    Ok(response) -> {
      io.println_error("Status: " <> response.status |> int.to_string)
      io.println(response.body)
    }
    Error(err) -> io.println_error("Error: " <> string.inspect(err))
  }
}
