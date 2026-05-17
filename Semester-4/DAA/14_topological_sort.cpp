#include <iostream>
#include <vector>
#include <queue>
using namespace std;

void topoSort(int V, vector<int> adj[]) {
    vector<int> indegree(V, 0);

    for (int i = 0; i < V; i++)
        for (int v : adj[i])
            indegree[v]++;

    queue<int> q;
    for (int i = 0; i < V; i++)
        if (indegree[i] == 0)
            q.push(i);

    while (!q.empty()) {
        int u = q.front();
        q.pop();
        cout << u << " ";

        for (int v : adj[u]) {
            if (--indegree[v] == 0)
                q.push(v);
        }
    }
}

int main() {
    int V = 6;
    vector<int> adj[6];

    adj[5].push_back(2);
    adj[5].push_back(0);
    adj[4].push_back(0);

    topoSort(V, adj);
}