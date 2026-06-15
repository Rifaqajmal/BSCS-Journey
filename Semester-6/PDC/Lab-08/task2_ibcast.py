from mpi4py import MPI

comm = MPI.COMM_WORLD
rank = comm.Get_rank()

if rank == 0:
    data = {"hostname": "server1", "rank": rank}
else:
    data = None

data = comm.bcast(data, root=0)

print("Process", rank, "received:", data)