# Gleam + 46elks

This directory contains examples of using the [46elks API](https://46elks.com/docs/)
with [Gleam](https://gleam.run/)!

## Running

    ELKS_APIUSER=yourapiusername ELKS_APISECRET=yourapisecret gleam run -m <module>

where `<module>` is any of the examples in `src/`, for example:

    export ELKS_APIUSER=yourapiusername
    export ELKS_APISECRET=yourapisecret
    gleam run -m send_sms

Note the lack of file extension.
