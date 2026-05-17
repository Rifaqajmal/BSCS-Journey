import 'dart:io';

void main() {
  stdout.write("Enter character: ");
  String ch = stdin.readLineSync()!;

  if (ch=='a'||ch=='e'||ch=='i'||ch=='o'||ch=='u'||
      ch=='A'||ch=='E'||ch=='I'||ch=='O'||ch=='U')
    print("Vowel");
  else
    print("Consonant");
}