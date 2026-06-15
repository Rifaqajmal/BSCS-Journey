import 'package:firebase_database/firebase_database.dart';
import '../models/birthday_model.dart';

class DatabaseService {
  final DatabaseReference _db = FirebaseDatabase.instance.ref();

  // CREATE
  Future<void> addBirthday(String uid, BirthdayModel birthday) async {
    await _db
        .child('users')
        .child(uid)
        .child('birthdays')
        .child(birthday.id)
        .set(birthday.toMap());
  }

  // UPDATE
  Future<void> updateBirthday(String uid, BirthdayModel birthday) async {
    await _db
        .child('users')
        .child(uid)
        .child('birthdays')
        .child(birthday.id)
        .update(birthday.toMap());
  }

  // DELETE
  Future<void> deleteBirthday(String uid, String id) async {
    await _db
        .child('users')
        .child(uid)
        .child('birthdays')
        .child(id)
        .remove();
  }

  // ONE-TIME FETCH
  Future<List<BirthdayModel>> getBirthdays(String uid) async {
    final snapshot =
        await _db.child('users').child(uid).child('birthdays').get();

    if (!snapshot.exists || snapshot.value == null) return [];

    final data = Map<dynamic, dynamic>.from(snapshot.value as Map);

    return data.entries.map((e) {
      return BirthdayModel.fromMap(e.value);
    }).toList();
  }

  // REAL-TIME STREAM (NEW)
  Stream<List<BirthdayModel>> streamBirthdays(String uid) {
    return _db
        .child('users')
        .child(uid)
        .child('birthdays')
        .onValue
        .map((event) {
      final data = event.snapshot.value;

      if (data == null) return [];

      final map = Map<dynamic, dynamic>.from(data as Map);

      return map.entries.map((e) {
        return BirthdayModel.fromMap(e.value);
      }).toList();
    });
  }
}