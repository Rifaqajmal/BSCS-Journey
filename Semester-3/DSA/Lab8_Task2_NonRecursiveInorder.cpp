#include <iostream>
#include <stack>
using namespace std;

class Node {
public:
    int data;
    Node* left;
    Node* right;

    Node(int val) {
        data = val;
        left = right = NULL;
    }
};

class BST {
public:
    Node* root;

    BST() {
        root = NULL;
    }

    void insert(int val) {
        Node* newNode = new Node(val);

        if (root == NULL) {
            root = newNode;
            return;
        }

        Node* curr = root;
        Node* parent = NULL;

        while (curr != NULL) {
            parent = curr;
            if (val < curr->data)
                curr = curr->left;
            else
                curr = curr->right;
        }

        if (val < parent->data)
            parent->left = newNode;
        else
            parent->right = newNode;
    }

    void inorderNonRecursive() {
        stack<Node*> s;
        Node* curr = root;

        while (curr != NULL || !s.empty()) {
            while (curr != NULL) {
                s.push(curr);
                curr = curr->left;
            }

            curr = s.top();
            s.pop();

            cout << curr->data << " ";

            curr = curr->right;
        }
    }
};

int main() {
    BST tree;

    tree.insert(10);
    tree.insert(5);
    tree.insert(20);
    tree.insert(3);
    tree.insert(7);

    cout << "Inorder (Non-Recursive): ";
    tree.inorderNonRecursive();

    return 0;
}