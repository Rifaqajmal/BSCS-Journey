#include <iostream>
using namespace std;

void countingSort(int arr[], int n) {
    int count[100] = {0};

    for (int i = 0; i < n; i++)
        count[arr[i]]++;

    int index = 0;
    for (int i = 0; i < 100; i++) {
        while (count[i]--)
            arr[index++] = i;
    }
}

int main() {
    int arr[] = {4,2,2,8,3};
    int n = sizeof(arr)/sizeof(arr[0]);

    countingSort(arr, n);

    for (int i = 0; i < n; i++)
        cout << arr[i] << " ";
}