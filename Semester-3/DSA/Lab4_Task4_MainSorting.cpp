#include <iostream>
#include <cstdlib>
using namespace std;

void bubbleSort(float arr[], int n);
void insertionSort(float arr[], int n);
void IntArray(float arr[], int n);

void printArray(float arr[], int n) {
    for (int i = 0; i < n; i++) {
        cout << arr[i] << " ";
    }
    cout << endl;
}

int main() {
    int n;
    cin >> n;

    float arr[n];

    IntArray(arr, n);

    float arr1[n], arr2[n];

    for (int i = 0; i < n; i++) {
        arr1[i] = arr[i];
        arr2[i] = arr[i];
    }

    bubbleSort(arr1, n);
    insertionSort(arr2, n);

    cout << "Bubble Sort (Desc): ";
    printArray(arr1, n);

    cout << "Insertion Sort (Desc): ";
    printArray(arr2, n);

    return 0;
}