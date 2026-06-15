#include <iostream>
using namespace std;

class Stack {
    int arr[10];
    int top;

public:
    Stack() { top = -1; }

    void push(int x) {
        if (top == 9) return;
        arr[++top] = x;
    }

    void display() {
        cout << "Top = " << top << endl;
        for (int i = top; i >= 0; i--) {
            cout << arr[i] << " ";
        }
    }
};