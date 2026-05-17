#include <iostream>
#include <algorithm>
using namespace std;

struct Item {
    int value, weight;
};

bool cmp(Item a, Item b) {
    return (double)a.value/a.weight > (double)b.value/b.weight;
}

double knapsack(Item arr[], int n, int W) {
    sort(arr, arr+n, cmp);

    double total = 0;

    for (int i = 0; i < n; i++) {
        if (W >= arr[i].weight) {
            W -= arr[i].weight;
            total += arr[i].value;
        } else {
            total += arr[i].value * ((double)W / arr[i].weight);
            break;
        }
    }
    return total;
}

int main() {
    Item arr[] = {{60,10},{100,20},{120,30}};
    cout << knapsack(arr, 3, 50);
}