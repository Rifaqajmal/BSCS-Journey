import 'package:flutter/material.dart';
import '../models/birthday_model.dart';

class BirthdayProvider extends ChangeNotifier {
  List<BirthdayModel> _birthdays = [];
  List<BirthdayModel> _filtered = [];

  List<BirthdayModel> get birthdays => _birthdays;

  List<BirthdayModel> get filteredBirthdays {
    return _filtered.isNotEmpty ? _filtered : _birthdays;
  }

  // SET DATA (REALTIME FIREBASE)
  void setBirthdays(List<BirthdayModel> data) {
    _birthdays = data;
    _filtered = [];
    notifyListeners();
  }

  // ADD
  void addBirthday(BirthdayModel birthday) {
    _birthdays.add(birthday);
    notifyListeners();
  }

  // REMOVE
  void removeBirthday(String id) {
    _birthdays.removeWhere((b) => b.id == id);
    notifyListeners();
  }

  // CLEAR
  void clear() {
    _birthdays.clear();
    _filtered.clear();
    notifyListeners();
  }

  // SEARCH
  void search(String query) {
    if (query.isEmpty) {
      _filtered = [];
    } else {
      _filtered = _birthdays.where((b) {
        return b.name.toLowerCase().contains(query.toLowerCase()) ||
            b.relation.toLowerCase().contains(query.toLowerCase());
      }).toList();
    }
    notifyListeners();
  }

  // SORT
  void sortByUpcoming() {
    final now = DateTime.now();

    _birthdays.sort((a, b) {
      DateTime aNext = DateTime(now.year, a.dob.month, a.dob.day);
      DateTime bNext = DateTime(now.year, b.dob.month, b.dob.day);

      if (aNext.isBefore(now)) {
        aNext = DateTime(now.year + 1, a.dob.month, a.dob.day);
      }

      if (bNext.isBefore(now)) {
        bNext = DateTime(now.year + 1, b.dob.month, b.dob.day);
      }

      return aNext.compareTo(bNext);
    });

    notifyListeners();
  }
}