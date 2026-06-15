import 'package:flutter/material.dart';
import 'package:firebase_core/firebase_core.dart';
import 'package:firebase_auth/firebase_auth.dart';

import 'screens/login_screen.dart';
import 'screens/post_screen.dart';

void main() async {

  WidgetsFlutterBinding.ensureInitialized();

  await Firebase.initializeApp(
    options: const FirebaseOptions(
      apiKey: "AIzaSyBnguhzqX3glJbuZmZNl8CG1Jv78AY47xE",
      appId: "1:649210134456:android:51b85c5268089fe1f1d94d",
      messagingSenderId: "649210134456",
      projectId: "lab14firebase-bc59c",
      databaseURL: "https://lab14firebase-bc59c-default-rtdb.asia-southeast1.firebasedatabase.app",
    ),
  );

  runApp(MyApp());
}

class MyApp extends StatelessWidget {

  @override
  Widget build(BuildContext context) {

    return MaterialApp(
      debugShowCheckedModeBanner: false,

      home: FirebaseAuth.instance.currentUser == null
          ? LoginScreen()
          : PostScreen(),
    );
  }
}