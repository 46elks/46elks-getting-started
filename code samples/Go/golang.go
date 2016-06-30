package main

import "net/url"
import "io/ioutil"
import "fmt"
import "net/http"
import "bytes"
import "strconv"

func main() {

    fmt.Println("I will now try to send a Message!")

    data := url.Values{
        "from": {"FromGO"},
        "to": {"+46700000000"},
        "message":{"Hej på dig!"}}

    req, err := http.NewRequest("POST", "https://api.46elks.com/a1/SMS", bytes.NewBufferString(data.Encode()))
    req.Header.Add("Content-Type", "application/x-www-form-urlencoded")
    req.Header.Add("Content-Length", strconv.Itoa(len(data.Encode())))
    req.SetBasicAuth("<API Username>", "<API Password>")

    client := &http.Client{}
    resp, err := client.Do(req)

    defer resp.Body.Close()
    body, err := ioutil.ReadAll(resp.Body)

    if err != nil {
        fmt.Println("Oh dear!!!")
    }

    fmt.Println(string(body))

}