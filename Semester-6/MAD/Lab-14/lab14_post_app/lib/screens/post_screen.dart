import 'package:flutter/material.dart';
import 'package:firebase_auth/firebase_auth.dart';

import 'add_post_screen.dart';
import 'login_screen.dart';

class PostScreen extends StatelessWidget {

  logout(BuildContext context) async {

    await FirebaseAuth.instance.signOut();

    Navigator.pushReplacement(
      context,
      MaterialPageRoute(builder: (_) => LoginScreen()),
    );
  }

  @override
  Widget build(BuildContext context) {

    return Scaffold(

      appBar: AppBar(
        title: Text("PostScreen"),

        actions: [

          IconButton(
            onPressed: () {
              logout(context);
            },
            icon: Icon(Icons.logout),
          )

        ],
      ),

      floatingActionButton: FloatingActionButton(

        child: Icon(Icons.add),

        onPressed: () {

          Navigator.push(
            context,
            MaterialPageRoute(
              builder: (_) => AddPostScreen(),
            ),
          );
        },
      ),
    );
  }
}