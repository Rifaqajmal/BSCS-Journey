#We divide array into parts and simulate parallel search
import numpy as np

def binary_search(arr, target, start, end):
    while start <= end:
        mid = (start + end) // 2
        if arr[mid] == target:
            return mid
        elif arr[mid] < target:
            start = mid + 1
        else:
            end = mid - 1
    return -1

# sorted array
arr = np.array([2,5,8,12,16,23,38,56,72,91])

target = 23

# divide into segments
segments = 2
size = len(arr)//segments

result = -1

for i in range(segments):
    start = i*size
    end = (i+1)*size - 1

    res = binary_search(arr, target, start, end)

    if res != -1:
        result = res
        break

print("Element found at index:", result)