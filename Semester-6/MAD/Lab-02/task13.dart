import 'dart:io';

void main() {
  stdout.write("How many numbers? ");
  int count = int.parse(stdin.readLineSync()!);

  int sum = 0;

  for(int i=1;i<=count;i++){
    stdout.write("Enter number $i: ");
    int num = int.parse(stdin.readLineSync()!);
    sum += num;
  }

  print("Total sum = $sum");
}