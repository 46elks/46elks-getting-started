package main

import "io/ioutil"
import "fmt"
import "net/http"

func main() {

    fmt.Println("I will now try to get the user informaton!")

    req, err := http.NewRequest("GET", "https://api.46elks.com/a1/me", nil)
    req.Header.Add("Content-Type", "application/x-www-form-urlencoded")
    req.SetBasicAuth("<API-username>", "<API-password>")

    client := &http.Client{}
    resp, err := client.Do(req)

    defer resp.Body.Close()
    body, err := ioutil.ReadAll(resp.Body)

    if err != nil {
        fmt.Println("Oh dear!!!")
    }

    fmt.Println(string(body))

}
