#include <iostream>
#include <vector>
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
        head = tail = NULL;
        size = 0;
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
        size++;
    }

    void displaySize() {
        cout << "Size = " << size << endl;
    }

    void displayReverse() {
        if (!head) return;

        vector<int> v;
        Node* temp = head;

        do {
            v.push_back(temp->data);
            temp = temp->next;
        } while (temp != head);

        for (int i = v.size() - 1; i >= 0; i--) {
            cout << v[i] << " ";
        }
        cout << endl;
    }
};

int main() {
    CircularList cl;

    cl.insert(1);
    cl.insert(2);
    cl.insert(3);
    cl.insert(4);

    cl.displaySize();
    cl.displayReverse();

    return 0;
}