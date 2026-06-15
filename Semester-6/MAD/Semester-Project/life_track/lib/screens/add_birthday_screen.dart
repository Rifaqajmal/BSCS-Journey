import 'package:flutter/material.dart';
import 'package:uuid/uuid.dart';

import '../models/birthday_model.dart';
import '../services/database_service.dart';

class AddBirthdayScreen extends StatefulWidget {
  final String uid;

  const AddBirthdayScreen({super.key, required this.uid});

  @override
  State<AddBirthdayScreen> createState() => _AddBirthdayScreenState();
}

class _AddBirthdayScreenState extends State<AddBirthdayScreen> {
  final nameController = TextEditingController();
  final relationController = TextEditingController();
  DateTime? selectedDate;
  bool loading = false;

  final DatabaseService db = DatabaseService();

  void pickDate() async {
    DateTime? date = await showDatePicker(
      context: context,
      firstDate: DateTime(1900),
      lastDate: DateTime.now(),
      initialDate: DateTime(2000),
      builder: (context, child) {
        return Theme(
          data: Theme.of(context).copyWith(
            colorScheme: ColorScheme.light(
              primary: Colors.deepPurple,
              onPrimary: Colors.white,
              surface: Colors.white,
            ),
          ),
          child: child!,
        );
      },
    );

    if (date != null) {
      setState(() => selectedDate = date);
    }
  }

  void saveBirthday() async {
    if (nameController.text.isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text("Please enter a name")),
      );
      return;
    }
    if (selectedDate == null) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text("Please select a date of birth")),
      );
      return;
    }

    setState(() => loading = true);

    final id = const Uuid().v4();

    final birthday = BirthdayModel(
      id: id,
      name: nameController.text.trim(),
      dob: selectedDate!,
      relation: relationController.text.trim(),
    );

    await db.addBirthday(widget.uid, birthday);

    setState(() => loading = false);
    Navigator.pop(context);
  }

  String _relationIcon(String relation) {
    switch (relation.toLowerCase()) {
      case 'mother':
      case 'father':
      case 'parent':
        return '👨‍👩‍👧';
      case 'brother':
      case 'sister':
        return '👫';
      case 'friend':
        return '👫';
      case 'wife':
      case 'husband':
        return '💑';
      default:
        return '🎂';
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFFF5F0FF),
      appBar: AppBar(
        backgroundColor: Colors.deepPurple,
        foregroundColor: Colors.white,
        title: const Text(
          "Add Birthday",
          style: TextStyle(fontWeight: FontWeight.bold),
        ),
        elevation: 0,
      ),
      body: SingleChildScrollView(
        child: Column(
          children: [
            // HEADER
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
              child: const Column(
                children: [
                  Text("🎂", style: TextStyle(fontSize: 50)),
                  SizedBox(height: 10),
                  Text(
                    "Add a Birthday",
                    style: TextStyle(
                      color: Colors.white,
                      fontSize: 22,
                      fontWeight: FontWeight.bold,
                    ),
                  ),
                  Text(
                    "Never miss a special day",
                    style: TextStyle(color: Colors.white70, fontSize: 14),
                  ),
                ],
              ),
            ),

            const SizedBox(height: 30),

            // FORM CARD
            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 20),
              child: Card(
                shape: RoundedRectangleBorder(
                  borderRadius: BorderRadius.circular(20),
                ),
                elevation: 4,
                child: Padding(
                  padding: const EdgeInsets.all(20),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      // NAME FIELD
                      const Text(
                        "Full Name",
                        style: TextStyle(
                          fontWeight: FontWeight.bold,
                          color: Colors.deepPurple,
                        ),
                      ),
                      const SizedBox(height: 8),
                      TextField(
                        controller: nameController,
                        decoration: InputDecoration(
                          hintText: "e.g. Ali Khan",
                          prefixIcon: const Icon(Icons.person,
                              color: Colors.deepPurple),
                          filled: true,
                          fillColor: const Color(0xFFF5F0FF),
                          border: OutlineInputBorder(
                            borderRadius: BorderRadius.circular(12),
                            borderSide: BorderSide.none,
                          ),
                        ),
                      ),

                      const SizedBox(height: 20),

                      // RELATION FIELD
                      const Text(
                        "Relation",
                        style: TextStyle(
                          fontWeight: FontWeight.bold,
                          color: Colors.deepPurple,
                        ),
                      ),
                      const SizedBox(height: 8),
                      TextField(
                        controller: relationController,
                        decoration: InputDecoration(
                          hintText: "e.g. Friend, Brother, Mother",
                          prefixIcon: const Icon(Icons.people,
                              color: Colors.deepPurple),
                          filled: true,
                          fillColor: const Color(0xFFF5F0FF),
                          border: OutlineInputBorder(
                            borderRadius: BorderRadius.circular(12),
                            borderSide: BorderSide.none,
                          ),
                        ),
                      ),

                      const SizedBox(height: 20),

                      // DATE PICKER
                      const Text(
                        "Date of Birth",
                        style: TextStyle(
                          fontWeight: FontWeight.bold,
                          color: Colors.deepPurple,
                        ),
                      ),
                      const SizedBox(height: 8),
                      GestureDetector(
                        onTap: pickDate,
                        child: Container(
                          padding: const EdgeInsets.symmetric(
                              horizontal: 16, vertical: 14),
                          decoration: BoxDecoration(
                            color: const Color(0xFFF5F0FF),
                            borderRadius: BorderRadius.circular(12),
                          ),
                          child: Row(
                            children: [
                              const Icon(Icons.cake,
                                  color: Colors.deepPurple),
                              const SizedBox(width: 12),
                              Text(
                                selectedDate == null
                                    ? "Tap to select date"
                                    : "${selectedDate!.day}/${selectedDate!.month}/${selectedDate!.year}",
                                style: TextStyle(
                                  fontSize: 16,
                                  color: selectedDate == null
                                      ? Colors.grey
                                      : Colors.black87,
                                ),
                              ),
                              const Spacer(),
                              const Icon(Icons.arrow_drop_down,
                                  color: Colors.deepPurple),
                            ],
                          ),
                        ),
                      ),
                    ],
                  ),
                ),
              ),
            ),

            const SizedBox(height: 30),

            // SAVE BUTTON
            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 20),
              child: SizedBox(
                width: double.infinity,
                height: 55,
                child: ElevatedButton(
                  onPressed: loading ? null : saveBirthday,
                  style: ElevatedButton.styleFrom(
                    backgroundColor: Colors.deepPurple,
                    foregroundColor: Colors.white,
                    shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(15),
                    ),
                    elevation: 4,
                  ),
                  child: loading
                      ? const CircularProgressIndicator(color: Colors.white)
                      : const Row(
                          mainAxisAlignment: MainAxisAlignment.center,
                          children: [
                            Icon(Icons.save),
                            SizedBox(width: 10),
                            Text(
                              "Save Birthday",
                              style: TextStyle(
                                fontSize: 18,
                                fontWeight: FontWeight.bold,
                              ),
                            ),
                          ],
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
}