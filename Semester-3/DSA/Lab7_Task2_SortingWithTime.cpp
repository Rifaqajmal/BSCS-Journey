#include <iostream>
#include <cstdlib>
#include <ctime>
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

    dList() {
        head = NULL;
    }

    void insertAtHead(Item* i) {
        if (head == NULL) {
            head = i;
        } else {
            i->next = head;
            head->prev = i;
            head = i;
        }
    }

    void populate(int n) {
        for (int i = 0; i < n; i++) {
            insertAtHead(new Item(rand() % 500 + 1));
        }
    }

    // Bubble Sort (FIXED)
    void bubbleSort() {
        if (!head) return;

        bool swapped;
        Item* ptr;

        do {
            swapped = false;
            ptr = head;

            while (ptr->next != NULL) {
                if (ptr->data > ptr->next->data) {
                    int temp = ptr->data;
                    ptr->data = ptr->next->data;
                    ptr->next->data = temp;
                    swapped = true;
                }
                ptr = ptr->next;
            }
        } while (swapped);
    }

    // Insertion Sort (for linked list)
    void insertionSort() {
        if (!head) return;

        Item* sorted = NULL;
        Item* curr = head;

        while (curr != NULL) {
            Item* next = curr->next;

            if (sorted == NULL || sorted->data >= curr->data) {
                curr->next = sorted;
                if (sorted) sorted->prev = curr;
                sorted = curr;
            } else {
                Item* temp = sorted;
                while (temp->next != NULL && temp->next->data < curr->data) {
                    temp = temp->next;
                }
                curr->next = temp->next;
                if (temp->next) temp->next->prev = curr;
                temp->next = curr;
                curr->prev = temp;
            }

            curr = next;
        }

        head = sorted;
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

int main() {
    dList list;

    int n;
    cin >> n;

    list.populate(n);

    // Bubble Sort timing
    clock_t start1 = clock();
    list.bubbleSort();
    clock_t end1 = clock();

    cout << "Bubble Sorted:\n";
    list.display();
    cout << "Time: " << (double)(end1 - start1) / CLOCKS_PER_SEC << " sec\n";

    // Re-populate for fair test
    dList list2;
    list2.populate(n);

    clock_t start2 = clock();
    list2.insertionSort();
    clock_t end2 = clock();

    cout << "Insertion Sorted:\n";
    list2.display();
    cout << "Time: " << (double)(end2 - start2) / CLOCKS_PER_SEC << " sec\n";

    return 0;
}