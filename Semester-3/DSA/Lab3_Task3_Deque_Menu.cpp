#include <iostream>
using namespace std;

class Deque {
    int arr[10];
    int front, rear;

public:
    Deque() {
        front = -1;
        rear = -1;
    }

    bool isFull() {
        return (front == 0 && rear == 9) || (front == rear + 1);
    }

    bool isEmpty() {
        return front == -1;
    }

    void insertRear(int x) {
        if (isFull()) return;

        if (front == -1) {
            front = rear = 0;
        } else if (rear == 9) {
            rear = 0;
        } else {
            rear++;
        }
        arr[rear] = x;
    }

    void insertFront(int x) {
        if (isFull()) return;

        if (front == -1) {
            front = rear = 0;
        } else if (front == 0) {
            front = 9;
        } else {
            front--;
        }
        arr[front] = x;
    }

    void display() {
        if (isEmpty()) return;

        int i = front;
        while (true) {
            cout << arr[i] << " ";
            if (i == rear) break;
            i = (i + 1) % 10;
        }
    }
};