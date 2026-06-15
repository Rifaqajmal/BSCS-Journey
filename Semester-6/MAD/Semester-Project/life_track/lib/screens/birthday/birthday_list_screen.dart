import 'package:firebase_auth/firebase_auth.dart';
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import '../providers/birthday_provider.dart';
import '../services/database_service.dart';
import 'birthday_detail_screen.dart';
import '../models/birthday_model.dart';

class BirthdayListScreen extends StatefulWidget {
  const BirthdayListScreen({super.key});

  @override
  State<BirthdayListScreen> createState() => _BirthdayListScreenState();
}

class _BirthdayListScreenState extends State<BirthdayListScreen> {
  final DatabaseService db = DatabaseService();

  @override
  void initState() {
    super.initState();
    loadBirthdays();
  }

  void loadBirthdays() {
    final uid = FirebaseAuth.instance.currentUser!.uid;

    db.streamBirthdays(uid).listen((data) {
      Provider.of<BirthdayProvider>(context, listen: false)
          .setBirthdays(data);
    });
  }

  DateTime nextBirthday(DateTime dob) {
    final now = DateTime.now();

    DateTime next = DateTime(now.year, dob.month, dob.day);

    if (next.isBefore(now)) {
      next = DateTime(now.year + 1, dob.month, dob.day);
    }

    return next;
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text("Birthday List"),
        actions: [
          IconButton(
            icon: const Icon(Icons.sort),
            onPressed: () {
              Provider.of<BirthdayProvider>(context, listen: false)
                  .sortByUpcoming();
            },
          )
        ],
      ),

      body: Consumer<BirthdayProvider>(
        builder: (context, provider, child) {
          final List<BirthdayModel> birthdays =
              provider.filteredBirthdays;

          return Column(
            children: [
              // SEARCH BAR
              Padding(
                padding: const EdgeInsets.all(10),
                child: TextField(
                  decoration: const InputDecoration(
                    hintText: "Search by name or relation",
                    prefixIcon: Icon(Icons.search),
                    border: OutlineInputBorder(),
                  ),
                  onChanged: (value) {
                    provider.search(value);
                  },
                ),
              ),

              // LIST
              Expanded(
                child: birthdays.isEmpty
                    ? const Center(
                        child: Text("No birthdays found"),
                      )
                    : ListView.builder(
                        itemCount: birthdays.length,
                        itemBuilder: (context, index) {
                          final b = birthdays[index];

                          final daysLeft = nextBirthday(b.dob)
                              .difference(DateTime.now())
                              .inDays;

                          return Card(
                            child: ListTile(
                              leading: const Icon(Icons.cake),
                              title: Text(b.name),
                              subtitle: Text(
                                  "Relation: ${b.relation}\nDays left: $daysLeft"),

                              // OPEN DETAIL SCREEN
                              onTap: () {
                                Navigator.push(
                                  context,
                                  MaterialPageRoute(
                                    builder: (_) =>
                                        BirthdayDetailScreen(
                                            birthday: b),
                                  ),
                                );
                              },
                            ),
                          );
                        },
                      ),
              ),
            ],
          );
        },
      ),
    );
  }
}