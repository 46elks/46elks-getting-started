use reqwest;
use reqwest::header;

fn main() -> Result<(), Box<dyn std::error::Error>> {
    let mut headers = header::HeaderMap::new();
    headers.insert("Content-Type", "application/x-www-form-urlencoded".parse().unwrap());

    let client = reqwest::blocking::Client::builder()
        .redirect(reqwest::redirect::Policy::none())
        .build()
        .unwrap();
    let res = client.post("https://api.46elks.com/a1/sms")
        .basic_auth("YOUR USERNAME", Some("YOUR PASSWORD"))
        .headers(headers)
        .body("from=46ELKS&to=+46700000000&message=Your Message here")
        .send()?
        .text()?;
    println!("{}", res);

    Ok(())
}
