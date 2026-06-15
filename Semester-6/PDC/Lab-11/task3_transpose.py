import pyopencl as cl
import numpy as np

A = np.array([[1,2,3],[4,5,6]], dtype=np.float32)

rows, cols = A.shape

platform = cl.get_platforms()[0]
device = platform.get_devices()[0]

context = cl.Context([device])
queue = cl.CommandQueue(context)

mf = cl.mem_flags

A_buf = cl.Buffer(context, mf.READ_ONLY | mf.COPY_HOST_PTR, hostbuf=A)
T_buf = cl.Buffer(context, mf.WRITE_ONLY, A.nbytes)

kernel = """
__kernel void transpose(__global float *A,
                        __global float *T,
                        int rows, int cols)
{
    int i = get_global_id(0);
    int j = get_global_id(1);

    T[j*rows + i] = A[i*cols + j];
}
"""

program = cl.Program(context, kernel).build()

program.transpose(queue, (rows, cols), None,
                  A_buf, T_buf,
                  np.int32(rows), np.int32(cols))

T = np.empty((cols, rows), dtype=np.float32)
cl.enqueue_copy(queue, T, T_buf)

print("Transpose:\n", T)