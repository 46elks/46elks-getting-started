# Receive SMS orders with PHP and MySQL

With this script your customers can send their orders via SMS directly to your server and store the order in your database.

The script will check if it’s the first time the customer order something. If so, you can send them a special *thank you*.

## Getting started

1. Create a new database called `sms-orders`.
2. Import the `database.sql` into your new database.
3. Add your database credentials in the PHP-file, e.g:
```
	$host = "localhost";
	$username = "root";
	$password = "root";
	$dbname = "sms-orders";
```
4. Allocate a number using the [/Numbers REST resource](https://46elks.com/docs/allocate-number) or via the [dashboard](https://46elks.com/guides/get-virtual-number).
5. Configure [`sms_url`](https://46elks.com/docs/configure-number) on the number you allocated, with your script URL.
6. Send an SMS from your phone to your allocated number with the text ”Cucumber”.
7. Wait for a respons and send another SMS with the text ”Orange”.

In this example script you can send the following text and get a valid respons:
- Cucumber
- Orange
- Mushroom

Have fun 🥳