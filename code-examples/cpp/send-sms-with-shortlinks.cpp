#define CPPHTTPLIB_OPENSSL_SUPPORT
#include "httplib.h"
#include <iostream>
#include <cstdlib>

int main() {
    httplib::SSLClient cli("api.46elks.com");

    cli.set_basic_auth("<API_USERNAME>", "<API_PASSWORD>");

    httplib::Params data = {
        {"from", "CppElk"},
        {"to", "+46720000000"},
        {"message", "This will be a shortlink: https://yourdomain.com/events/cool_workshop/signup"},
        {"shortlinks", "default"}
    };

    auto response = cli.Post("/a1/sms", data);

    if (response) {
        if (response->status == 200) {
            std::cout << response->body << std::endl;
        } else {
            std::cerr << response->body << std::endl;
        }
    } else {
        std::cerr << response.error() << std::endl;
    }

    return 0;
}
