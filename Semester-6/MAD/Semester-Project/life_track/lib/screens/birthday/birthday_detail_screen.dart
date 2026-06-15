import 'package:flutter/material.dart';
import '../models/birthday_model.dart';

class BirthdayDetailScreen extends StatelessWidget {
  final BirthdayModel birthday;

  const BirthdayDetailScreen({super.key, required this.birthday});

  int calculateAge(DateTime dob) {
    final now = DateTime.now();
    int age = now.year - dob.year;

    if (now.month < dob.month ||
        (now.month == dob.month && now.day < dob.day)) {
      age--;
    }

    return age;
  }

  @override
  Widget build(BuildContext context) {
    final age = calculateAge(birthday.dob);

    return Scaffold(
      appBar: AppBar(
        title: Text(birthday.name),
      ),
      body: Padding(
        padding: const EdgeInsets.all(20),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text("Name: ${birthday.name}",
                style: const TextStyle(fontSize: 18)),
            const SizedBox(height: 10),

            Text("Relation: ${birthday.relation}",
                style: const TextStyle(fontSize: 18)),

            const SizedBox(height: 10),

            Text("Date of Birth: ${birthday.dob.toString().split(' ')[0]}",
                style: const TextStyle(fontSize: 18)),

            const SizedBox(height: 10),

            Text("Age: $age years",
                style: const TextStyle(
                    fontSize: 20, fontWeight: FontWeight.bold)),
          ],
        ),
      ),
    );
  }
}