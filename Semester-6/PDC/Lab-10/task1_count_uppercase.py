import pyopencl as cl
import numpy as np

input_str = "Hello OpenCL WORLD"
data = np.frombuffer(input_str.encode(), dtype=np.uint8)
count = np.zeros(1, dtype=np.int32)

context = cl.create_some_context()
queue = cl.CommandQueue(context, properties=cl.command_queue_properties.PROFILING_ENABLE)

program = cl.Program(context, """
__kernel void count_upper(__global char* str, __global int* count) {
    int id = get_global_id(0);
    if (str[id] >= 'A' && str[id] <= 'Z') {
        atomic_inc(count);
    }
}
""").build()

mf = cl.mem_flags
data_buf = cl.Buffer(context, mf.READ_ONLY | mf.COPY_HOST_PTR, hostbuf=data)
count_buf = cl.Buffer(context, mf.READ_WRITE | mf.COPY_HOST_PTR, hostbuf=count)

event = program.count_upper(queue, data.shape, None, data_buf, count_buf)
event.wait()

cl.enqueue_copy(queue, count, count_buf)

print("Uppercase letters:", count[0])
print("Execution time (ns):", event.profile.end - event.profile.start)