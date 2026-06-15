import pyopencl as cl
import numpy as np

A = np.array([2,4,6], dtype=np.float32)
B = np.array([3,5,7], dtype=np.float32)

platform = cl.get_platforms()[0]
device = platform.get_devices()[0]

context = cl.Context([device])
queue = cl.CommandQueue(context)

mf = cl.mem_flags

A_buf = cl.Buffer(context, mf.READ_ONLY | mf.COPY_HOST_PTR, hostbuf=A)
B_buf = cl.Buffer(context, mf.READ_ONLY | mf.COPY_HOST_PTR, hostbuf=B)
temp_buf = cl.Buffer(context, mf.WRITE_ONLY, A.nbytes)

kernel = """
__kernel void temp_mul(__global const float *A,
                       __global const float *B,
                       __global float *temp)
{
    int i = get_global_id(0);
    temp[i] = A[i] * B[i];
}
"""

program = cl.Program(context, kernel).build()

program.temp_mul(queue, A.shape, None, A_buf, B_buf, temp_buf)

temp = np.empty_like(A)
cl.enqueue_copy(queue, temp, temp_buf)

print("Temporary array:", temp)