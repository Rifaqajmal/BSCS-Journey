import 'package:flutter/material.dart';
import '../../services/notification_service.dart';

class HomeScreen extends StatelessWidget {
  const HomeScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text("LifeTrack Home"),
      ),

      body: Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            const Text(
              "LifeTrack App Running",
              style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold),
            ),

            const SizedBox(height: 20),

            ElevatedButton(
              onPressed: () {
                NotificationService.show(
                  "Test Notification",
                  "It is working fine",
                );
              },
              child: const Text("Test Notification"),
            ),
          ],
        ),
      ),
    );
  }
}