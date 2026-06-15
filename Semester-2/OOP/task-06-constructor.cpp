// Program 2: Constructor Example

#include <iostream>
using namespace std;

class Student
{
public:
    int id;

    Student(int x)
    {
        id = x;
    }

    void display()
    {
        cout << "ID: " << id << endl;
    }
};

int main()
{
    Student s1(10);
    s1.display();

    return 0;
}