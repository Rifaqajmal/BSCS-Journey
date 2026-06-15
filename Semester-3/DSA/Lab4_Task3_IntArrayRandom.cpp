#include <iostream>
#include <cstdlib>
using namespace std;

void IntArray(float arr[], int n) {
    for (int i = 0; i < n; i++) {
        arr[i] = rand() % 1000;
    }
}