#include <iostream>
#include <vector>
#include <queue>
using namespace std;

void dijkstra(vector<pair<int,int>> adj[], int V, int src) {
    priority_queue<pair<int,int>, vector<pair<int,int>>, greater<>> pq;
    vector<int> dist(V, 1e9);

    dist[src] = 0;
    pq.push({0, src});

    while (!pq.empty()) {
        int u = pq.top().second;
        pq.pop();

        for (auto x : adj[u]) {
            int v = x.first;
            int w = x.second;

            if (dist[v] > dist[u] + w) {
                dist[v] = dist[u] + w;
                pq.push({dist[v], v});
            }
        }
    }

    for (int i = 0; i < V; i++)
        cout << dist[i] << " ";
}

int main() {
    int V = 4;
    vector<pair<int,int>> adj[4];

    adj[0].push_back({1,1});
    adj[0].push_back({2,4});
    adj[1].push_back({2,2});
    adj[2].push_back({3,1});

    dijkstra(adj, V, 0);
}