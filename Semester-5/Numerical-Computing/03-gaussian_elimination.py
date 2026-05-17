import numpy as np

A = np.array([[2, -1, 1],
              [3, 3, 9],
              [3, 3, 5]], dtype=float)

B = np.array([2, -1, 4], dtype=float)

n = len(B)

# Forward elimination
for i in range(n):
    for j in range(i+1, n):
        ratio = A[j][i] / A[i][i]
        for k in range(n):
            A[j][k] -= ratio * A[i][k]
        B[j] -= ratio * B[i]

# Back substitution
x = np.zeros(n)
for i in range(n-1, -1, -1):
    x[i] = B[i]
    for j in range(i+1, n):
        x[i] -= A[i][j]*x[j]
    x[i] /= A[i][i]

print("Solution:", x)