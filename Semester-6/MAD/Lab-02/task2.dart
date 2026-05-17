import 'dart:io';

void main() {
  stdout.write("Enter first number: ");
  int a = int.parse(stdin.readLineSync()!);

  stdout.write("Enter second number: ");
  int b = int.parse(stdin.readLineSync()!);

  a = a + b;
  b = a - b;
  a = a - b;

  print("After swapping:");
  print("a = $a");
  print("b = $b");
}