// File Name: employee_records.cpp

#include <iostream>
using namespace std;

class taskOne
{
private:
    int id, age;
    float salary;
    char name[50];

public:
    void getData()
    {
        cout << "\nEnter ID: ";
        cin >> id;

        cout << "Enter Age: ";
        cin >> age;

        cout << "Enter Salary: ";
        cin >> salary;

        cin.ignore(); // FIXED: clear buffer

        cout << "Enter Name: ";
        cin.getline(name, 50);
    }

    void displayData()
    {
        cout << "\n--- Employee Data ---" << endl;
        cout << "ID: " << id << endl;
        cout << "Age: " << age << endl;
        cout << "Salary: " << salary << endl;
        cout << "Name: " << name << endl;
    }
};

int main()
{
    taskOne obj[3];

    for (int i = 0; i < 3; i++)
    {
        cout << "\nEnter data for Employee " << i + 1 << endl;
        obj[i].getData();
    }

    for (int i = 0; i < 3; i++)
    {
        obj[i].displayData();
    }

    return 0;
}