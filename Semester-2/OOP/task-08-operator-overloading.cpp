// Program 4: Operator Overloading

#include <iostream>
using namespace std;

class Number
{
public:
    int value;

    Number(int v)
    {
        value = v;
    }

    Number operator+(Number n)
    {
        return Number(value + n.value);
    }
};

int main()
{
    Number n1(5), n2(10);
    Number n3 = n1 + n2;

    cout << "Sum: " << n3.value << endl;

    return 0;
}