from mpi4py import MPI
import numpy as np

comm = MPI.COMM_WORLD
rank = comm.Get_rank()

if rank == 0:
    data = np.array([1, 2, 3, 4, 5], dtype='i')
    print("Process 0 sending:", data)

    comm.Send(data, dest=1, tag=0)

    comm.Recv(data, source=1, tag=1)
    print("Process 0 received modified array:", data)

elif rank == 1:
    data = np.empty(5, dtype='i')

    comm.Recv(data, source=0, tag=0)
    print("Process 1 received:", data)

    data = data * 2

    comm.Send(data, dest=0, tag=1)