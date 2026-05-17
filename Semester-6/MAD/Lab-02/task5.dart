import 'dart:io';

void main() {
  stdout.write("Enter first number: ");
  int a = int.parse(stdin.readLineSync()!);

  stdout.write("Enter second number: ");
  int b = int.parse(stdin.readLineSync()!);

  if (a == b) {
    print("Squares: ${a*a} and ${b*b}");
  }
  else if (a % 2 == 0 && b % 2 == 0) {
    print("Sum: ${a+b}");
  }
  else if (a % 2 != 0 && b % 2 != 0) {
    print("Product: ${a*b}");
  }
  else {
    print("Difference: ${(a>b)?a-b:b-a}");
  }
}