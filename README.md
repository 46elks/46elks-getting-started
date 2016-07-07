![46elks-logo](https://www.46elks.com/images/README-on-github/46elks-240-150.png)

# Phonecalls, SMS & MMS api

Welcome!

The 46elks api makes it easy for you to add custom telephony features that are perfectly suited to the way you do things – because let’s face it, sometimes ‘off the shelf’ just doesn't cut it.

You can use the [46elks](https://www.46elks.com) api to 

* send and receive text messages
* initiate and receive phonecalls
* build interactive voice sessions


### Links

  * [Quickstart](https://github.com/46elks/46elks-getting-started#sample-code)
  * [Documentation](https://www.46elks.com/api-docs#introduction)
  * [Sample code](https://github.com/46elks/46elks-getting-started/blob/master/README.md#code-examples-in-different-languages)
  * [Dashboard](http://dashboard.46elks.com/)
  * [Introduction - Learn about using an API](https://zapier.com/learn/apis/) 
  * [Contact 46elks](46elks.com/help#contact)


## Getting started

1. Get your credentials(api keys (username and password to connect to the api)). Create your [free 46elks account](https://www.46elks.com/create-account).
2. *Documentation* is available at [46elks.com/docs](https://46elks.com/docs).
3. You might want to dig right into *[Sample code](https://github.com/littlekid/testing-learning-to-create-a-good-getting-started-and-readme/tree/master/samples)*.

<br>

## Sample code

**Quick start** - give the api a try! Using your [46elks credentials](https://dashboard.46elks.com) enter this into your terminal:
```
curl -X POST \
  -u <API-Username>:<API-Password> \
  -H 'Content-Type: application/x-www-form-urlencoded' \
  -d 'from=Victoria' \
  -d 'to=+46766861004' \
  -d 'message=Test Message To your phone.' \
  'https://api.46elks.com/a1/SMS' 
``` 

You've now sent an sms!  
![sms-on-mobile-phone](https://www.46elks.com/images/README-on-github/phone-with-lovely-sms-black.png)


<br>
##### Code examples in different languages

* Send sms
[C#](https://github.com/46elks/46elks-getting-started/blob/master/code-examples/C%23) -
[Elixir](https://github.com/46elks/46elks-getting-started/blob/master/code-examples/Elixir) -
[Go](https://github.com/46elks/46elks-getting-started/blob/master/code-examples/Go) -
[Google apps](https://github.com/46elks/46elks-getting-started/blob/master/code-examples/Google%20apps%20script) -
[haskell](https://github.com/46elks/46elks-getting-started/blob/master/code-examples/haskell) -
[HTML](https://github.com/46elks/46elks-getting-started/blob/master/code-examples/HTML) -
[PHP](https://github.com/46elks/46elks-getting-started/blob/master/code-examples/PHP) -
[Python](https://github.com/46elks/46elks-getting-started/blob/master/code-examples/Python) -
[Ruby](https://github.com/46elks/46elks-getting-started/blob/master/code-examples/Ruby) -
[Java]( https://github.com/46elks/46elks-getting-started/tree/master/code-examples/Java) -
[Node](https://github.com/46elks/46elks-getting-started/tree/master/code-examples/Node) -
[cURL](https://github.com/46elks/46elks-getting-started/tree/master/code-examples/cURL)



* Build an interactive voice menu or response (IVR)
[IVR samples collection](https://github.com/46elks/46elks-getting-started/tree/master/code-examples/Voice%20-%20IVR%20-%20interactive%20voice%20menues).

[Show all coding examples for all languages](https://github.com/46elks/46elks-getting-started/tree/master/code-examples)

## Tutorials
*Most popular*
* [Love messenger](https://github.com/gish/love-messenger)
* [Receive SMS into Google spreadsheet](https://medium.com/@46elks/receive-sms-into-google-spreadsheet-435b51393493#.9ku01h462)

Have you written a tutorial or created an app that you're willing to share?
Let us know at hello@46elks.com!

## Demos
* [passer -- self-hosted sms to tweet](https://github.com/46elks/passer) #python
* [Elkme - Send sms from the command line](https://github.com/46elks/elkme)

## Resources
* [Postman app](https://www.getpostman.com/) - interact with apis through a Chrome app, also available as an OSX app.
  
## Integrations
  * [Zapier](https://zapier.com/zapbook/46elks/)
  * [Microsoft Excel](https://excel.46elks.com/)
  * Google spreadsheet [Google Script code sample](https://github.com/46elks/SMStoGoogleSheets) | Tutorial: [Receive sms into Google spreadsheet](https://medium.com/@46elks/receive-sms-into-google-spreadsheet-435b51393493#.iu690j86w)

# Hackathons
  We are keen on helping out, and we'd be happy to help you with preparations, mentoring and give away boosted credit packages for participants. [Talk to us](mailto:hello@46elks.com). We're also particularily interested in helping events and organizations working with teaching coding to beginners, initiatives that encourage diversity in IT, and among conferences. We enjoy meetups, let us know if you’d like for someone to come and hold a presentation about APIs - getting started with apis, how to send an sms in PHP/Python/Elixir/Ruby/Go or another language - we love to learn and share!  We exist to help you build useful and cool things!

**Contact 46elks**  
Email: hello@46elks.com
On twitter: [@46elks](https://twitter.com/46elks)  
