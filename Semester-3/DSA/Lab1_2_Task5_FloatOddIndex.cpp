#include <iostream>
using namespace std;

int main() {
    float arr[10];

    for (int i = 0; i < 10; i++) {
        cin >> arr[i];
    }

    float *p = arr;

    for (int i = 1; i < 10; i += 2) {
        cout << *(p + i) << " ";
    }

    return 0;
}