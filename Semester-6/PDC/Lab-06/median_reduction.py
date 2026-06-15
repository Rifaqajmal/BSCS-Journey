from mpi4py import MPI
import numpy as np

comm = MPI.COMM_WORLD
rank = comm.Get_rank()

revenue = rank * 1000 + 2000

all_revenues = comm.gather(revenue, root=0)

if rank == 0:
    median = np.median(all_revenues)
    print("Revenues:", all_revenues)
    print("Median revenue:", median)