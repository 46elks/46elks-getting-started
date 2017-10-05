#include <stdlib.h>
#include <stdio.h>
#include <stdbool.h>
#include <string.h>
#include <errno.h>
#include <curl/curl.h>
#include "elks.h"

int main(int argc, char* argv[]) {
    size_t sms_size = 160;
    char* message = calloc(sms_size+1, sizeof(char));
    size_t counter = 0;

    if (argc < 3) {
        fprintf(stderr, "Usage: %s <RECIPIENT: +46700000000> <MESSAGE...>\n",
                argv[0]);
        return EINVAL;
    }

    for (int i = 2; i < argc; i++) {
        char* word = argv[i];
        size_t word_len = strlen(word);
        if (counter + word_len + 1 > sms_size) {
            fprintf(stderr,
                "Payload too big, should be at most %ld bytes\n",
                (long) sms_size);
            return EOVERFLOW;
        }
        strncpy(message+counter, word, word_len);
        counter += word_len;
        if (i != argc-1) {
            message[counter] = ' ';
            counter++;
        }
    }
    char* from = "CMOSe";
    char* to = argv[1];
    if (verify_phonenumber(to)) {
        elks_api_connect(message, to, from);
    }
    free(message);
}

bool verify_phonenumber(char* number) {
    /* A phonenumber might be a phonenumber if it starts with a plus
     * and has only digits following the initial plus
     */
    if (strlen(number) < 2) {
        return false;
    } else if (number[0] != '+' && number[0] != ' ') {
        return false;
    } else {
        size_t len = strlen(number);
        for (int i = 1; i < len; i++) {
            if (number[i] >= '0' && number[i] <= '9') {
                continue;
            } else {
                return false;
            }
        }
        return true;
    }
}

void elks_api_connect(char* message, char* to, char* from) {
    int http_code;
    void* head_buffer = calloc(1, sizeof(string_buffer));
    CURLcode res;
    CURL* curl = curl_easy_init();

    if (!curl) {
        return;
    }

    curl_easy_setopt(curl, CURLOPT_URL, "https://api.46elks.com/a1/SMS");
#ifndef DEBUG
    FILE* devnull = fopen("/dev/null", "w");
    curl_easy_setopt(curl, CURLOPT_WRITEDATA, devnull);
    printf("\n");
#endif
    curl_easy_setopt(curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_easy_setopt(curl, CURLOPT_USERNAME, curl_getenv("ELKS_USERNAME"));
    curl_easy_setopt(curl, CURLOPT_PASSWORD, curl_getenv("ELKS_PASSWORD"));
    char* formatted_message = curl_easy_escape(curl, message, strlen(message));
    char* formatted_to = curl_easy_escape(curl, to, strlen(to));
    char* formatted_from = curl_easy_escape(curl, from, strlen(from));

    char* payload = calloc(
        1,
        strlen(formatted_message)*3 + strlen(to)*3 + strlen(from)*3
    );
    sprintf(payload, "to=%s&from=%s&message=%s", formatted_to, formatted_from,
            formatted_message);
    curl_easy_setopt(curl, CURLOPT_POSTFIELDS, payload);
    curl_free(formatted_message);
    curl_free(formatted_to);
    curl_free(formatted_from);

    res = curl_easy_perform(curl);

    curl_easy_getinfo (curl, CURLINFO_RESPONSE_CODE, &http_code);
    free(payload);
    curl_easy_cleanup(curl);
#ifndef DEBUG
    fclose(devnull);
#endif

    if (http_code == 401) {
        printf("AUTHENTICATION ERROR\n");
        if (!curl_getenv("ELKS_USERNAME")) {
            printf("Set the environment variables ELKS_USERNAME and ");
            printf("ELKS_PASSWORD to your API public and secret keys\nfrom ");
            printf("https://dashboard.46elks.com/\n");
        } else {
            printf("Wrong ELKS_USERNAME or ELKS_PASSWORD.\n");
            printf("Go to https://dashboard.46elks.com/ and verify that ");
            printf("you are using the correct credentials");
        }
    } else if (http_code == 200) {
        printf("Sent, check the recipient phone!\n");
    }
    return;
}

