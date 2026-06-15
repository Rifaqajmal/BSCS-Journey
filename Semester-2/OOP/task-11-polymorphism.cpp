// Program 7: Function Overriding

#include <iostream>
using namespace std;

class Base
{
public:
    virtual void show()
    {
        cout << "Base class function" << endl;
    }
};

class Derived : public Base
{
public:
    void show()
    {
        cout << "Derived class function" << endl;
    }
};

int main()
{
    Base* b;
    Derived d;

    b = &d;
    b->show();

    return 0;
}