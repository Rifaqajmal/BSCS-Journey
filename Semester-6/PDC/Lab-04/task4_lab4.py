from mpi4py import MPI

comm = MPI.COMM_WORLD
rank = comm.Get_rank()
size = comm.Get_size()

if rank == 0:
    data = [10, 20, 30, 40]
else:
    data = None

recv = comm.scatter(data, root=0)

recv = recv * 2

result = comm.gather(recv, root=0)

if rank == 0:
    print("Final result:", result)