import 'dart:io';

int findMax(List<int> list) {
  int max = list[0];

  for (int i = 1; i < list.length; i++) {
    if (list[i] > max) {
      max = list[i];
    }
  }

  return max;
}

void main() {
  stdout.write("Enter number of elements: ");
  int n = int.parse(stdin.readLineSync()!);

  List<int> numbers = [];

  for (int i = 0; i < n; i++) {
    stdout.write("Enter number ${i + 1}: ");
    int num = int.parse(stdin.readLineSync()!);
    numbers.add(num);
  }

  int max = findMax(numbers);

  print("Maximum number is: $max");
}