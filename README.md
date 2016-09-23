![46elks-logo](https://www.46elks.com/images/README-on-github/46elks-240-150.png)

## Voice, SMS & MMS for developers.

Welcome to our developer friendly API service! :-)

We make it easy for you to add custom telephony features that are perfectly suited to the way you do things – because sometimes ‘off the shelf’ just doesn't cut it.

You can use [46elks](https://www.46elks.com) for:

* Sending SMS text messages
* Receiving SMS to your applications
* Sending & receiving MMS pictures
* Controlling incoming and outgoing telephone calls with code
* Building interactive voice sessions
* And other telephony [features](https://www.46elks.com/features).


### Links

  * [Quickstart](https://github.com/46elks/46elks-getting-started#sample-code)
  * [API documentation](https://www.46elks.com/api-docs#introduction)
  * [Code examples](https://github.com/46elks/46elks-getting-started/blob/master/README.md#code-examples-in-different-languages)
  * [Login / sign up](https://dashboard.46elks.com/)
  * [Introduction to using an API](https://zapier.com/learn/apis/) 
  * [Contact the elks](46elks.com/help#contact)

## Quickstart

1. Create your [46elks account](https://www.46elks.com/create-account) and locate your [api keys](https://dashboard.46elks.com/).
2. Send your first SMS from the [dashboard](https://dashboard.46elks.com/) or with curl (see below).
3. Send an SMS code using one of our [code examples](https://github.com/46elks/46elks-getting-started/tree/master/code-examples).
4. Learn more about what you can do by reading the [documentation](https://46elks.com/docs).


## Send your first SMS with curl

Use your [api keys](https://dashboard.46elks.com) and enter this into your terminal:

```
curl -X POST \
  -u <API-Username>:<API-Password> \
  -H 'Content-Type: application/x-www-form-urlencoded' \
  -d 'from=Victoria' \
  -d 'to=+46766861004' \
  -d 'message=Test Message To your phone.' \
  'https://api.46elks.com/a1/SMS' 
``` 

![sms-on-mobile-phone](https://static.46elks.com/sms-iphone-hello-416x85.png)


<br>
##### Code examples

[C](code-examples/c) -
[C# | .NET](https://github.com/46elks/46elks-getting-started/tree/master/code-examples/c-sharp) -
[Elixir](https://github.com/46elks/46elks-getting-started/blob/master/code-examples/Elixir) -
[Go](https://github.com/46elks/46elks-getting-started/blob/master/code-examples/Go) -
[Google apps](https://github.com/46elks/46elks-getting-started/blob/master/code-examples/Google%20apps%20script) -
[haskell](https://github.com/46elks/46elks-getting-started/blob/master/code-examples/haskell) -
[HTML](https://github.com/46elks/46elks-getting-started/blob/master/code-examples/HTML) -
[PHP](https://github.com/46elks/46elks-getting-started/blob/master/code-examples/PHP) -
[Python](https://github.com/46elks/46elks-getting-started/blob/master/code-examples/Python) -
[Ruby](https://github.com/46elks/46elks-getting-started/blob/master/code-examples/Ruby) -
[Java]( https://github.com/46elks/46elks-getting-started/tree/master/code-examples/Java) -
[Node.js](https://github.com/46elks/46elks-getting-started/tree/master/code-examples/Node) -
[cURL](https://github.com/46elks/46elks-getting-started/tree/master/code-examples/cURL)

##### Build an interactive voice menu or response (IVR)

[IVR samples collection](https://github.com/46elks/46elks-getting-started/tree/master/code-examples/Voice%20-%20IVR%20-%20interactive%20voice%20menues).

[Show all coding examples for all languages](https://github.com/46elks/46elks-getting-started/tree/master/code-examples)

## Community resources
*Tutorials*

* [Love messenger](https://github.com/gish/love-messenger) by Erik Hedberg
* [Receive SMS into Google spreadsheet](https://medium.com/@46elks/receive-sms-into-google-spreadsheet-435b51393493#.9ku01h462)

*Modules, SDKs and libraries*

* [Ruby client](https://github.com/jage/elk) by Johan Eckerström
* [Wordpress module](https://github.com/tobiasehlert/WP-SMS-46elks) by Tobias Ehlert
* [Codeigniter extension](https://github.com/nyfagel/codeigniter-46elks) by Jan Lindblom
* [Node.JS wrapper](https://github.com/leomelin/fortysix-elks) by Leo Melin

Have you written a tutorial or created an app that you're willing to share?
Let us know at hello@46elks.com!

## Demos
* [passer](https://github.com/46elks/passer) - Self-hosted "sms to twitter" written in Python
* [elkme](https://github.com/46elks/elkme) - Send SMS from the command line

## Resources
* [Postman app](https://www.getpostman.com/) - Interact with APIs through a Chrome app, also available as an OSX app.
  
## Integrations
  * [Zapier](https://zapier.com/zapbook/46elks/)
  * [Microsoft Excel](https://excel.46elks.com/)
  * Google spreadsheet [Google Script code sample](https://github.com/46elks/SMStoGoogleSheets) | Tutorial: [Receive sms into Google spreadsheet](https://medium.com/@46elks/receive-sms-into-google-spreadsheet-435b51393493#.iu690j86w)
  * [Hubot chat bot](https://github.com/github/hubot-scripts/blob/master/src/scripts/46elks.coffee)

# Hackathons
  We are keen on helping out, and we'd be happy to help you with preparations, mentoring and give away boosted credit packages for participants. [Talk to us](mailto:hello@46elks.com). We're also particularily interested in helping events and organizations working with teaching coding to beginners, initiatives that encourage diversity in IT, and among conferences. We enjoy meetups, let us know if you’d like for someone to come and hold a presentation about APIs - getting started with apis, how to send an sms in PHP/Python/Elixir/Ruby/Go or another language - we love to learn and share!  We exist to help you build useful and cool things!

**Contact 46elks**  
Email: hello@46elks.com
On twitter: [@46elks](https://twitter.com/46elks)  
