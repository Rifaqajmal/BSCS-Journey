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

    void populateBST(int n) {
        int mid = n / 2;

        // simple balanced-like insertion (middle-first approach)
        for (int i = 1; i <= n; i++) {
            int val;
            cin >> val;
            root = insert(root, val);
        }
    }
};