// Program 5: Inheritance

#include <iostream>
using namespace std;

class Person
{
public:
    string name;

    void showName()
    {
        cout << "Name: " << name << endl;
    }
};

class Student : public Person
{
public:
    int roll;

    void showData()
    {
        cout << "Roll No: " << roll << endl;
    }
};

int main()
{
    Student s;

    s.name = "Rifaq";
    s.roll = 123;

    s.showName();
    s.showData();

    return 0;
}