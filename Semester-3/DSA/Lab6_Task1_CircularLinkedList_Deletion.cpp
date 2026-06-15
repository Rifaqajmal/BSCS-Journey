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
    int size;

public:
    CircularList() {
        head = NULL;
        tail = NULL;
        size = 0;
    }

    void insert(int val) {
        Node* newNode = new Node(val);

        if (head == NULL) {
            head = tail = newNode;
            tail->next = head;
        } else {
            tail->next = newNode;
            tail = newNode;
            tail->next = head;
        }
        size++;
    }

    void deleteNode(int val) {
        if (head == NULL) return;

        Node *curr = head, *prev = NULL;

        // single node case
        if (head->data == val && head->next == head) {
            delete head;
            head = tail = NULL;
            size--;
            return;
        }

        do {
            if (curr->data == val) break;
            prev = curr;
            curr = curr->next;
        } while (curr != head);

        if (curr->data != val) return;

        if (curr == head) {
            head = head->next;
            tail->next = head;
        } else if (curr == tail) {
            prev->next = head;
            tail = prev;
        } else {
            prev->next = curr->next;
        }

        delete curr;
        size--;
    }

    void display() {
        if (head == NULL) return;

        Node* temp = head;
        do {
            cout << temp->data << " ";
            temp = temp->next;
        } while (temp != head);

        cout << endl;
    }
};

int main() {
    CircularList cl;

    cl.insert(10);
    cl.insert(20);
    cl.insert(30);
    cl.insert(40);

    cl.display();

    cl.deleteNode(20);

    cl.display();

    return 0;
}