import 'dart:io';

void main() {
  stdout.write("Enter number: ");
  int n = int.parse(stdin.readLineSync()!);

  int fact = 1;

  for(int i=1;i<=n;i++){
    fact *= i;
  }

  print("Factorial = $fact");
}