import numpy as np

# input matrix
A = np.array([
    [1, 2, 3],
    [4, 5, 6],
    [7, 8, 9]
], dtype=np.float32)

# row-wise sum
row_sum = np.sum(A, axis=1)

print("Matrix:")
print(A)

print("\nRow-wise Sum:")
print(row_sum)