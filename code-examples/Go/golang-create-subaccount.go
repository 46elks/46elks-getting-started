package main

import "net/url"
import "io/ioutil"
import "fmt"
import "net/http"
import "bytes"
import "strconv"

func main() {

    fmt.Println("I will now try to create a new subaccount!")

    data := url.Values{
        "name": {"Example subaccount"},
    }

    req, err := http.NewRequest("POST", "https://api.46elks.com/a1/Subaccounts", bytes.NewBufferString(data.Encode()))
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
