// File Name: toll_booth_system.cpp

#include <iostream>
using namespace std;

class toll_Booth
{
private:
    unsigned int totalCars;
    double totalCash;

public:
    toll_Booth()
    {
        totalCars = 0;
        totalCash = 0.0;
    }

    void payingCar()
    {
        totalCars++;
        totalCash += 0.50;
    }

    void nopayCar()
    {
        totalCars++;
    }

    void display()
    {
        cout << "\nTotal Cars: " << totalCars << endl;
        cout << "Total Cash: $" << totalCash << endl;
    }
};

int main()
{
    toll_Booth t;
    char ch;

    cout << "Enter 'p' for paying car\n";
    cout << "Enter 'n' for non-paying car\n";
    cout << "Enter 'e' to exit\n";

    while (true)
    {
        cin >> ch;

        if (ch == 'p' || ch == 'P')
            t.payingCar();
        else if (ch == 'n' || ch == 'N')
            t.nopayCar();
        else if (ch == 'e' || ch == 'E')
            break;
        else
            cout << "Invalid input\n";
    }

    t.display();

    return 0;
}