use reqwest::blocking::Client;
use reqwest::Error;

fn main() -> Result<(), Error> {
    let client = Client::new();

    let data = [
        ("from", "PythonElk"),
        ("to", "+46723175800"),
        ("message", "Test Message To your phone."),
    ];

    let response = client
        .post("https://api.46elks.com/a1/sms")
        .basic_auth("<API Username>", Some("<API Password>"))
        .form(&data)
        .send();

    println!("{:?}", response);

    Ok(())
}
