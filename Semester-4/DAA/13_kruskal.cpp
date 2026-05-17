#include <iostream>
#include <vector>
#include <algorithm>
using namespace std;

struct Edge {
    int u, v, w;
};

bool cmp(Edge a, Edge b) {
    return a.w < b.w;
}

int parent[100];

int find(int i) {
    if (parent[i] == i)
        return i;
    return parent[i] = find(parent[i]);
}

void unite(int x, int y) {
    parent[find(x)] = find(y);
}

int main() {
    int V = 4;
    vector<Edge> edges = {{0,1,10},{0,2,6},{0,3,5},{2,3,4}};

    sort(edges.begin(), edges.end(), cmp);

    for (int i = 0; i < V; i++)
        parent[i] = i;

    for (auto e : edges) {
        if (find(e.u) != find(e.v)) {
            cout << e.u << "-" << e.v << " ";
            unite(e.u, e.v);
        }
    }
}