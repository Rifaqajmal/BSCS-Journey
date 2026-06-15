import pyopencl as cl
import numpy as np
import time

size = 512  # you can increase later

A = np.random.rand(size, size).astype(np.float32)
B = np.random.rand(size, size).astype(np.float32)

# CPU timing
start = time.time()
C_cpu = np.dot(A, B)
end = time.time()

print("CPU Time:", end - start)

# OpenCL setup
platform = cl.get_platforms()[0]
device = platform.get_devices()[0]
context = cl.Context([device])
queue = cl.CommandQueue(context)

mf = cl.mem_flags

A_buf = cl.Buffer(context, mf.READ_ONLY | mf.COPY_HOST_PTR, hostbuf=A)
B_buf = cl.Buffer(context, mf.READ_ONLY | mf.COPY_HOST_PTR, hostbuf=B)
C_buf = cl.Buffer(context, mf.WRITE_ONLY, A.nbytes)

kernel = """
__kernel void matmul(__global float *A,
                     __global float *B,
                     __global float *C,
                     int N)
{
    int row = get_global_id(0);
    int col = get_global_id(1);

    float sum = 0.0;

    for (int i = 0; i < N; i++)
        sum += A[row*N + i] * B[i*N + col];

    C[row*N + col] = sum;
}
"""

program = cl.Program(context, kernel).build()

start = time.time()

program.matmul(queue, (size, size), None,
               A_buf, B_buf, C_buf,
               np.int32(size))

C = np.empty_like(A)
cl.enqueue_copy(queue, C, C_buf)

end = time.time()

print("GPU(OpenCL) Time:", end - start)