import numpy as np

# input matrix
A = np.array([
    [1, 2, 3],
    [4, 5, 6]
], dtype=np.float32)

# transpose
transpose_matrix = A.T

print("Original Matrix:")
print(A)

print("\nTranspose Matrix:")
print(transpose_matrix)

# validation
numpy_transpose = np.transpose(A)

print("\nNumPy Transpose:")
print(numpy_transpose)

if np.array_equal(transpose_matrix, numpy_transpose):
    print("\nValidation Successful")
else:
    print("\nValidation Failed")