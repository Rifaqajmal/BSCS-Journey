#include <iostream>
using namespace std;

struct Node {
    int data;
    Node* next;
};

void removeN(Node*& head, int n) {
    Node *temp = head, *prev = NULL;

    while (temp != NULL && temp->data != n) {
        prev = temp;
        temp = temp->next;
    }

    if (temp == NULL) return;

    if (prev == NULL) {
        head = temp->next;
    } else {
        prev->next = temp->next;
    }

    delete temp;
}