#include <iostream>
using namespace std;

class Node {
public:
    int data;
    Node* next;

    Node(int val) {
        data = val;
        next = NULL;
    }
};

class CircularList {
    Node* head;
    Node* tail;

public:
    CircularList() {
        head = tail = NULL;
    }

    void insert(int val) {
        Node* n = new Node(val);

        if (!head) {
            head = tail = n;
            tail->next = head;
        } else {
            tail->next = n;
            tail = n;
            tail->next = head;
        }
    }

    bool search(int key) {
        if (!head) return false;

        Node* temp = head;

        do {
            if (temp->data == key)
                return true;

            temp = temp->next;
        } while (temp != head);

        return false;
    }
};

int main() {
    CircularList cl;

    cl.insert(10);
    cl.insert(20);
    cl.insert(30);

    cout << cl.search(20) << endl; // 1 = found
    cout << cl.search(99) << endl; // 0 = not found

    return 0;
}