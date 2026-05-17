#deep copy example

import copy

original = [[1, 2], [3, 4]]
deep_copy = copy.deepcopy(original)

# Modify the original
original[0][0] = 99

print("Original:", original)
print("Deep copy:", deep_copy)
