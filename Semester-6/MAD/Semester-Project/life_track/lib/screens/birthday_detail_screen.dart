import 'package:flutter/material.dart';
import 'package:firebase_auth/firebase_auth.dart';
import '../models/birthday_model.dart';
import '../services/share_service.dart';
import '../services/database_service.dart';

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

  int daysUntilBirthday(DateTime dob) {
    final now = DateTime.now();
    DateTime next = DateTime(now.year, dob.month, dob.day);
    if (next.isBefore(now)) {
      next = DateTime(now.year + 1, dob.month, dob.day);
    }
    return DateTime(next.year, next.month, next.day)
        .difference(DateTime(now.year, now.month, now.day))
        .inDays;
  }

  String getZodiac(DateTime dob) {
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

  Map<String, String> getLifeStats(DateTime dob) {
    final now = DateTime.now();
    final daysLived = now.difference(dob).inDays;
    final heartbeats = daysLived * 24 * 60 * 70;
    final hoursSlept = daysLived * 8;
    final breaths = daysLived * 24 * 60 * 15;

    return {
      '❤️ Heartbeats': '~${_format(heartbeats)}',
      '😴 Hours Slept': '~${_format(hoursSlept)}',
      '🌬️ Breaths Taken': '~${_format(breaths)}',
      '📅 Days Lived': _format(daysLived),
    };
  }

  String _format(int number) {
    if (number >= 1000000000) return '${(number / 1000000000).toStringAsFixed(1)}B';
    if (number >= 1000000) return '${(number / 1000000).toStringAsFixed(1)}M';
    if (number >= 1000) return '${(number / 1000).toStringAsFixed(1)}K';
    return number.toString();
  }

  Future<void> _deleteBirthday(BuildContext context) async {
    final confirm = await showDialog<bool>(
      context: context,
      builder: (context) => AlertDialog(
        shape: RoundedRectangleBorder(
            borderRadius: BorderRadius.circular(16)),
        title: const Text("Delete Birthday"),
        content: Text(
            "Are you sure you want to delete ${birthday.name}'s birthday?"),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context, false),
            child: const Text("Cancel"),
          ),
          ElevatedButton(
            onPressed: () => Navigator.pop(context, true),
            style: ElevatedButton.styleFrom(
              backgroundColor: Colors.red,
              foregroundColor: Colors.white,
            ),
            child: const Text("Delete"),
          ),
        ],
      ),
    );

    if (confirm == true) {
      final uid = FirebaseAuth.instance.currentUser!.uid;
      await DatabaseService().deleteBirthday(uid, birthday.id);
      Navigator.pop(context);
    }
  }

  @override
  Widget build(BuildContext context) {
    final age = calculateAge(birthday.dob);
    final daysLeft = daysUntilBirthday(birthday.dob);
    final zodiac = getZodiac(birthday.dob);
    final lifeStats = getLifeStats(birthday.dob);
    final isBirthdayToday = daysLeft == 0;

    return Scaffold(
      backgroundColor: const Color(0xFFF5F0FF),
      appBar: AppBar(
        backgroundColor: Colors.deepPurple,
        foregroundColor: Colors.white,
        title: Text(
          birthday.name,
          style: const TextStyle(fontWeight: FontWeight.bold),
        ),
        actions: [
          IconButton(
            icon: const Icon(Icons.delete_outline),
            tooltip: "Delete",
            onPressed: () => _deleteBirthday(context),
          ),
        ],
        elevation: 0,
      ),
      body: SingleChildScrollView(
        child: Column(
          children: [
            Container(
              width: double.infinity,
              padding: const EdgeInsets.symmetric(vertical: 30),
              decoration: const BoxDecoration(
                color: Colors.deepPurple,
                borderRadius: BorderRadius.only(
                  bottomLeft: Radius.circular(30),
                  bottomRight: Radius.circular(30),
                ),
              ),
              child: Column(
                children: [
                  CircleAvatar(
                    radius: 45,
                    backgroundColor: Colors.white.withOpacity(0.2),
                    child: Text(
                      birthday.name[0].toUpperCase(),
                      style: const TextStyle(
                        fontSize: 40,
                        fontWeight: FontWeight.bold,
                        color: Colors.white,
                      ),
                    ),
                  ),
                  const SizedBox(height: 12),
                  Text(
                    birthday.name,
                    style: const TextStyle(
                      color: Colors.white,
                      fontSize: 24,
                      fontWeight: FontWeight.bold,
                    ),
                  ),
                  Text(
                    birthday.relation.isEmpty
                        ? zodiac
                        : "${birthday.relation} · $zodiac",
                    style: const TextStyle(
                        color: Colors.white70, fontSize: 14),
                  ),
                  const SizedBox(height: 16),
                  isBirthdayToday
                      ? Container(
                          padding: const EdgeInsets.symmetric(
                              horizontal: 20, vertical: 8),
                          decoration: BoxDecoration(
                            color: Colors.amber,
                            borderRadius: BorderRadius.circular(20),
                          ),
                          child: const Text(
                            "🎉 Birthday Today!",
                            style: TextStyle(
                              fontWeight: FontWeight.bold,
                              color: Colors.black,
                            ),
                          ),
                        )
                      : Container(
                          padding: const EdgeInsets.symmetric(
                              horizontal: 20, vertical: 8),
                          decoration: BoxDecoration(
                            color: Colors.white.withOpacity(0.2),
                            borderRadius: BorderRadius.circular(20),
                          ),
                          child: Text(
                            "$daysLeft days until birthday 🎂",
                            style: const TextStyle(color: Colors.white),
                          ),
                        ),
                ],
              ),
            ),

            const SizedBox(height: 20),

            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 16),
              child: Card(
                shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(16)),
                elevation: 3,
                child: Padding(
                  padding: const EdgeInsets.all(16),
                  child: Column(
                    children: [
                      _infoRow(Icons.cake, "Date of Birth",
                          "${birthday.dob.day}/${birthday.dob.month}/${birthday.dob.year}"),
                      const Divider(),
                      _infoRow(Icons.celebration, "Age", "$age years old"),
                      const Divider(),
                      _infoRow(
                          Icons.auto_awesome, "Zodiac Sign", zodiac),
                    ],
                  ),
                ),
              ),
            ),

            const SizedBox(height: 16),

            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 16),
              child: Card(
                shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(16)),
                elevation: 3,
                child: Padding(
                  padding: const EdgeInsets.all(16),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      const Text(
                        "Life Stats",
                        style: TextStyle(
                          fontSize: 16,
                          fontWeight: FontWeight.bold,
                          color: Colors.deepPurple,
                        ),
                      ),
                      const SizedBox(height: 12),
                      GridView.count(
                        crossAxisCount: 2,
                        shrinkWrap: true,
                        physics: const NeverScrollableScrollPhysics(),
                        crossAxisSpacing: 10,
                        mainAxisSpacing: 10,
                        childAspectRatio: 1.6,
                        children: lifeStats.entries.map((entry) {
                          return Container(
                            padding: const EdgeInsets.all(12),
                            decoration: BoxDecoration(
                              color: const Color(0xFFF5F0FF),
                              borderRadius: BorderRadius.circular(12),
                            ),
                            child: Column(
                              crossAxisAlignment:
                                  CrossAxisAlignment.start,
                              mainAxisAlignment:
                                  MainAxisAlignment.center,
                              children: [
                                Text(
                                  entry.key,
                                  style: const TextStyle(
                                    fontSize: 12,
                                    color: Colors.deepPurple,
                                  ),
                                ),
                                const SizedBox(height: 4),
                                Text(
                                  entry.value,
                                  style: const TextStyle(
                                    fontSize: 16,
                                    fontWeight: FontWeight.bold,
                                  ),
                                ),
                              ],
                            ),
                          );
                        }).toList(),
                      ),
                    ],
                  ),
                ),
              ),
            ),

            const SizedBox(height: 16),

            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 16),
              child: SizedBox(
                width: double.infinity,
                height: 55,
                child: ElevatedButton.icon(
                  onPressed: () =>
                      ShareService.shareBirthday(birthday.name),
                  icon: const Icon(Icons.share),
                  label: const Text(
                    "Share Birthday Wish",
                    style: TextStyle(
                        fontSize: 16, fontWeight: FontWeight.bold),
                  ),
                  style: ElevatedButton.styleFrom(
                    backgroundColor: Colors.deepPurple,
                    foregroundColor: Colors.white,
                    shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(15),
                    ),
                    elevation: 4,
                  ),
                ),
              ),
            ),

            const SizedBox(height: 30),
          ],
        ),
      ),
    );
  }

  Widget _infoRow(IconData icon, String label, String value) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 8),
      child: Row(
        children: [
          Icon(icon, color: Colors.deepPurple, size: 22),
          const SizedBox(width: 12),
          Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(label,
                  style: const TextStyle(
                      color: Colors.grey, fontSize: 12)),
              Text(value,
                  style: const TextStyle(
                      fontSize: 15, fontWeight: FontWeight.bold)),
            ],
          ),
        ],
      ),
    );
  }
}