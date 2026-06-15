// Program 8: Encapsulation

#include <iostream>
using namespace std;

class Account
{
private:
    double balance;

public:
    void setBalance(double b)
    {
        balance = b;
    }

    double getBalance()
    {
        return balance;
    }
};

int main()
{
    Account a;

    a.setBalance(5000);
    cout << "Balance: " << a.getBalance() << endl;

    return 0;
}