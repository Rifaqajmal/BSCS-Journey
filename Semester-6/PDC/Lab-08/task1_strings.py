from mpi4py import MPI

comm = MPI.COMM_WORLD
rank = comm.Get_rank()

if rank == 0:
    data = ["Hello", "MPI", "World"]
    req = comm.isend(data, dest=1, tag=0)
    req.wait()
    print("Process 0 sent:", data)

elif rank == 1:
    req = comm.irecv(source=0, tag=0)
    data = req.wait()
    print("Process 1 received:", data)