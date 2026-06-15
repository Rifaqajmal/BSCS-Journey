#Full heap sort on GPU is complex, so we do:
#CPU Heap Sort
#Simple OpenCL kernel (swap/compare step)
#Time comparison

import pyopencl as cl
import numpy as np
import time

# -------- CPU Heap Sort --------
def heapify(arr, n, i):
    largest = i
    l = 2*i + 1
    r = 2*i + 2

    if l < n and arr[l] > arr[largest]:
        largest = l

    if r < n and arr[r] > arr[largest]:
        largest = r

    if largest != i:
        arr[i], arr[largest] = arr[largest], arr[i]
        heapify(arr, n, largest)

def heap_sort(arr):
    n = len(arr)

    for i in range(n//2 -1, -1, -1):
        heapify(arr, n, i)

    for i in range(n-1, 0, -1):
        arr[i], arr[0] = arr[0], arr[i]
        heapify(arr, i, 0)

# test data
data = np.random.randint(0, 100, 20).astype(np.int32)

# CPU timing
start = time.time()
cpu_sorted = data.copy()
heap_sort(cpu_sorted)
end = time.time()

print("CPU Sorted:", cpu_sorted)
print("CPU Time:", end - start)

# -------- OpenCL (simple compare kernel) --------
platform = cl.get_platforms()[0]
device = platform.get_devices()[0]
context = cl.Context([device])
queue = cl.CommandQueue(context)

mf = cl.mem_flags

buf = cl.Buffer(context, mf.READ_WRITE | mf.COPY_HOST_PTR, hostbuf=data)

kernel = """
__kernel void compare_swap(__global int *arr)
{
    int i = get_global_id(0);
    int j = 2*i + 1;

    if(j < 20 && arr[i] < arr[j])
    {
        int temp = arr[i];
        arr[i] = arr[j];
        arr[j] = temp;
    }
}
"""

program = cl.Program(context, kernel).build()

start = time.time()
program.compare_swap(queue, data.shape, None, buf)
result = np.empty_like(data)
cl.enqueue_copy(queue, result, buf)
end = time.time()

print("OpenCL Step Output:", result)
print("GPU Time:", end - start)