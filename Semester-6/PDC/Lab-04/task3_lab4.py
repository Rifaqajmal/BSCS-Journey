from mpi4py import MPI
import numpy as np

comm = MPI.COMM_WORLD
rank = comm.Get_rank()
size = comm.Get_size()

data = np.array(rank, dtype='i') 

if rank == 0:
    recv_data = np.empty(size, dtype='i')
else:
    recv_data = None

comm.Gather(data, recv_data, root=0)

if rank == 0:
    print("Gathered Data:", recv_data)