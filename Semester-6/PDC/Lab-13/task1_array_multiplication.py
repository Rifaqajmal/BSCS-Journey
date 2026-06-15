import numpy as np
import time

# input arrays
A = np.array([1, 2, 3, 4, 5], dtype=np.float32)
B = np.array([10, 20, 30, 40, 50], dtype=np.float32)

# start timer
start = time.time()

# element-wise multiplication
C = A * B

# end timer
end = time.time()

print("Array A:")
print(A)

print("\nArray B:")
print(B)

print("\nMultiplication Result:")
print(C)

print("\nExecution Time:", end - start)

# validation with NumPy
numpy_result = np.multiply(A, B)

print("\nNumPy Result:")
print(numpy_result)

if np.array_equal(C, numpy_result):
    print("\nValidation Successful")
else:
    print("\nValidation Failed")