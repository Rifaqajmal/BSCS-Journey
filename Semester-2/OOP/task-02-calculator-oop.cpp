// File Name: calculator_oop.cpp

#include <iostream>
using namespace std;

class testing
{
public:
    float x, y;
    char op;
    double res;

    void input()
    {
        cout << "\nEnter first number: ";
        cin >> x;

        cout << "Enter operator (+, -, *, /): ";
        cin >> op;

        cout << "Enter second number: ";
        cin >> y;
    }

    void sum() { res = x + y; }
    void sub() { res = x - y; }
    void mul() { res = x * y; }

    void div()
    {
        if (y != 0)
            res = x / y;
        else
        {
            cout << "Error: Division by zero!" << endl;
            res = 0;
        }
    }

    void eval()
    {
        switch (op)
        {
            case '+': sum(); break;
            case '-': sub(); break;
            case '*': mul(); break;
            case '/': div(); break;
            default:
                cout << "Invalid operator!" << endl;
        }
    }

    void disp()
    {
        cout << "Result = " << res << endl;
    }
};

int main()
{
    testing t;
    char ch;

    do
    {
        t.input();
        t.eval();
        t.disp();

        cout << "\nDo another operation? (Y/N): ";
        cin >> ch;

    } while (ch != 'N' && ch != 'n');

    return 0;
}