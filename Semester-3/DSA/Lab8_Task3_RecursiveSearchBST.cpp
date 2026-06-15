#include <iostream>
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

    Node* insert(Node* node, int val) {
        if (node == NULL)
            return new Node(val);

        if (val < node->data)
            node->left = insert(node->left, val);
        else
            node->right = insert(node->right, val);

        return node;
    }

    void insert(int val) {
        root = insert(root, val);
    }

    Node* recSearchItem(Node* node, int key) {
        if (node == NULL || node->data == key)
            return node;

        if (key < node->data)
            return recSearchItem(node->left, key);

        return recSearchItem(node->right, key);
    }
};

int main() {
    BST tree;

    tree.insert(15);
    tree.insert(10);
    tree.insert(20);
    tree.insert(8);
    tree.insert(12);

    int key = 12;

    Node* result = tree.recSearchItem(tree.root, key);

    if (result != NULL)
        cout << "Found: " << result->data << endl;
    else
        cout << "Not Found" << endl;

    return 0;
}