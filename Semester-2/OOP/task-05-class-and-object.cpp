// Program 1: Basic Class and Object

#include <iostream>
using namespace std;

class Student
{
public:
    int id;
    string name;

    void display()
    {
        cout << "ID: " << id << endl;
        cout << "Name: " << name << endl;
    }
};

int main()
{
    Student s1;

    s1.id = 101;
    s1.name = "Rifaq";

    s1.display();

    return 0;
}