import 'package:flutter/material.dart';
import 'package:shared_preferences/shared_preferences.dart';

void main() {
  runApp(MyApp());
}

class MyApp extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      debugShowCheckedModeBanner: false,
      home: SplashScreen(),
    );
  }
}

/// ================= SPLASH SCREEN =================
class SplashScreen extends StatefulWidget {
  @override
  _SplashScreenState createState() => _SplashScreenState();
}

class _SplashScreenState extends State<SplashScreen> {

  @override
  void initState() {
    super.initState();
    checkLogin();
  }

  void checkLogin() async {
    await Future.delayed(Duration(seconds: 3));

    final prefs = await SharedPreferences.getInstance();
    String? role = prefs.getString("role");

    if (role == "admin") {
      Navigator.pushReplacement(
        context,
        MaterialPageRoute(builder: (_) => AdminScreen()),
      );
    } else if (role == "user") {
      Navigator.pushReplacement(
        context,
        MaterialPageRoute(builder: (_) => UserScreen()),
      );
    } else {
      Navigator.pushReplacement(
        context,
        MaterialPageRoute(builder: (_) => LoginScreen()),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Center(
        child: Text(
          "Welcome App",
          style: TextStyle(fontSize: 24),
        ),
      ),
    );
  }
}

/// ================= LOGIN SCREEN =================
class LoginScreen extends StatelessWidget {

  final TextEditingController username = TextEditingController();
  final TextEditingController password = TextEditingController();

  void login(BuildContext context) async {
    final prefs = await SharedPreferences.getInstance();

    if (username.text == "admin" && password.text == "123") {
      await prefs.setString("role", "admin");

      Navigator.pushReplacement(
        context,
        MaterialPageRoute(builder: (_) => AdminScreen()),
      );
    } else {
      await prefs.setString("role", "user");

      Navigator.pushReplacement(
        context,
        MaterialPageRoute(builder: (_) => UserScreen()),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text("Login")),
      body: Padding(
        padding: EdgeInsets.all(20),
        child: Column(
          children: [
            TextField(controller: username, decoration: InputDecoration(labelText: "Username")),
            TextField(controller: password, decoration: InputDecoration(labelText: "Password")),
            SizedBox(height: 20),

            ElevatedButton(
              onPressed: () => login(context),
              child: Text("Login"),
            )
          ],
        ),
      ),
    );
  }
}

/// ================= ADMIN SCREEN =================
class AdminScreen extends StatelessWidget {

  void logout(BuildContext context) async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.clear();

    Navigator.pushReplacement(
      context,
      MaterialPageRoute(builder: (_) => LoginScreen()),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text("Admin Panel")),
      body: Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Text("Welcome Admin"),
            ElevatedButton(
              onPressed: () => logout(context),
              child: Text("Logout"),
            )
          ],
        ),
      ),
    );
  }
}

/// ================= USER SCREEN =================
class UserScreen extends StatelessWidget {

  void logout(BuildContext context) async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.clear();

    Navigator.pushReplacement(
      context,
      MaterialPageRoute(builder: (_) => LoginScreen()),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text("User Panel")),
      body: Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Text("Welcome User"),
            ElevatedButton(
              onPressed: () => logout(context),
              child: Text("Logout"),
            )
          ],
        ),
      ),
    );
  }
}