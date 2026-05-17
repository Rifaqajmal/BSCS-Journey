import 'dart:io';

void main() {
  stdout.write("Enter number: ");
  int n = int.parse(stdin.readLineSync()!);

  int sum = 0;

  for(int i=1;i<=n;i++){
    if(i%2!=0)
      sum += i;
  }

  print("Sum of odd numbers = $sum");
}