#shallow copy example

import copy

original = [[1, 2], [3, 4]]
shallow_copy = copy.copy(original)

# Modify the original
original[0][0] = 99

print("Original:", original)
print("Shallow copy:", shallow_copy)
