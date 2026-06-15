// Program 10: Simple Bank System

#include <iostream>
using namespace std;

class Bank
{
private:
    double balance;

public:
    Bank()
    {
        balance = 0;
    }

    void deposit(double amount)
    {
        balance += amount;
    }

    void withdraw(double amount)
    {
        if (amount <= balance)
            balance -= amount;
        else
            cout << "Insufficient balance\n";
    }

    void display()
    {
        cout << "Balance: " << balance << endl;
    }
};

int main()
{
    Bank b;

    b.deposit(1000);
    b.withdraw(300);
    b.display();

    return 0;
}