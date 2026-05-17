import 'dart:io';

void main() {
  stdout.write("Enter base: ");
  int base = int.parse(stdin.readLineSync()!);

  stdout.write("Enter power: ");
  int power = int.parse(stdin.readLineSync()!);

  int result = 1;

  for(int i=1;i<=power;i++){
    result *= base;
  }

  print("Result = $result");
}