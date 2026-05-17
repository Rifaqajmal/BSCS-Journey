#include <iostream>
using namespace std;

struct Node {
    int data;
    Node* left;
    Node* right;
};

Node* insert(Node* root, int key) {
    if (!root) {
        root = new Node{key, NULL, NULL};
        return root;
    }

    if (key < root->data)
        root->left = insert(root->left, key);
    else
        root->right = insert(root->right, key);

    return root;
}

void inorder(Node* root) {
    if (root) {
        inorder(root->left);
        cout << root->data << " ";
        inorder(root->right);
    }
}

int main() {
    Node* root = NULL;
    int keys[] = {50,30,20,40};

    for (int i = 0; i < 4; i++)
        root = insert(root, keys[i]);

    inorder(root);
}