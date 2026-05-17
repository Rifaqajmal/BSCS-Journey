import 'package:flutter/material.dart';

void main() {
  runApp(LightApp());
}

class LightApp extends StatefulWidget {
  @override
  _LightAppState createState() => _LightAppState();
}

class _LightAppState extends State<LightApp> {

  bool isLightOn = false;

  void toggleLight() {
    setState(() {
      isLightOn = !isLightOn;
    });
  }

  @override
  Widget build(BuildContext context) {

    return MaterialApp(
      debugShowCheckedModeBanner: false,

      home: Scaffold(
        appBar: AppBar(title: Text("Light App")),

        body: Center(
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,

            children: [

              Icon(
                isLightOn ? Icons.lightbulb : Icons.lightbulb_outline,
                size: 120,
                color: isLightOn ? Colors.yellow : Colors.grey,
              ),

              SizedBox(height: 20),

              Text(
                isLightOn ? "Light is ON" : "Light is OFF",
                style: TextStyle(fontSize: 24),
              ),

            ],
          ),
        ),

        floatingActionButton: FloatingActionButton(
          onPressed: toggleLight,
          child: Icon(Icons.power_settings_new),
        ),
      ),
    );
  }
}