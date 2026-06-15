import pyopencl as cl
import numpy as np

# vectors
A = np.array([1,2,3,4], dtype=np.float32)
B = np.array([5,6,7,8], dtype=np.float32)

platform = cl.get_platforms()[0]
device = platform.get_devices()[0]

context = cl.Context([device])
queue = cl.CommandQueue(context)

mf = cl.mem_flags

A_buf = cl.Buffer(context, mf.READ_ONLY | mf.COPY_HOST_PTR, hostbuf=A)
B_buf = cl.Buffer(context, mf.READ_ONLY | mf.COPY_HOST_PTR, hostbuf=B)
C_buf = cl.Buffer(context, mf.WRITE_ONLY, A.nbytes)

kernel_code = """
__kernel void subtract(__global const float *A,
                       __global const float *B,
                       __global float *C)
{
    int i = get_global_id(0);
    C[i] = A[i] - B[i];
}
"""

program = cl.Program(context, kernel_code).build()

program.subtract(queue, A.shape, None, A_buf, B_buf, C_buf)

C = np.empty_like(A)
cl.enqueue_copy(queue, C, C_buf)

print("Vector A:", A)
print("Vector B:", B)
print("Subtraction Result:", C)