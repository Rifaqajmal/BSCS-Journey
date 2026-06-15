#include <iostream>
using namespace std;

int main() {
    Node *head = NULL, *temp;

    int x;
    while (true) {
        cin >> x;
        if (x == -1) break;

        Node *newNode = new Node();
        newNode->data = x;
        newNode->next = NULL;

        if (head == NULL) {
            head = temp = newNode;
        } else {
            temp->next = newNode;
            temp = newNode;
        }
    }

    temp = head;
    while (temp != NULL) {
        cout << temp->data << " ";
        temp = temp->next;
    }

    return 0;
}