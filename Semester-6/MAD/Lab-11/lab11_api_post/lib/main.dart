import 'package:flutter/material.dart';

import 'screens/home_screen.dart';
import 'screens/signup_screen.dart';

void main() {
  runApp(MyApp());
}

class MyApp extends StatelessWidget {

  @override
  Widget build(BuildContext context) {

    return MaterialApp(
      debugShowCheckedModeBanner: false,

      initialRoute: "/",

      routes: {
        "/": (context) => HomeScreen(),
        "/signup": (context) => SignupScreen(),
      },
    );
  }
}