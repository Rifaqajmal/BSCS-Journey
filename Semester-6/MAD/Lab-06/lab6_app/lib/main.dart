import 'package:flutter/material.dart';

void main() {
  runApp(MyApp());
}

class MyApp extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      debugShowCheckedModeBanner: false,
      home: HomeScreen(),
    );
  }
}

//////////////////////////////////////////////////
// HOME SCREEN WITH DRAWER
//////////////////////////////////////////////////

class HomeScreen extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text("Home")),

      drawer: Drawer(
        child: ListView(
          children: [

            DrawerHeader(
              decoration: BoxDecoration(color: Colors.blue),
              child: Text("My App", style: TextStyle(color: Colors.white, fontSize: 24)),
            ),

            ListTile(
              title: Text("Login Screen"),
              onTap: () {
                Navigator.push(
                  context,
                  MaterialPageRoute(builder: (context) => LoginScreen()),
                );
              },
            ),

            ListTile(
              title: Text("List Screen"),
              onTap: () {
                Navigator.push(
                  context,
                  MaterialPageRoute(builder: (context) => ListScreen()),
                );
              },
            ),

          ],
        ),
      ),

      body: Center(
        child: Text("Welcome to Lab 7 App"),
      ),
    );
  }
}

//////////////////////////////////////////////////
// LOGIN SCREEN
//////////////////////////////////////////////////

class LoginScreen extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text("Login")),

      body: Padding(
        padding: EdgeInsets.all(20),

        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [

            TextField(decoration: InputDecoration(labelText: "Email")),
            TextField(decoration: InputDecoration(labelText: "Password"), obscureText: true),

            SizedBox(height: 20),

            ElevatedButton(
              onPressed: () {},
              child: Text("Login"),
            ),

            SizedBox(height: 20),

            RichText(
              text: TextSpan(
                text: "Don't have an account? ",
                style: TextStyle(color: Colors.black),
                children: [
                  TextSpan(
                    text: "Sign Up",
                    style: TextStyle(color: Colors.blue),
                  ),
                ],
              ),
            )

          ],
        ),
      ),
    );
  }
}

//////////////////////////////////////////////////
// LIST SCREEN
//////////////////////////////////////////////////

class ListScreen extends StatelessWidget {

  final List<String> items = [
    "Profile",
    "Settings",
    "Notifications",
    "Messages",
    "Logout"
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text("List Screen")),

      body: ListView.builder(
        itemCount: items.length,
        itemBuilder: (context, index) {
          return ListTile(
            leading: Icon(Icons.arrow_forward),
            title: Text(items[index]),
          );
        },
      ),
    );
  }
}