import 'dart:io';

void main() {
  stdout.write("Enter feet: ");
  double feet = double.parse(stdin.readLineSync()!);

  double meter = feet * 0.3048;
  print("Meters = $meter");

  stdout.write("Enter meters: ");
  double meters = double.parse(stdin.readLineSync()!);

  double kilometer = meters / 1000;
  print("Kilometers = $kilometer");
}