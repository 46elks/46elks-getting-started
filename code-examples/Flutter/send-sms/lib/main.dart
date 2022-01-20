import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';

Future<String> sendSMS( String phoneNumber, String message) async {
  String username = 'API_USER';
  String password = 'API_PASS';
  String basicAuth =
      'Basic ' + base64Encode(utf8.encode('$username:$password'));
  print(basicAuth);
  var url = Uri.parse('https://api.46elks.com/a1/sms');
  var response = await http.post(url,headers: <String, String>{'authorization': basicAuth}, body: {'to': phoneNumber, 'from': 'Android', 'message' : message});
  print('Response status: ${response.statusCode}');
  print('Response body: ${response.body}');
  return response.body;
}

void main() => runApp(MyApp());

class MyApp extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    final appTitle = '46elks send SMS sample';
    return MaterialApp(
      title: appTitle,
      home: Scaffold(
        appBar: AppBar(
          title: Text(appTitle),
        ),
        body: MyCustomForm(),
      ),
    );
  }
}
// Create a Form widget.
class MyCustomForm extends StatefulWidget {
  @override
  MyCustomFormState createState() {
    return MyCustomFormState();
  }
}
// Create a corresponding State class, which holds data related to the form.
class MyCustomFormState extends State<MyCustomForm> {
  // Create a global key that uniquely identifies the Form widget
  // and allows validation of the form.
  final _formKey = GlobalKey<FormState>();

  @override
  Widget build(BuildContext context) {
    // Build a Form widget using the _formKey created above.
    TextEditingController phoneNumberEditingController = TextEditingController();
    TextEditingController messageEditingController = TextEditingController();
    return Form(
      key: _formKey,
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: <Widget>[
          TextFormField(
            controller: phoneNumberEditingController,
            decoration: const InputDecoration(
              icon: const Icon(Icons.phone),
              hintText: 'Enter a phone number',
              labelText: 'Phone',
            ),
            validator: (value) {
              if (value.isEmpty) {
                return 'Please enter valid phone number';
              }
              return null;
            },
          ),
          TextFormField(
            controller: messageEditingController,
            decoration: const InputDecoration(
              icon: const Icon(Icons.message),
              hintText: 'Enter your message.',
              labelText: 'Message',
            ),
            validator: (value) {
              if (value.isEmpty) {
                return 'Please enter the message.';
              }
              return null;
            },
          ),
          new Container(
              padding: const EdgeInsets.only(left: 150.0, top: 40.0),
              child: new RaisedButton(
                child: const Text('Submit'),
                onPressed: () {
                  // It returns true if the form is valid, otherwise returns false
                  if (_formKey.currentState.validate()) {
                    // If the form is valid, display a Snackbar.
                    sendSMS(phoneNumberEditingController.text, messageEditingController.text).then((value) {
                      Scaffold.of(context)
                          .showSnackBar(SnackBar(content: Text(value)));
                    });
                  }
                },
              )),
        ],
      ),
    );
  }
}