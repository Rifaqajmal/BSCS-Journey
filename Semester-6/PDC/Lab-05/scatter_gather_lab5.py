from mpi4py import MPI

comm = MPI.COMM_WORLD
rank = comm.Get_rank()
size = comm.Get_size()

data = None

if rank == 0:
    data = [10, 20, 30, 40]

value = comm.scatter(data, root=0)

print("Process", rank, "received", value)

value = value * 2

result = comm.gather(value, root=0)

if rank == 0:
    print("Final gathered result:", result)