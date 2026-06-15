#include <iostream>
using namespace std;

class Queue {
    int arr[10];
    int rear;

public:
    Queue() { rear = -1; }

    void enqueue(int x) {
        arr[++rear] = x;
    }

    void dequeue() {
        for (int i = 0; i < rear; i++) {
            arr[i] = arr[i + 1];
        }
        rear--;
    }

    void display() {
        for (int i = 0; i <= rear; i++) {
            cout << arr[i] << " ";
        }
    }
};