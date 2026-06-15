#include <iostream>
using namespace std;

int main() {
    int x;
    int *p = &x;

    cin >> x;

    cout << "Value: " << x << endl;
    cout << "Address (variable): " << &x << endl;
    cout << "Address (pointer): " << p << endl;

    return 0;
}