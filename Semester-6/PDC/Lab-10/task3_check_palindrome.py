import pyopencl as cl
import numpy as np

input_str = "madam"
data = np.frombuffer(input_str.encode(), dtype=np.uint8)
result = np.array([1], dtype=np.int32)

context = cl.create_some_context()
queue = cl.CommandQueue(context, properties=cl.command_queue_properties.PROFILING_ENABLE)

program = cl.Program(context, """
__kernel void check_palindrome(__global char* str, __global int* result, int n) {
    int id = get_global_id(0);

    if (id < n / 2) {
        if (str[id] != str[n - id - 1]) {
            result[0] = 0;
        }
    }
}
""").build()

mf = cl.mem_flags
data_buf = cl.Buffer(context, mf.READ_ONLY | mf.COPY_HOST_PTR, hostbuf=data)
result_buf = cl.Buffer(context, mf.READ_WRITE | mf.COPY_HOST_PTR, hostbuf=result)

event = program.check_palindrome(queue, data.shape, None, data_buf, result_buf, np.int32(len(data)))
event.wait()

cl.enqueue_copy(queue, result, result_buf)

if result[0] == 1:
    print("Palindrome")
else:
    print("Not Palindrome")

print("Execution time (ns):", event.profile.end - event.profile.start)