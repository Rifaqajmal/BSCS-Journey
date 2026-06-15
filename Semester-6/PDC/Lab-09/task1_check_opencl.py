import pyopencl as cl

for platform in cl.get_platforms():
    print("Platform:", platform.name)
    
    for device in platform.get_devices():
        print(" Device:", device.name)