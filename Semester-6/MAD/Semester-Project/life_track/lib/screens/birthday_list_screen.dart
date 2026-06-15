import 'package:firebase_auth/firebase_auth.dart';
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import '../providers/birthday_provider.dart';
import '../services/database_service.dart';
import '../services/notification_service.dart';
import 'birthday_detail_screen.dart';

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
    loadStream();
  }

  void loadStream() {
    final uid = FirebaseAuth.instance.currentUser!.uid;

    db.streamBirthdays(uid).listen((data) {
      final provider = Provider.of<BirthdayProvider>(context, listen: false);
      provider.setBirthdays(data);

      for (var b in data) {
        NotificationService.scheduleBirthdayNotification(
          id: b.id.hashCode,
          name: b.name,
          birthday: b.dob,
        );
      }
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

  String _getZodiac(DateTime dob) {
    int month = dob.month;
    int day = dob.day;
    if ((month == 3 && day >= 21) || (month == 4 && day <= 19)) return '♈ Aries';
    if ((month == 4 && day >= 20) || (month == 5 && day <= 20)) return '♉ Taurus';
    if ((month == 5 && day >= 21) || (month == 6 && day <= 20)) return '♊ Gemini';
    if ((month == 6 && day >= 21) || (month == 7 && day <= 22)) return '♋ Cancer';
    if ((month == 7 && day >= 23) || (month == 8 && day <= 22)) return '♌ Leo';
    if ((month == 8 && day >= 23) || (month == 9 && day <= 22)) return '♍ Virgo';
    if ((month == 9 && day >= 23) || (month == 10 && day <= 22)) return '♎ Libra';
    if ((month == 10 && day >= 23) || (month == 11 && day <= 21)) return '♏ Scorpio';
    if ((month == 11 && day >= 22) || (month == 12 && day <= 21)) return '♐ Sagittarius';
    if ((month == 12 && day >= 22) || (month == 1 && day <= 19)) return '♑ Capricorn';
    if ((month == 1 && day >= 20) || (month == 2 && day <= 18)) return '♒ Aquarius';
    return '♓ Pisces';
  }

  Color _daysLeftColor(int days) {
    if (days == 0) return Colors.red;
    if (days <= 7) return Colors.orange;
    if (days <= 30) return Colors.amber;
    return Colors.green;
  }

  String _daysLeftText(int days) {
    if (days == 0) return '🎉 Today!';
    if (days == 1) return 'Tomorrow!';
    return '$days days left';
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFFF5F0FF),
      appBar: AppBar(
        backgroundColor: Colors.deepPurple,
        foregroundColor: Colors.white,
        title: const Text(
          "Birthdays",
          style: TextStyle(fontWeight: FontWeight.bold),
        ),
        actions: [
          IconButton(
            icon: const Icon(Icons.sort),
            tooltip: "Sort by upcoming",
            onPressed: () {
              Provider.of<BirthdayProvider>(context, listen: false)
                  .sortByUpcoming();
            },
          ),
        ],
      ),
      body: Consumer<BirthdayProvider>(
        builder: (context, provider, child) {
          final birthdays = provider.filteredBirthdays;

          return Column(
            children: [
              // SEARCH BAR
              Container(
                color: Colors.deepPurple,
                padding:
                    const EdgeInsets.only(left: 16, right: 16, bottom: 20),
                child: TextField(
                  onChanged: (value) => provider.search(value),
                  style: const TextStyle(color: Colors.white),
                  decoration: InputDecoration(
                    hintText: "Search by name or relation...",
                    hintStyle: const TextStyle(color: Colors.white60),
                    prefixIcon:
                        const Icon(Icons.search, color: Colors.white70),
                    filled: true,
                    fillColor: Colors.white.withOpacity(0.15),
                    border: OutlineInputBorder(
                      borderRadius: BorderRadius.circular(12),
                      borderSide: BorderSide.none,
                    ),
                  ),
                ),
              ),

              // COUNT HEADER
              Padding(
                padding:
                    const EdgeInsets.symmetric(horizontal: 16, vertical: 10),
                child: Row(
                  children: [
                    Text(
                      "${birthdays.length} Birthday${birthdays.length != 1 ? 's' : ''}",
                      style: const TextStyle(
                        fontWeight: FontWeight.bold,
                        fontSize: 16,
                        color: Colors.deepPurple,
                      ),
                    ),
                  ],
                ),
              ),

              // LIST
              Expanded(
                child: birthdays.isEmpty
                    ? Center(
                        child: Column(
                          mainAxisAlignment: MainAxisAlignment.center,
                          children: [
                            const Text("🎂",
                                style: TextStyle(fontSize: 60)),
                            const SizedBox(height: 16),
                            const Text(
                              "No birthdays yet",
                              style: TextStyle(
                                fontSize: 18,
                                fontWeight: FontWeight.bold,
                                color: Colors.deepPurple,
                              ),
                            ),
                            const SizedBox(height: 8),
                            Text(
                              "Tap + on home screen to add one",
                              style: TextStyle(color: Colors.grey[600]),
                            ),
                          ],
                        ),
                      )
                    : ListView.builder(
                        padding: const EdgeInsets.symmetric(horizontal: 16),
                        itemCount: birthdays.length,
                        itemBuilder: (context, index) {
                          final b = birthdays[index];
                          final today = DateTime.now();
                          final next = nextBirthday(b.dob);
                          final daysLeft = DateTime(next.year, next.month, next.day)
                                .difference(DateTime(today.year, today.month, today.day))
                                .inDays;
                          final zodiac = _getZodiac(b.dob);

                          return GestureDetector(
                            onTap: () {
                              Navigator.push(
                                context,
                                MaterialPageRoute(
                                  builder: (_) =>
                                      BirthdayDetailScreen(birthday: b),
                                ),
                              );
                            },
                            child: Card(
                              margin: const EdgeInsets.only(bottom: 12),
                              shape: RoundedRectangleBorder(
                                borderRadius: BorderRadius.circular(16),
                              ),
                              elevation: 3,
                              child: Padding(
                                padding: const EdgeInsets.all(16),
                                child: Row(
                                  children: [
                                    // AVATAR
                                    CircleAvatar(
                                      backgroundColor: Colors.deepPurple
                                          .withOpacity(0.15),
                                      radius: 28,
                                      child: Text(
                                        b.name[0].toUpperCase(),
                                        style: const TextStyle(
                                          fontSize: 22,
                                          fontWeight: FontWeight.bold,
                                          color: Colors.deepPurple,
                                        ),
                                      ),
                                    ),

                                    const SizedBox(width: 16),

                                    // INFO
                                    Expanded(
                                      child: Column(
                                        crossAxisAlignment:
                                            CrossAxisAlignment.start,
                                        children: [
                                          Text(
                                            b.name,
                                            style: const TextStyle(
                                              fontSize: 16,
                                              fontWeight: FontWeight.bold,
                                            ),
                                          ),
                                          const SizedBox(height: 4),
                                          Text(
                                            b.relation.isEmpty
                                                ? zodiac
                                                : "${b.relation} · $zodiac",
                                            style: TextStyle(
                                              color: Colors.grey[600],
                                              fontSize: 13,
                                            ),
                                          ),
                                          const SizedBox(height: 4),
                                          Text(
                                            "${b.dob.day}/${b.dob.month}/${b.dob.year}",
                                            style: TextStyle(
                                              color: Colors.grey[500],
                                              fontSize: 12,
                                            ),
                                          ),
                                        ],
                                      ),
                                    ),

                                    // DAYS LEFT BADGE
                                    Container(
                                      padding: const EdgeInsets.symmetric(
                                          horizontal: 10, vertical: 6),
                                      decoration: BoxDecoration(
                                        color: _daysLeftColor(daysLeft)
                                            .withOpacity(0.15),
                                        borderRadius:
                                            BorderRadius.circular(20),
                                      ),
                                      child: Text(
                                        _daysLeftText(daysLeft),
                                        style: TextStyle(
                                          color: _daysLeftColor(daysLeft),
                                          fontWeight: FontWeight.bold,
                                          fontSize: 12,
                                        ),
                                      ),
                                    ),
                                  ],
                                ),
                              ),
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