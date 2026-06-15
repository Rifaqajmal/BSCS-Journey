// File Name: testing_class_basic.cpp

#include <iostream>
using namespace std;

class testing
{
private:
    int a, b;

public:
    void setData(int x)
    {
        a = x;
        cout << "Enter value for b: ";
        cin >> b;
    }

    void getData()
    {
        cout << "Value of a: " << a << endl;
        cout << "Value of b: " << b << endl;
    }
};

int main()
{
    testing obj;

    int value;
    cout << "Enter value for a: ";
    cin >> value;

    obj.setData(value);
    obj.getData();

    return 0;
}