import pyopencl as cl
import numpy as np

input_str = "Hello OpenCL"
data = np.frombuffer(input_str.encode(), dtype=np.uint8)

context = cl.create_some_context()
queue = cl.CommandQueue(context, properties=cl.command_queue_properties.PROFILING_ENABLE)

program = cl.Program(context, """
__kernel void replace_vowels(__global char* str) {
    int id = get_global_id(0);
    char c = str[id];

    if (c=='a'||c=='e'||c=='i'||c=='o'||c=='u'||
        c=='A'||c=='E'||c=='I'||c=='O'||c=='U') {
        str[id] = '*';
    }
}
""").build()

mf = cl.mem_flags
data_buf = cl.Buffer(context, mf.READ_WRITE | mf.COPY_HOST_PTR, hostbuf=data)

event = program.replace_vowels(queue, data.shape, None, data_buf)
event.wait()

result = np.empty_like(data)
cl.enqueue_copy(queue, result, data_buf)

print("Result:", result.tobytes().decode())
print("Execution time (ns):", event.profile.end - event.profile.start)