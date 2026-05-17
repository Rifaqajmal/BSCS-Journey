import 'package:flutter/material.dart';
import 'dart:math';

void main() {
  runApp(DiceApp());
}

class DiceApp extends StatefulWidget {
  @override
  _DiceAppState createState() => _DiceAppState();
}

class _DiceAppState extends State<DiceApp> {

  int diceNumber = 1;

  void rollDice() {
    setState(() {
      diceNumber = Random().nextInt(6) + 1;
    });
  }

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      debugShowCheckedModeBanner: false,

      home: Scaffold(
        appBar: AppBar(title: Text("Dice App")),

        body: Center(
          child: Image.asset(
            "assets/dice$diceNumber.png",
            width: 200,
          ),
        ),

        floatingActionButton: FloatingActionButton(
          onPressed: rollDice,
          child: Icon(Icons.casino),
        ),
      ),
    );
  }
}