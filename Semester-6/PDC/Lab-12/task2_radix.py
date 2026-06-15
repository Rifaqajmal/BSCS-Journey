#We simulate radix sort using bit operations (simple + acceptable for lab)
import numpy as np

def radix_sort(arr):
    max_val = max(arr)
    exp = 1

    while max_val // exp > 0:
        buckets = [[] for _ in range(10)]

        for num in arr:
            index = (num // exp) % 10
            buckets[index].append(num)

        arr = [num for bucket in buckets for num in bucket]

        print("Pass:", arr)   # visualization
        exp *= 10

    return arr

data = [170, 45, 75, 90, 802, 24, 2, 66]

sorted_arr = radix_sort(data)

print("Final Sorted:", sorted_arr)