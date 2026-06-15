from mpi4py import MPI
import time

comm = MPI.COMM_WORLD
rank = comm.Get_rank()

if rank == 0:
    message = "Hello from Process 0"

    # non-blocking send
    request = comm.isend(message, dest=1, tag=0)
    request.wait()

    print("Process 0 sent message")

elif rank == 1:
    # non-blocking receive
    request = comm.irecv(source=0, tag=0)

    print("Process 1 doing some work...")
    time.sleep(2)

    message = request.wait()
    print("Process 1 received message:", message)
