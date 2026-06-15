#include <iostream>
using namespace std;

int main() {
    int x;
    int *p = &x;

    cin >> *p;

    cout << "Value via pointer: " << *p << endl;
    cout << "Address: " << p << endl;

    return 0;
}