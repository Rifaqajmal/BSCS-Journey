import pyopencl as cl
import numpy as np

A = np.array([[1,2],[3,4],[5,6],[7,8]], dtype=np.float32)  # 4x2
B = np.array([[1,2,3],[4,5,6]], dtype=np.float32)          # 2x3

M, K = A.shape
K, N = B.shape

platform = cl.get_platforms()[0]
device = platform.get_devices()[0]

context = cl.Context([device])
queue = cl.CommandQueue(context)

mf = cl.mem_flags

A_buf = cl.Buffer(context, mf.READ_ONLY | mf.COPY_HOST_PTR, hostbuf=A)
B_buf = cl.Buffer(context, mf.READ_ONLY | mf.COPY_HOST_PTR, hostbuf=B)
C_buf = cl.Buffer(context, mf.WRITE_ONLY, M*N*4)

kernel = """
__kernel void matmul(__global float *A,
                     __global float *B,
                     __global float *C,
                     int M, int N, int K)
{
    int row = get_global_id(0);
    int col = get_global_id(1);

    float sum = 0.0;

    for (int i = 0; i < K; i++)
        sum += A[row*K + i] * B[i*N + col];

    C[row*N + col] = sum;
}
"""

program = cl.Program(context, kernel).build()

program.matmul(queue, (M, N), None, A_buf, B_buf, C_buf,
               np.int32(M), np.int32(N), np.int32(K))

C = np.empty((M, N), dtype=np.float32)
cl.enqueue_copy(queue, C, C_buf)

print("Result:\n", C)