import 'package:flutter_local_notifications/flutter_local_notifications.dart';
import 'package:timezone/timezone.dart' as tz;
import 'package:timezone/data/latest.dart' as tz;
import '../services/notification_service.dart';

class NotificationService {
  static final FlutterLocalNotificationsPlugin _plugin =
      FlutterLocalNotificationsPlugin();

  // INIT
  static Future<void> init() async {
    tz.initializeTimeZones();

    const android = AndroidInitializationSettings('@mipmap/ic_launcher');

    const settings = InitializationSettings(android: android);

    await _plugin.initialize(settings);
  }

  // SIMPLE NOTIFICATION (TEST)
  static Future<void> show(String title, String body) async {
    const androidDetails = AndroidNotificationDetails(
      'birthday_channel',
      'Birthday Notifications',
      importance: Importance.max,
      priority: Priority.high,
    );

    const details = NotificationDetails(android: androidDetails);

    await _plugin.show(0, title, body, details);
  }

  // SCHEDULE BIRTHDAY NOTIFICATION
  static Future<void> scheduleBirthdayNotification({
    required int id,
    required String name,
    required DateTime birthday,
  }) async {
    final now = DateTime.now();

    DateTime nextBirthday = DateTime(now.year, birthday.month, birthday.day);

    if (nextBirthday.isBefore(now)) {
      nextBirthday =
          DateTime(now.year + 1, birthday.month, birthday.day);
    }

    const androidDetails = AndroidNotificationDetails(
      'birthday_channel',
      'Birthday Notifications',
      importance: Importance.max,
      priority: Priority.high,
    );

    const details = NotificationDetails(android: androidDetails);

    // TODAY NOTIFICATION
    await _plugin.zonedSchedule(
      id,
      "Birthday Today 🎉",
      "Wish $name a happy birthday!",
      tz.TZDateTime.from(nextBirthday, tz.local),
      details,
      androidScheduleMode: AndroidScheduleMode.exactAllowWhileIdle,
      uiLocalNotificationDateInterpretation:
          UILocalNotificationDateInterpretation.absoluteTime,
    );

    // 1 DAY BEFORE NOTIFICATION
    await _plugin.zonedSchedule(
      id + 1000,
      "Upcoming Birthday 🎂",
      "$name's birthday is tomorrow!",
      tz.TZDateTime.from(
        nextBirthday.subtract(const Duration(days: 1)),
        tz.local,
      ),
      details,
      androidScheduleMode: AndroidScheduleMode.exactAllowWhileIdle,
      uiLocalNotificationDateInterpretation:
          UILocalNotificationDateInterpretation.absoluteTime,
    );
  }
}