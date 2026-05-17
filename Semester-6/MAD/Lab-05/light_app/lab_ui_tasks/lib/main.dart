import 'package:flutter/material.dart';
import 'dart:async';
import 'dart:math';

void main() {
  runApp(RunnerGame());
}

class RunnerGame extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      debugShowCheckedModeBanner: false,
      home: GamePage(),
    );
  }
}

class GamePage extends StatefulWidget {
  @override
  State<GamePage> createState() => _GamePageState();
}

class _GamePageState extends State<GamePage> {

  double playerY = 0;
  double velocity = 0;
  double gravity = -0.05;

  double vehicleX = 1.5;

  int score = 0;
  bool gameStarted = false;

  Timer? gameTimer;

  List<String> vehicles = [
    "🚗",
    "🚙",
    "🚕",
    "🚓",
    "🚚",
    "🏎️"
  ];

  String currentVehicle = "🚗";

  Random random = Random();

  void startGame() {

    gameStarted = true;

    gameTimer = Timer.periodic(const Duration(milliseconds: 20), (timer) {

      setState(() {

        velocity += gravity;
        playerY -= velocity;

        if (playerY > 0) {
          playerY = 0;
          velocity = 0;
        }

        vehicleX -= 0.025;

        if (vehicleX < -1.5) {

          vehicleX = 1.5;

          currentVehicle =
              vehicles[random.nextInt(vehicles.length)];

          score++;

        }

        if (vehicleX < 0.2 &&
            vehicleX > -0.2 &&
            playerY == 0) {

          timer.cancel();
          gameOver();

        }

      });

    });

  }

  void jump() {

    if (playerY == 0) {
      velocity = 0.9;
    }

  }

  void gameOver() {

    showDialog(
      context: context,
      builder: (context) {
        return AlertDialog(
          title: const Text("Game Over"),
          content: Text("Score: $score"),
          actions: [
            TextButton(
              onPressed: () {
                Navigator.pop(context);
                restartGame();
              },
              child: const Text("Restart"),
            )
          ],
        );
      },
    );

  }

  void restartGame() {

    setState(() {

      playerY = 0;
      velocity = 0;
      vehicleX = 1.5;
      score = 0;
      gameStarted = false;

    });

  }

  Widget buildRoad() {

    return Container(
      color: Colors.grey[800],
      child: Center(
        child: Container(
          width: 6,
          color: Colors.white,
        ),
      ),
    );

  }

  @override
  Widget build(BuildContext context) {

    return GestureDetector(
      onTap: () {
        if (gameStarted) {
          jump();
        }
      },
      child: Scaffold(
        backgroundColor: Colors.lightBlue,
        body: Column(
          children: [

            Expanded(
              flex: 4,
              child: Stack(
                children: [

                  buildRoad(),

                  Align(
                    alignment: Alignment(0, playerY),
                    child: const Text(
                      "🏃‍♂️",
                      style: TextStyle(fontSize: 70),
                    ),
                  ),

                  Align(
                    alignment: Alignment(vehicleX, 0),
                    child: Text(
                      currentVehicle,
                      style: const TextStyle(fontSize: 70),
                    ),
                  ),

                  Positioned(
                    top: 50,
                    left: 20,
                    child: Text(
                      "Score: $score",
                      style: const TextStyle(
                          fontSize: 28,
                          color: Colors.white),
                    ),
                  )
                ],
              ),
            ),

            Expanded(
              child: Container(
                color: Colors.green,
                child: Center(
                  child: gameStarted
                      ? const Text(
                          "Tap to Jump",
                          style: TextStyle(
                              fontSize: 24,
                              color: Colors.white),
                        )
                      : ElevatedButton(
                          onPressed: startGame,
                          child: const Text("START GAME"),
                        ),
                ),
              ),
            )
          ],
        ),
      ),
    );
  }
}