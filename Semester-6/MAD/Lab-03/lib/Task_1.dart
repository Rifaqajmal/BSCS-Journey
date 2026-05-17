import 'dart:io';

// Function for area (optional named parameters)
double area({double? radius, double? length, double? width, double? base, double? height}) {
  if (radius != null) {
    return 3.14 * radius * radius; // Circle
  } 
  else if (length != null && width != null) {
    return length * width; // Rectangle
  } 
  else if (base != null && height != null) {
    return 0.5 * base * height; // Triangle
  } 
  else {
    return 0;
  }
}

void main() {

  stdout.write("Enter size of dataset: ");
  int n = int.parse(stdin.readLineSync()!);

  List<int> numbers = [];
  int totalSum = 0;
  int evenSum = 0;
  int oddSum = 0;

  // Input numbers
  for (int i = 0; i < n; i++) {
    stdout.write("Enter number ${i + 1}: ");
    int num = int.parse(stdin.readLineSync()!);
    numbers.add(num);

    totalSum += num;

    if (num % 2 == 0)
      evenSum += num;
    else
      oddSum += num;
  }

  print("\nTotal Sum = $totalSum");
  print("Even Sum = $evenSum");
  print("Odd Sum = $oddSum");

  if (totalSum == (evenSum + oddSum)) {
    print("Verification: TRUE");
  } else {
    print("Verification: FALSE");
  }

  // Geometry part
  stdout.write("\nEnter radius of circle: ");
  double r = double.parse(stdin.readLineSync()!);
  print("Circle Area = ${area(radius: r)}");

  stdout.write("Enter rectangle length: ");
  double l = double.parse(stdin.readLineSync()!);

  stdout.write("Enter rectangle width: ");
  double w = double.parse(stdin.readLineSync()!);
  print("Rectangle Area = ${area(length: l, width: w)}");

  stdout.write("Enter triangle base: ");
  double b = double.parse(stdin.readLineSync()!);

  stdout.write("Enter triangle height: ");
  double h = double.parse(stdin.readLineSync()!);
  print("Triangle Area = ${area(base: b, height: h)}");
}