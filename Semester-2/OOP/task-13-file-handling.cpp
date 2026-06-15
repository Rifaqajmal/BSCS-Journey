// Program 9: File Handling

#include <iostream>
#include <fstream>
using namespace std;

int main()
{
    ofstream file("data.txt");

    file << "Hello OOP";
    file.close();

    ifstream read("data.txt");
    string text;

    getline(read, text);
    cout << text;

    read.close();

    return 0;
}