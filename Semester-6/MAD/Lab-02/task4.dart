import 'dart:io';

void main() {
  stdout.write("Enter total days: ");
  int totalDays = int.parse(stdin.readLineSync()!);

  int years = totalDays ~/ 365;
  int remaining = totalDays % 365;

  int weeks = remaining ~/ 7;
  int days = remaining % 7;

  print("Years: $years");
  print("Weeks: $weeks");
  print("Days: $days");
}