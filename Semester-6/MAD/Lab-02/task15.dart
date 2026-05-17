import 'dart:io';

void main() {
  stdout.write("Enter hour (0-23): ");
  int hour = int.parse(stdin.readLineSync()!);

  String greeting =
      (hour < 12) ? "Good Morning"
      : (hour < 18) ? "Good Afternoon"
      : "Good Evening";

  print(greeting);
}