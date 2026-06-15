#include <iostream>
#include <cstdlib>
using namespace std;

class Item {
public:
    int data;
    Item* next;
    Item* prev;

    Item(int d) {
        data = d;
        next = NULL;
        prev = NULL;
    }
};

class dList {
public:
    Item* head;
    Item* tail;

    dList() {
        head = NULL;
        tail = NULL;
    }

    void insertAtHead(Item* i) {
        if (head == NULL) {
            head = tail = i;
        } else {
            i->next = head;
            head->prev = i;
            head = i;
        }
    }

    void populate(int n) {
        for (int i = 0; i < n; i++) {
            int val = (rand() % 500) + 1;
            insertAtHead(new Item(val));
        }
    }

    void display() {
        Item* temp = head;
        while (temp != NULL) {
            cout << temp->data << " ";
            temp = temp->next;
        }
        cout << endl;
    }
};