#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <curl/curl.h>

size_t write_callback(void *ptr, size_t size, size_t nmemb, char *data) {
    size_t real_size = size * nmemb;
    strncat(data, ptr, real_size);
    return real_size;
}

int main() {

    CURL *curl;
    CURLcode res;
    char response[4096] = "\0";

    const char *url = "https://api.46elks.com/a1/sms";
    const char *username = "<API_USERNAME>";
    const char *password = "<API_PASSWORD>";
    const char *to = "+46720000000";
    const char *from = "CElk";
    const char *message = "This will be a shortlink: https://yourdomain.com/events/cool_workshop/signup";
    const char *shortlinks = "default";

    char auth[256];
    snprintf(auth, sizeof(auth), "%s:%s", username, password);

    curl = curl_easy_init();
    
    curl_easy_setopt(curl, CURLOPT_URL, url);

    curl_easy_setopt(curl, CURLOPT_USERPWD, auth);

    char data[1024];
    snprintf(data, sizeof(data),
                "to=%s&from=%s&message=%s&shortlinks=%s",
                to, from, message, shortlinks);

    curl_easy_setopt(curl, CURLOPT_POSTFIELDS, data);

    curl_easy_setopt(curl, CURLOPT_WRITEFUNCTION, write_callback);
    curl_easy_setopt(curl, CURLOPT_WRITEDATA, response);

    res = curl_easy_perform(curl);

    if (res != CURLE_OK) {
        fprintf(stderr, "%s\n", curl_easy_strerror(res));
    } else {
        fprintf(stdout, "%s\n", response);
    }

    curl_easy_cleanup(curl);

    return 0;
}
